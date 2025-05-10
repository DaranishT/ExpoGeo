<div id="expo-chatbot">
  <!-- Orb -->
  <div id="chatbot-orb" style="
    width: 60px;
    height: 60px;
    background: #00f0ff;
    border-radius: 50%;
    position: fixed;
    bottom: 20px;
    right: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    z-index: 1000;
    box-shadow: 0 0 20px #00f0ff;
  ">
    <i class="fas fa-comment-dots" style="color: #05050a; font-size: 24px;"></i>
  </div>

  <!-- Window -->
  <div id="chatbot-window" style="
    position: fixed;
    bottom: 90px;
    right: 20px;
    width: 300px;
    height: 400px;
    background: #0a0a14;
    border: 1px solid #00f0ff;
    border-radius: 10px;
    display: none;
    z-index: 999;
    color: white;
    flex-direction: column;
  ">
    <div style="
      text-align: center;
      padding: 15px;
      font-weight: bold;
      background: rgba(0, 240, 255, 0.2);
      border-bottom: 1px solid #00f0ff;
    ">
      EXPO BOT
    </div>
    <div id="chatbot-messages" style="
      flex-grow: 1;
      padding: 15px;
      overflow-y: auto;
    ">
      <div style="
        background: rgba(255,255,255,0.1);
        padding: 10px;
        border-radius: 5px;
        margin-bottom: 10px;
        border-bottom-left-radius: 0;
        max-width: 80%;
      ">
        Hello! I'm your Expo assistant. How can I help?
      </div>
    </div>
    <div style="
      display: flex;
      padding: 15px;
      border-top: 1px solid rgba(0, 240, 255, 0.3);
    ">
      <input id="chatbot-input" type="text" placeholder="Type your question..." style="
        flex-grow: 1;
        padding: 10px 15px;
        border-radius: 20px;
        border: 1px solid #00f0ff;
        background: rgba(255,255,255,0.1);
        color: white;
        outline: none;
      ">
      <button id="chatbot-send" style="
        background: #00f0ff;
        color: #05050a;
        border: none;
        border-radius: 50%;
        width: 40px;
        height: 40px;
        margin-left: 10px;
        cursor: pointer;
      ">
        <i class="fas fa-paper-plane"></i>
      </button>
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
  // Get elements
  const orb = document.getElementById('chatbot-orb');
  const window = document.getElementById('chatbot-window');
  const input = document.getElementById('chatbot-input');
  const sendBtn = document.getElementById('chatbot-send');
  const messages = document.getElementById('chatbot-messages');
  
  // Toggle window
  orb.addEventListener('click', function(e) {
    e.stopPropagation();
    window.style.display = window.style.display === 'none' ? 'flex' : 'none';
  });
  
  // Close when clicking outside
  document.addEventListener('click', function() {
    window.style.display = 'none';
  });
  
  // Prevent window clicks from closing
  window.addEventListener('click', function(e) {
    e.stopPropagation();
  });
  
  // Send message function
  function sendMessage() {
    const message = input.value.trim();
    if (message) {
      // Add user message
      addMessage(message, 'user');
      input.value = '';
      
      // Simulate bot response after delay
      setTimeout(() => {
        const responses = [
          "I can help with exhibition information!",
          "Ticket details will be available soon.",
          "Which exhibition are you interested in?",
          "Check our website for more details.",
          "Registration opens next week!"
        ];
        const response = responses[Math.floor(Math.random() * responses.length)];
        addMessage(response, 'bot');
      }, 800);
    }
  }
  
  // Add message to chat
  function addMessage(text, sender) {
    const messageDiv = document.createElement('div');
    messageDiv.style.cssText = `
      padding: 10px 15px;
      border-radius: 5px;
      margin-bottom: 10px;
      max-width: 80%;
      word-wrap: break-word;
      background: ${sender === 'user' ? '#00f0ff' : 'rgba(255,255,255,0.1)'};
      color: ${sender === 'user' ? '#05050a' : 'white'};
      margin-left: ${sender === 'user' ? 'auto' : '0'};
      border-bottom-right-radius: ${sender === 'user' ? '0' : '5px'};
      border-bottom-left-radius: ${sender === 'user' ? '5px' : '0'};
    `;
    messageDiv.textContent = text;
    messages.appendChild(messageDiv);
    
    // Scroll to bottom
    messages.scrollTop = messages.scrollHeight;
  }
  
  // Send on button click
  sendBtn.addEventListener('click', sendMessage);
  
  // Send on Enter key
  input.addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
      sendMessage();
    }
  });
});
</script>