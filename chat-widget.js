// =========================
// Artemis Chat Widget Logic
// =========================

const chatBubble = document.getElementById("artemis-bubble");
const chatBox = document.getElementById("artemis-chatbox");
const chatBody = document.querySelector(".chat-body");
const chatInput = document.querySelector(".chat-input input");
const sendButton = document.querySelector(".chat-input button");

// Show/hide chatbox
chatBubble.addEventListener("click", () => {
  chatBox.classList.toggle("hidden");

  if (!chatBody.innerHTML.trim()) {
    addMessage("Hi! I'm Artemis 🐾 — your virtual vet assistant. How can I help today?", "bot");
  }
});

// Add message to chat
function addMessage(text, sender = "user") {
  const message = document.createElement("div");
  message.classList.add("chat-message", sender);
  message.textContent = text;
  chatBody.appendChild(message);
  chatBody.scrollTop = chatBody.scrollHeight;
}

// Handle sending messages
sendButton.addEventListener("click", handleUserMessage);
chatInput.addEventListener("keypress", (e) => {
  if (e.key === "Enter") handleUserMessage();
});

function handleUserMessage() {
  const message = chatInput.value.trim();
  if (!message) return;

  addMessage(message, "user");
  chatInput.value = "";

  // Simulate AI thinking
  const thinking = document.createElement("div");
  thinking.classList.add("chat-message", "bot");
  thinking.textContent = "Artemis is thinking...";
  chatBody.appendChild(thinking);
  chatBody.scrollTop = chatBody.scrollHeight;

  setTimeout(async () => {
    const reply = await generateAIResponse(message);
    thinking.remove();
    addMessage(reply, "bot");
  }, 1000);
}

// Placeholder AI response logic (replace with your real API later)
async function generateAIResponse(userMessage) {
  const lower = userMessage.toLowerCase();
  if (lower.includes("dog") || lower.includes("cat")) {
    return "That’s great! Could you describe your pet’s current symptoms?";
  } else if (lower.includes("symptom") || lower.includes("sick")) {
    return "I'm sorry to hear that. Make sure your pet stays hydrated. Would you like me to suggest nearby vets?";
  } else if (lower.includes("hello") || lower.includes("hi")) {
    return "Hello there! How’s your furry friend today?";
  } else {
    return "I’m here to assist with your pet’s health concerns. Could you tell me what’s wrong?";
  }
}