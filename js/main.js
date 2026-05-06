// -- Tab switcher (item page) --
const tabButtons = document.querySelectorAll('.tab-button');
const tabPanels  = document.querySelectorAll('.tab-panel');

if (tabButtons.length && tabPanels.length) {
    tabButtons.forEach(button => {
        button.addEventListener('click', () => {
            tabButtons.forEach(btn   => btn.classList.remove('active'));
            tabPanels.forEach(panel  => panel.classList.remove('active'));
            button.classList.add('active');
            const matchingPanel = document.getElementById(button.getAttribute('data-tab'));
            if (matchingPanel) matchingPanel.classList.add('active');
        });
    });
}

// -- Carousel (homepage) --
const track   = document.getElementById('featuredProducts');
const prevBtn = document.getElementById('prevCategory');
const nextBtn = document.getElementById('nextCategory');

if (track && prevBtn && nextBtn) {
    nextBtn.addEventListener('click', () => track.scrollBy({ left: 220, behavior: 'smooth' }));
    prevBtn.addEventListener('click', () => track.scrollBy({ left: -220, behavior: 'smooth' }));
}

// -- Chatbot --
function toggleChat() {
    const win = document.getElementById('chat-window');
    if (win) win.classList.toggle('open');
}

function askQuestion(btn) {
    const question  = btn.textContent;
    const answer    = btn.getAttribute('data-answer');
    const messages  = document.getElementById('chat-messages');
    const questions = document.getElementById('chat-questions');
    const backDiv   = document.getElementById('chat-back');

    const userMsg = document.createElement('div');
    userMsg.classList.add('chat-msg', 'user');
    userMsg.textContent = question;
    messages.appendChild(userMsg);

    const botMsg = document.createElement('div');
    botMsg.classList.add('chat-msg', 'bot');
    botMsg.textContent = answer;
    messages.appendChild(botMsg);

    messages.scrollTop     = messages.scrollHeight;
    questions.style.display = 'none';
    backDiv.style.display   = 'block';
}

function resetChat() {
    const messages  = document.getElementById('chat-messages');
    const questions = document.getElementById('chat-questions');
    const backDiv   = document.getElementById('chat-back');
    messages.innerHTML      = '<div class="chat-msg bot">Hi! How can I help you today? Choose a question below.</div>';
    questions.style.display = 'flex';
    backDiv.style.display   = 'none';
}

// -- Item page image gallery --
const galleryImages  = window.galleryImages || [];
let currentIndex     = 0;
const mainImage      = document.getElementById('mainProductImage');
const thumbs         = document.querySelectorAll('.item-thumb');

function showImage(index) {
    if (!mainImage || galleryImages.length === 0) return;
    currentIndex  = index;
    mainImage.src = galleryImages[index];
    thumbs.forEach((t, i) => t.classList.toggle('active-thumb', i === index));
}

const prevImageBtn = document.getElementById('prevImage');
const nextImageBtn = document.getElementById('nextImage');

if (prevImageBtn) prevImageBtn.addEventListener('click', () => showImage((currentIndex - 1 + galleryImages.length) % galleryImages.length));
if (nextImageBtn) nextImageBtn.addEventListener('click', () => showImage((currentIndex + 1) % galleryImages.length));

thumbs.forEach(thumb => {
    thumb.addEventListener('click', () => showImage(parseInt(thumb.getAttribute('data-index'))));
});

// -- Account details inline edit --
function startEdit(field) {
    document.getElementById('val-'    + field).style.display = 'none';
    document.getElementById('inp-'    + field).style.display = 'block';
    document.getElementById('save-'   + field).style.display = 'inline-block';
    document.getElementById('cancel-' + field).style.display = 'inline-block';
    document.getElementById('row-'    + field).querySelector('.edit-btn').style.display = 'none';

    const input  = document.getElementById('inp-'    + field);
    const hidden = document.getElementById('hidden-' + field);
    input.addEventListener('input', () => { hidden.value = input.value; });
    input.focus();
}

function cancelEdit(field) {
    const original = document.getElementById('hidden-' + field).value;
    document.getElementById('inp-'    + field).value            = original;
    document.getElementById('val-'    + field).style.display    = 'block';
    document.getElementById('inp-'    + field).style.display    = 'none';
    document.getElementById('save-'   + field).style.display    = 'none';
    document.getElementById('cancel-' + field).style.display    = 'none';
    document.getElementById('row-'    + field).querySelector('.edit-btn').style.display = 'inline-block';
}

function togglePassword() {
    const fields = document.getElementById('passwordFields');
    if (fields) fields.classList.toggle('open');
}

// -- Star rating (checkout) --
document.querySelectorAll('.star-rating').forEach(group => {
    const labels = Array.from(group.querySelectorAll('label'));
    const inputs = Array.from(group.querySelectorAll('input'));

    function highlightStars(upToIndex) {
        labels.forEach((l, i) => { l.style.color = i <= upToIndex ? '#f5a623' : '#ccc'; });
    }

    function resetStars() {
        const checked = inputs.find(inp => inp.checked);
        if (checked) {
            highlightStars(inputs.indexOf(checked));
        } else {
            labels.forEach(l => l.style.color = '#ccc');
        }
    }

    labels.forEach((label, index) => {
        label.addEventListener('mouseover', () => highlightStars(index));
        label.addEventListener('mouseout',  () => resetStars());
        label.addEventListener('click',     () => highlightStars(index));
    });
});