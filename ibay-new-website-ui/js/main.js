const categoryData = {
    "Technology": [
        { icon: "🎧", title: "Wireless Headphones", price: "£45.00" },
        { icon: "⌨️", title: "Mechanical Keyboard", price: "£70.00" },
        { icon: "📱", title: "Smartphone", price: "£220.00" },
        { icon: "🖱️", title: "Gaming Mouse", price: "£25.00" },
        { icon: "💻", title: "Laptop Stand", price: "£18.00" }
    ],
    "Clothing": [
        { icon: "👕", title: "Vintage T-Shirt", price: "£12.00" },
        { icon: "👟", title: "Running Trainers", price: "£35.00" },
        { icon: "🧥", title: "Winter Jacket", price: "£48.00" },
        { icon: "🧢", title: "Cap", price: "£8.00" },
        { icon: "👖", title: "Denim Jeans", price: "£20.00" }
    ],
    "Trading Cards": [
        { icon: "🃏", title: "Rare Card Bundle", price: "£55.00" },
        { icon: "📦", title: "Graded Card", price: "£110.00" },
        { icon: "🎴", title: "Collector Set", price: "£80.00" },
        { icon: "🪙", title: "Limited Edition Card", price: "£40.00" },
        { icon: "📇", title: "Starter Deck", price: "£18.00" }
    ],
    "Gardening": [
        { icon: "🪴", title: "Plant Pot Set", price: "£14.00" },
        { icon: "🌱", title: "Seed Pack Bundle", price: "£9.00" },
        { icon: "🚿", title: "Garden Hose Nozzle", price: "£11.00" },
        { icon: "✂️", title: "Pruning Shears", price: "£17.00" },
        { icon: "🧤", title: "Gardening Gloves", price: "£7.00" }
    ],
    "Home": [
        { icon: "🛋️", title: "Cushion Set", price: "£16.00" },
        { icon: "🕯️", title: "Decor Candle Pack", price: "£10.00" },
        { icon: "🪞", title: "Wall Mirror", price: "£24.00" },
        { icon: "🍽️", title: "Dining Plate Set", price: "£28.00" },
        { icon: "🧺", title: "Laundry Basket", price: "£13.00" }
    ],
    "Collectables": [
        { icon: "🎁", title: "Vintage Figure", price: "£60.00" },
        { icon: "🪙", title: "Old Coin", price: "£95.00" },
        { icon: "🎮", title: "Retro Game", price: "£42.00" },
        { icon: "📼", title: "Classic VHS", price: "£15.00" },
        { icon: "🧸", title: "Collector Toy", price: "£34.00" }
    ],
    "Sports": [
        { icon: "⚽", title: "Football", price: "£14.00" },
        { icon: "🏀", title: "Basketball", price: "£18.00" },
        { icon: "🎾", title: "Tennis Racket", price: "£29.00" },
        { icon: "🏋️", title: "Dumbbell Set", price: "£52.00" },
        { icon: "🚴", title: "Cycling Helmet", price: "£26.00" }
    ],
    "Books": [
        { icon: "📘", title: "Programming Book", price: "£12.00" },
        { icon: "📕", title: "Novel Collection", price: "£15.00" },
        { icon: "📗", title: "Maths Textbook", price: "£20.00" },
        { icon: "📙", title: "History Book", price: "£11.00" },
        { icon: "📚", title: "Book Bundle", price: "£25.00" }
    ]
};

const categoryLinks = document.querySelectorAll('.category-nav a');
const featuredProducts = document.getElementById('featuredProducts');
const featuredTitle = document.getElementById('featuredTitle');
const prevCategoryBtn = document.getElementById('prevCategory');
const nextCategoryBtn = document.getElementById('nextCategory');

let categoryNames = Object.keys(categoryData);
let currentCategoryIndex = 0;
let autoRotateInterval = null;

function renderCategory(categoryName) {
    if (!featuredProducts || !featuredTitle) return;

    featuredTitle.textContent = `Best Selling in ${categoryName}`;
    featuredProducts.innerHTML = '';

    categoryData[categoryName].forEach(product => {
        const card = document.createElement('div');
        card.className = 'product-card';
        card.innerHTML = `
            <div class="product-card-image">${product.icon}</div>
            <h3>${product.title}</h3>
            <p>${product.price}</p>
        `;
        featuredProducts.appendChild(card);
    });

    categoryLinks.forEach(link => {
        link.classList.remove('active');
        if (link.dataset.category === categoryName) {
            link.classList.add('active');
        }
    });
}

function showCategoryByIndex(index) {
    currentCategoryIndex = index;
    renderCategory(categoryNames[currentCategoryIndex]);
}

if (categoryLinks.length) {
    categoryLinks.forEach((link, index) => {
        link.addEventListener('click', (e) => {
            e.preventDefault();
            showCategoryByIndex(index);
            restartAutoRotate();
        });
    });
}

if (prevCategoryBtn) {
    prevCategoryBtn.addEventListener('click', () => {
        currentCategoryIndex = (currentCategoryIndex - 1 + categoryNames.length) % categoryNames.length;
        showCategoryByIndex(currentCategoryIndex);
        restartAutoRotate();
    });
}

if (nextCategoryBtn) {
    nextCategoryBtn.addEventListener('click', () => {
        currentCategoryIndex = (currentCategoryIndex + 1) % categoryNames.length;
        showCategoryByIndex(currentCategoryIndex);
        restartAutoRotate();
    });
}

function startAutoRotate() {
    if (!featuredProducts) return;

    autoRotateInterval = setInterval(() => {
        currentCategoryIndex = (currentCategoryIndex + 1) % categoryNames.length;
        showCategoryByIndex(currentCategoryIndex);
    }, 5000);
}

function restartAutoRotate() {
    clearInterval(autoRotateInterval);
    startAutoRotate();
}

if (featuredProducts) {
    renderCategory(categoryNames[currentCategoryIndex]);
    startAutoRotate();
}

const thumbnails = document.querySelectorAll('.thumbnail');
const mainImage = document.getElementById('productMainImage');

if (thumbnails.length && mainImage) {
    thumbnails.forEach(thumbnail => {
        thumbnail.addEventListener('click', () => {
            thumbnails.forEach(t => t.classList.remove('active'));
            thumbnail.classList.add('active');
            mainImage.src = thumbnail.src;
        });
    });
}