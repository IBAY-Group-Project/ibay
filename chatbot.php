<!-- -- Chatbot -- -->
<div id="chat-bubble" onclick="toggleChat()">
    <i class="fa-solid fa-message"></i>
</div>

<div id="chat-window">
    <div id="chat-header">
        <span>iBay Assistant</span>
        <button onclick="toggleChat()" id="chat-close">&#10005;</button>
    </div>
    <div id="chat-messages">
        <div class="chat-msg bot">Hi! I'm the iBay Assistant. Ask me anything about listings or how the site works.</div>
    </div>
    <div id="chat-input-row">
        <input type="text" id="chat-input" placeholder="Ask a question..." onkeydown="if(event.key==='Enter') sendChat()">
        <button onclick="sendChat()" id="chat-send"><i class="fa-solid fa-paper-plane"></i></button>
    </div>
</div>
