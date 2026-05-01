// -- Tab switcher (for item page) --
const tabButtons = document.querySelectorAll('.tab-button');
const tabPanels = document.querySelectorAll('.tab-panel');

if (tabButtons.length && tabPanels.length) {
    tabButtons.forEach(button => {
        button.addEventListener('click', () => {
            tabButtons.forEach(btn => btn.classList.remove('active'));
            tabPanels.forEach(panel => panel.classList.remove('active'));
            button.classList.add('active');
            const tabId = button.getAttribute('data-tab');
            const matchingPanel = document.getElementById(tabId);
            if (matchingPanel) {
                matchingPanel.classList.add('active');
            }
        });
    });
}

// -- Carousel (for homepage) --
const track = document.getElementById('featuredProducts');
const prevBtn = document.getElementById('prevCategory');
const nextBtn = document.getElementById('nextCategory');

if (track && prevBtn && nextBtn) {
    nextBtn.addEventListener('click', () => {
        track.scrollBy({ left: 220, behavior: 'smooth' });
    });

    prevBtn.addEventListener('click', () => {
        track.scrollBy({ left: -220, behavior: 'smooth' });
    });
}