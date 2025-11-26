import { sendSymptoms, getNearbyVets } from "./api.js";

const chatBubble = document.getElementById("artemis-bubble");
const chatBox = document.getElementById("artemis-chatbox");
const chatBody = document.querySelector(".chat-body");
const chatInput = document.querySelector(".chat-input input");
const sendButton = document.querySelector(".chat-input button");

chatBubble.addEventListener("click", () => {
  chatBox.classList.toggle("hidden");
  if (!chatBody.innerHTML.trim()) {
    addMessage("Hi! I'm Artemis 🐾 — your virtual vet assistant. How can I help today?", "bot");
  }
});

function addMessage(text, sender = "user") {
  const message = document.createElement("div");
  message.classList.add("chat-message", sender);
  message.textContent = text;
  chatBody.appendChild(message);
  chatBody.scrollTo({ top: chatBody.scrollHeight, behavior: "smooth" });
}

sendButton.addEventListener("click", handleUserMessage);
chatInput.addEventListener("keypress", (e) => {
  if (e.key === "Enter") {
    e.preventDefault();
    handleUserMessage();
  }
});

async function handleUserMessage() {
  const message = chatInput.value.trim();
  if (!message) return;
  addMessage(message, "user");
  chatInput.value = "";

  const thinking = document.createElement("div");
  thinking.classList.add("chat-message", "bot");
  thinking.textContent = "Artemis is thinking...";
  chatBody.appendChild(thinking);
  chatBody.scrollTo({ top: chatBody.scrollHeight, behavior: "smooth" });

  try {
    const reply = await generateAIResponse(message);
    thinking.remove();
    addMessage(reply, "bot");
  } catch (err) {
    thinking.remove();
    addMessage("Oops! Something went wrong while processing your request.", "bot");
    console.error(err);
  }
}

async function generateAIResponse(userMessage) {
  const lower = userMessage.toLowerCase();

  if (lower.includes("hello") || lower.includes("hi")) {
    return "Hello there! How’s your furry friend today?";
  }

  if (lower.includes("dog") || lower.includes("cat")) {
    return "That’s great! Could you describe your pet’s current symptoms?";
  }

  if (lower.includes("sick") || lower.includes("symptom") || lower.includes("ill")) {
    try {
      const apiResponse = await sendSymptoms(userMessage);
      let reply = "Thanks for the details! ";
      if (apiResponse.recommendedVets && apiResponse.recommendedVets.length > 0) {
        reply += "Here are some vets you might consider:\n";
        apiResponse.recommendedVets.forEach((vet, index) => {
          reply += `${index + 1}. ${vet.name} - ${vet.address}\n`;
        });
      } else {
        reply += "I couldn't find nearby vets, but you can try searching in your area.";
      }
      return reply;
    } catch {
      return "I had trouble fetching vet recommendations. Please try again later.";
    }
  }

  return "I’m here to assist with your pet’s health concerns. Could you tell me more about what’s wrong?";
}
