<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Diagnopet</title>
  <style>
    body {
      font-family: 'Poppins', Georgia;
      margin: 0;
      padding: 0;
      background: linear-gradient(180deg, #f8fbff 0%, #e8f2ff 100%);
      overflow-x: hidden;
      color: #003366;
      text-align: center;
    }

    header {
      padding: 100px 20px 60px;
    }

    header h1 {
      font-size: 3em;
      color: #0055cc;
      margin-bottom: 10px;
    }

    header p {
      color: #336699;
      font-size: 1.2em;
      margin-bottom: 40px;
    }

    .get-started {
      background-color: #007bff;
      color: #fff;
      padding: 14px 36px;
      border-radius: 50px;
      font-size: 1.2em;
      border: none;
      cursor: pointer;
      font-weight: 600;
      transition: all 0.3s ease;
    }

    .get-started:hover {
      background-color: #0056b3;
      transform: scale(1.05);
    }

    .about-section {
      padding: 60px 20px;
      max-width: 800px;
      margin: 0 auto;
      opacity: 0;
      transform: translateY(20px);
      transition: opacity 0.6s ease, transform 0.6s ease;
    }

    .about-section.fade-in {
      opacity: 1;
      transform: translateY(0);
    }

    .about-section h2 {
      font-size: 2.5em;
      color: #0055cc;
      margin-bottom: 20px;
    }

    .about-section p {
      color: #336699;
      font-size: 1.1em;
      line-height: 1.6;
    }

    .vision-section {
      padding: 60px 20px;
      max-width: 800px;
      margin: 0 auto;
      opacity: 0;
      transform: translateY(20px);
      transition: opacity 0.6s ease, transform 0.6s ease;
    }

    .vision-section.fade-in {
      opacity: 1;
      transform: translateY(0);
    }

    .vision-section h2 {
      font-size: 2.5em;
      color: #0055cc;
      margin-bottom: 20px;
    }

    .vision-section p {
      color: #336699;
      font-size: 1.1em;
      line-height: 1.6;
    }

    .mission-section {
      padding: 60px 20px;
      max-width: 800px;
      margin: 0 auto;
      opacity: 0;
      transform: translateY(20px);
      transition: opacity 0.6s ease, transform 0.6s ease;
    }

    .mission-section.fade-in {
      opacity: 1;
      transform: translateY(0);
    }

    .mission-section h2 {
      font-size: 2.5em;
      color: #0055cc;
      margin-bottom: 20px;
    }

    .mission-section p {
      color: #336699;
      font-size: 1.1em;
      line-height: 1.6;
    }

    /* --- Floating Bubbles --- */
    .bubble {
      position: absolute;
      border-radius: 50%;
      background: rgba(0, 123, 255, 0.15);
      animation: float 8s infinite ease-in-out;
      z-index: 0;
    }

    @keyframes float {
      0% { transform: translateY(0) scale(1); }
      50% { transform: translateY(-20px) scale(1.1); }
      100% { transform: translateY(0) scale(1); }
    }

    footer {
      background-color: #003366;
      color: #fff;
      padding: 20px;
      text-align: center;
      margin-top: 60px;
    }

    .footer-links {
      display: flex;
      justify-content: center;
      gap: 20px;
    }

    .footer-links a {
      color: #fff;
      text-decoration: none;
      font-size: 1.1em;
      transition: color 0.3s ease;
    }

    .footer-links a:hover {
      color: #007bff;
    }

    /* Chat Widget Styles */
    #artemis-bubble {
      position: fixed;
      bottom: 20px;
      right: 20px;
      width: 50px;
      height: 50px;
      border-radius: 50%;
      background: #0073e6;
      color: white;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 24px;
      cursor: pointer;
      box-shadow: 0 4px 12px rgba(0,0,0,0.3);
      z-index: 1000;
    }
    #artemis-widget {
      position: fixed;
      bottom: 80px;
      right: 20px;
      width: 300px;
      height: 400px;
      background: white;
      border-radius: 12px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.3);
      display: none;
      flex-direction: column;
      overflow: hidden;
    }
    #chat-header {
      background: #0073e6;
      color: white;
      padding: 10px;
      text-align: center;
    }
    #chat-body {
      flex: 1;
      padding: 10px;
      overflow-y: auto;
    }
    #chat-input-area {
      display: flex;
      border-top: 1px solid #ddd;
    }
    #chat-input {
      flex: 1;
      border: none;
      padding: 10px;
      font-size: 14px;
    }
    #chat-send {
      background: #0073e6;
      border: none;
      color: white;
      padding: 0 15px;
      cursor: pointer;
    }
    .chat-message {
      margin: 6px 0;
      padding: 8px 12px;
      border-radius: 10px;
      max-width: 80%;
      word-wrap: break-word;
    }
    .chat-message.user {
      background: #cce5ff;
      align-self: flex-end;
    }
    .chat-message.bot {
      background: #f1f1f1;
      align-self: flex-start;
    }
  </style>
</head>
<body>
  <div class="bubble" style="width:120px;height:120px;top:20%;left:15%;animation-delay:0s;"></div>
  <div class="bubble" style="width:80px;height:80px;top:60%;left:70%;animation-delay:2s;"></div>
  <div class="bubble" style="width:100px;height:100px;top:40%;left:40%;animation-delay:4s;"></div>
  <div class="bubble" style="width:60px;height:60px;top:80%;left:25%;animation-delay:1s;"></div>

  <header>
    <h1>DIAGNOPET</h1>
    <p>Your intelligent pet health assistant </p>
    <button class="get-started" id="getStartedBtn">Get Started</button>
  </header>

  <section class="about-section" id="aboutSection">
    <h2>ABOUT US</h2>
    <p>Welcome to Diagnopet, a web-based diagnostic support system created to assist pet owners in understanding their pets’ health conditions. Our platform is designed to provide a preliminary evaluation of symptoms based on the information you provide, offering helpful insights before consulting a professional veterinarian.</p>
    <p>At Diagnopet, we believe that early awareness leads to better care. Our goal is to empower pet owners with accessible, reliable, and user-friendly tools that promote proactive pet health management. Whether it’s a sudden change in behavior or visible symptoms, Diagnopet helps you take the first step in identifying possible conditions and making informed decisions for your pet’s well-being.</p>
  </section>

  <section class="vision-section" id="visionSection">
    <h2>VISION</h2>
    <p>To become a trusted and innovative digital companion for every pet owner, empowering them to make informed decisions and promoting a future where technology and compassion work together for healthier, happier pets.</p>
  </section>

  <section class="mission-section">
    <h2>MISSION</h2>
    <p>Our mission is to provide an accessible, reliable, and intelligent web-based diagnostic support system that assists pet owners in identifying their pets’ symptoms and understanding potential health conditions. Through Diagnopet, we strive to promote early detection and preventive care, deliver accurate and user-friendly diagnostic insights, encourage responsible pet ownership, and enhance pet health awareness through continuous innovation and education.</p>
  </section>

  <footer>
  </footer>

  <!-- 🩵 Artemis Widget -->
  <div id="artemis-bubble">🐾</div>
  <div id="artemis-widget">
    <div id="chat-header">🐾 Artemis Vet Assistant</div>
    <div id="chat-body"></div>
    <div id="chat-input-area">
      <input id="chat-input" type="text" placeholder="Type your message..." />
      <button id="chat-send">➤</button>
    </div>
  </div>

  <script>
    document.getElementById("getStartedBtn").addEventListener("click", () => {
      window.location.href = "role-select.php";
    });

    // Scroll animation for about, vision, and mission sections
    const sections = document.querySelectorAll('.about-section, .vision-section, .mission-section');
    const observer = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.classList.add('fade-in');
        }
      });
    }, { threshold: 0.1 });

    sections.forEach(section => observer.observe(section));

    // Chat Widget
    const bubble = document.getElementById("artemis-bubble");
    const widget = document.getElementById("artemis-widget");
    const chatBody = document.getElementById("chat-body");
    const chatInput = document.getElementById("chat-input");
    const chatSend = document.getElementById("chat-send");

    bubble.onclick = () => {
      widget.style.display = widget.style.display === "none" ? "flex" : "none";
      if (!chatBody.innerHTML) addMessage("Hi! I'm Artemis 🐾. How can I help today?", "bot");
    };

    function addMessage(text, sender) {
      const msg = document.createElement("div");
      msg.className = `chat-message ${sender}`;
      msg.textContent = text;
      chatBody.appendChild(msg);
      chatBody.scrollTop = chatBody.scrollHeight;
    }

    async function sendMessage(message) {
      addMessage(message, "user");
      chatInput.value = "";
      addMessage("⏳ Artemis is thinking...", "bot");

      // ✅ Placeholder AI logic
      setTimeout(() => {
        chatBody.lastChild.remove();
        addMessage("This is a placeholder AI response. Connect your API here!", "bot");
      }, 1200);
    }

    chatSend.onclick = () => {
      if (chatInput.value.trim()) sendMessage(chatInput.value.trim());
    };

    chatInput.addEventListener("keypress", (e) => {
      if (e.key === "Enter" && chatInput.value.trim()) sendMessage(chatInput.value.trim());
    });
  </script>
</body>
</html>
