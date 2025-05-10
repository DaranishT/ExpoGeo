document.addEventListener("DOMContentLoaded", function() {
  // Elements
  const elements = {
    orb: document.getElementById('chatbot-orb'),
    window: document.getElementById('chatbot-window'),
    input: document.getElementById('chatbot-input'),
    sendBtn: document.getElementById('chatbot-send'),
    messages: document.getElementById('chatbot-messages'),
    minimizeBtn: document.getElementById('chatbot-minimize'),
    closeBtn: document.getElementById('chatbot-close')
  };

  // Response Database
  const responseDB = {
    greetings: {
      triggers: ["hi", "hello", "hey"],
      responses: [
        "Hello! Welcome to ExpoGeo. How can I help?",
        "Hi there! What exhibition interests you?",
        "Welcome! Ready to explore amazing exhibits?"
      ]
    },
    exhibitions: {
      triggers: ["exhibit", "show", "display", "see", "view", "what's on"],
      responses: [
        "Current exhibitions:\n- Digital Art Frontier\n- Ancient Civilizations\n- Future Tech",
        "Featured now:\n- Modern Sculptures\n- Photography Through Time"
      ]
    },
    tickets: {
      triggers: ["ticket", "price", "cost", "admission", "how much"],
      responses: [
        "Ticket options:\n- Adult: $15\n- Student: $10\n- Group: $12pp",
        "Admission:\n- Day Pass: $18\n- Evening: $12\n- Season: $60"
      ]
    },
    hours: {
      triggers: ["open", "close", "hour", "time", "when"],
      responses: [
        "Hours:\nMon-Fri: 9am-7pm\nWeekends: 10am-9pm",
        "Open:\nWeekdays: 9-7\nWeekends: 10-9\nHolidays: 10-5"
      ]
    },
    default: [
      "I'm still learning about ExpoGeo. Could you rephrase?",
      "Interesting question! Let me check that for you."
    ]
  };

  // Message Functions
  function createMessage(text, isUser) {
    const msg = document.createElement('div');
    msg.className = `chatbot-message ${isUser ? 'user' : 'bot'}`;
    msg.innerHTML = `<div class="message-content">${text}</div>`;
    return msg;
  }

  function addMessage(text, isUser) {
    elements.messages.appendChild(createMessage(text, isUser));
    elements.messages.scrollTop = elements.messages.scrollHeight;
  }

  // Response Logic
  function getResponse(input) {
    input = input.toLowerCase();
    
    for (const category in responseDB) {
      if (category === 'default') continue;
      
      if (responseDB[category].triggers.some(trigger => input.includes(trigger))) {
        const responses = responseDB[category].responses;
        return responses[Math.floor(Math.random() * responses.length)];
      }
    }
    
    return responseDB.default[Math.floor(Math.random() * responseDB.default.length)];
  }

  // Event Handlers
  function handleSend() {
    const text = elements.input.value.trim();
    if (!text) return;
    
    addMessage(text, true);
    elements.input.value = '';
    
    setTimeout(() => {
      addMessage(getResponse(text), false);
    }, 800);
  }

  // Initialize
  elements.orb.addEventListener('click', (e) => {
    e.stopPropagation();
    elements.window.classList.toggle('active');
  });

  elements.sendBtn.addEventListener('click', handleSend);
  elements.input.addEventListener('keypress', (e) => {
    if (e.key === 'Enter') handleSend();
  });

  elements.minimizeBtn.addEventListener('click', () => {
    elements.window.classList.toggle('minimized');
  });

  elements.closeBtn.addEventListener('click', () => {
    elements.window.classList.remove('active');
  });

  document.addEventListener('click', () => {
    elements.window.classList.remove('active');
  });

  elements.window.addEventListener('click', (e) => {
    e.stopPropagation();
  });

  // Initial greeting
  setTimeout(() => {
    addMessage(responseDB.greetings.responses[
      Math.floor(Math.random() * responseDB.greetings.responses.length)
    ], false);
  }, 1000);
});