document.addEventListener('DOMContentLoaded', function() {
    const chatLog = document.getElementById('chat-log');
    const messageInput = document.getElementById('message-input');
    const sendButton = document.getElementById('send-button');
  
    sendButton.addEventListener('click', function() {
      var message = messageInput.value;

      if (message.trim() !== '') {
        appendMessage('You', message);
        sendMessage(message);
        messageInput.value = '';
      }
    });
  
    function appendMessage(sender, message) {
      var messageElement = document.createElement('div');
      messageElement.innerHTML = '<strong>' + sender + ':</strong> ' + message;
      chatLog.appendChild(messageElement);
    }
  
    function sendMessage(message) {
      const formData = new FormData();
      formData.append('message', message);

      fetch('/chat', {
        method: 'POST',
        body: formData,
      })
        .then(response => response.json())
        .then(data => {
          appendMessage('Chatbot', data.message);
        })
        .catch(error => {
          console.error('Error:', error);
        });
    }
  });