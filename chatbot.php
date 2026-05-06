<!-- -- Chatbot -- -->
<div id="chat-bubble" onclick="toggleChat()">
    <i class="fa-solid fa-message"></i>
</div>

<div id="chat-window">
    <div id="chat-header">
        <span>iBay Help</span>
        <button onclick="toggleChat()" id="chat-close">&#10005;</button>
    </div>
    <div id="chat-messages">
        <div class="chat-msg bot">Hi! How can I help you today? Choose a question below.</div>
    </div>
    <div id="chat-questions">
        <button class="chat-q" onclick="askQuestion(this)" data-answer="To create an account, click the Signup button in the top navigation bar. Fill in your first name, surname, email address, and a strong password. Once submitted you will be redirected to the login page.">How do I create an account?</button>
        <button class="chat-q" onclick="askQuestion(this)" data-answer="To list an item, click the Sell button in the top navigation bar. You must be logged in to sell. Fill in the title, category, condition, price, postage and description, then upload up to 3 images and click Publish Listing.">How do I list an item for sale?</button>
        <button class="chat-q" onclick="askQuestion(this)" data-answer="Use the search bar at the top of the page and type what you are looking for, then click Search. You can also browse by clicking a category in the navigation bar or the Shop by Category section.">How do I search for items?</button>
        <button class="chat-q" onclick="askQuestion(this)" data-answer="To add an item to your basket you must be logged in. Navigate to the item page by clicking View on any listing, then click the Add to Basket button. You can view your basket by clicking the basket icon in the top right.">How do I add an item to my basket?</button>
        <button class="chat-q" onclick="askQuestion(this)" data-answer="To contact a seller, navigate to the item page by clicking View on any listing. The sellers name is displayed on the item page and you can send them a message from there.">How do I contact a seller?</button>
        <button class="chat-q" onclick="askQuestion(this)" data-answer="To return an item, go to your Account page and find the order in your purchase history. Click on the order and select Request Return. The seller will be notified and will arrange the return with you.">How do I return an item?</button>
        <button class="chat-q" onclick="askQuestion(this)" data-answer="To change your password, click Account in the top navigation bar and go to Account Details. From there you can update your password. You will need to enter your current password to confirm the change.">How do I change my password?</button>
    </div>
    <div id="chat-back" style="display:none;">
        <button class="secondary-button" onclick="resetChat()">&#8592; Back to questions</button>
    </div>
</div>