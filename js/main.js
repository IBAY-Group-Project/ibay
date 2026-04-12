const tabButtons = document.querySelectorAll('.tab-button');
const tabPanels = document.querySelectorAll('.tab-panel');
console.log(tabButtons);
console.log(tabPanels);

tabButtons.forEach(button => {
    button.addEventListener('click', () => {
        tabButtons.forEach(btn => btn.classList.remove('active'));
        tabPanels.forEach(panel => panel.classList.remove('active'));
        
        button.classList.add('active');

        const tabId = button.getAttribute('data-tab');
        const matchingPanel = document.getElementById(tabId);
        matchingPanel.classList.add('active');
    });
});
