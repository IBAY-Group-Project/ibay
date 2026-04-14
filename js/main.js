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