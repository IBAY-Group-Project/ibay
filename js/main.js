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

const categoryLinks = document.querySelectorAll(".category-nav a");
const featuredProducts = document.getElementById("featuredProducts");
const featuredTitle = document.getElementById("featuredTitle");
const prevCategoryBtn = document.getElementById("prevCategory");
const nextCategoryBtn = document.getElementById("nextCategory");

let categoryNames = Object.keys(categoryData);
let currentCategoryIndex = 0;
let autoRotateInterval = null;

function renderCategory(categoryName) {
    if (!featuredProducts || !featuredTitle) return;

    featuredTitle.textContent = `Best Selling in ${categoryName}`;
    featuredProducts.innerHTML = "";

    categoryData[categoryName].forEach(product => {
        const card = document.createElement("div");
        card.className = "product-card";
        card.innerHTML = `
            <div class="product-card-image">${product.icon}</div>
            <h3>${product.title}</h3>
            <p>${product.price}</p>
        `;
        featuredProducts.appendChild(card);
    });

    categoryLinks.forEach(link => {
        link.classList.remove("active");
        if (link.dataset.category === categoryName) {
            link.classList.add("active");
        }
    });
}

function showCategoryByIndex(index) {
    currentCategoryIndex = index;
    renderCategory(categoryNames[currentCategoryIndex]);
}

if (categoryLinks.length) {
    categoryLinks.forEach((link, index) => {
        link.addEventListener("click", e => {
            e.preventDefault();
            showCategoryByIndex(index);
            restartAutoRotate();
        });
    });
}

if (prevCategoryBtn) {
    prevCategoryBtn.addEventListener("click", () => {
        currentCategoryIndex = (currentCategoryIndex - 1 + categoryNames.length) % categoryNames.length;
        showCategoryByIndex(currentCategoryIndex);
        restartAutoRotate();
    });
}

if (nextCategoryBtn) {
    nextCategoryBtn.addEventListener("click", () => {
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

/* SELL PAGE PREVIEW */

const titleInput = document.getElementById("title");
const categoryInput = document.getElementById("category");
const priceInput = document.getElementById("price");
const descriptionInput = document.getElementById("description");

const previewTitle = document.getElementById("previewTitle");
const previewCategory = document.getElementById("previewCategory");
const previewPrice = document.getElementById("previewPrice");
const charCount = document.getElementById("charCount");

function updateSellerPreview() {
    if (!previewTitle || !previewCategory || !previewPrice || !charCount) return;

    previewTitle.textContent =
        titleInput && titleInput.value.trim() ? titleInput.value.trim() : "Your item title";

    previewCategory.textContent =
        categoryInput && categoryInput.value ? categoryInput.value : "Technology";

    previewPrice.textContent =
        "Â£" + (priceInput && priceInput.value ? Number(priceInput.value).toFixed(2) : "0.00");

    charCount.textContent = `${descriptionInput ? descriptionInput.value.length : 0} / 500`;
}

if (titleInput || categoryInput || priceInput || descriptionInput) {
    [titleInput, categoryInput, priceInput, descriptionInput].forEach(input => {
        if (input) {
            input.addEventListener("input", updateSellerPreview);
        }
    });

    updateSellerPreview();
}

/* ITEM PAGE GALLERY */

const productImages = [
    "images/placeholder-product.jpg",
    "images/placeholder-product-2.jpg"
];

const mainProductImage = document.getElementById("mainProductImage");
const prevImageBtn = document.getElementById("prevImage");
const nextImageBtn = document.getElementById("nextImage");
const thumbnails = document.querySelectorAll(".item-thumb");

let currentImageIndex = 0;

function renderItemGallery() {
    if (!mainProductImage || !thumbnails.length) return;

    mainProductImage.src = productImages[currentImageIndex];

    thumbnails.forEach((thumb, index) => {
        thumb.src = productImages[index];
        thumb.classList.toggle("active-thumb", index === currentImageIndex);
    });
}

if (mainProductImage && prevImageBtn && nextImageBtn && thumbnails.length) {
    prevImageBtn.addEventListener("click", () => {
        currentImageIndex = (currentImageIndex - 1 + productImages.length) % productImages.length;
        renderItemGallery();
    });

    nextImageBtn.addEventListener("click", () => {
        currentImageIndex = (currentImageIndex + 1) % productImages.length;
        renderItemGallery();
    });

    thumbnails.forEach((thumb, index) => {
        thumb.addEventListener("click", () => {
            currentImageIndex = index;
            renderItemGallery();
        });
    });

    renderItemGallery();
}

/* SEARCH PAGE URL PARAMS + FILTERING */

document.addEventListener("DOMContentLoaded", () => {
    const params = new URLSearchParams(window.location.search);

    const query = (params.get("q") || "").trim().toLowerCase();
    const category = params.get("category") || "";
    const minPrice = params.get("minPrice") || "";
    const maxPrice = params.get("maxPrice") || "";
    const postage = params.get("postage") || "";
    const postcode = (params.get("postcode") || "").trim().toLowerCase();

    const headerSearchInputs = document.querySelectorAll('.search-form input[name="q"]');
    const pageSearchInput = document.querySelector('.search-page-form input[name="q"]');
    const keywordInput = document.getElementById("searchKeyword");
    const categorySearchInput = document.getElementById("searchCategory");
    const minPriceInput = document.getElementById("minPrice");
    const maxPriceInput = document.getElementById("maxPrice");
    const postageInput = document.getElementById("postage");
    const postcodeInput = document.getElementById("postcodeArea");
    const sortSelect = document.getElementById("sortBy");
    const resultsGrid = document.getElementById("searchResultsGrid");
    const resultCards = resultsGrid ? Array.from(resultsGrid.querySelectorAll(".search-item-card")) : [];
    const resultsCount = document.getElementById("resultsCount");
    const clearFiltersButton = document.getElementById("clearFiltersButton");

    headerSearchInputs.forEach(input => {
        input.value = params.get("q") || "";
    });

    if (pageSearchInput) pageSearchInput.value = params.get("q") || "";
    if (keywordInput) keywordInput.value = params.get("q") || "";
    if (categorySearchInput) categorySearchInput.value = category;
    if (minPriceInput) minPriceInput.value = minPrice;
    if (maxPriceInput) maxPriceInput.value = maxPrice;
    if (postageInput) postageInput.value = postage;
    if (postcodeInput) postcodeInput.value = params.get("postcode") || "";

    function applySearchFilters() {
        if (!resultCards.length || !resultsGrid) return;

        let visibleCards = resultCards.filter(card => {
            const title = (card.dataset.title || "").toLowerCase();
            const itemCategory = card.dataset.category || "";
            const itemPrice = parseFloat(card.dataset.price || "0");
            const itemPostage = card.dataset.postage || "";
            const itemPostcode = (card.dataset.postcode || "").toLowerCase();

            const matchesQuery = !query || title.includes(query);
            const matchesCategory = !category || itemCategory === category;
            const matchesMin = !minPrice || itemPrice >= parseFloat(minPrice);
            const matchesMax = !maxPrice || itemPrice <= parseFloat(maxPrice);
            const matchesPostage =
                !postage ||
                (postage === "Paid postage"
                    ? itemPostage !== "Free postage" && itemPostage !== "Collection only"
                    : itemPostage === postage);
            const matchesPostcode = !postcode || itemPostcode.includes(postcode);

            return (
                matchesQuery &&
                matchesCategory &&
                matchesMin &&
                matchesMax &&
                matchesPostage &&
                matchesPostcode
            );
        });

        const sortValue = sortSelect ? sortSelect.value : "low-high";

        visibleCards.sort((a, b) => {
            const priceA = parseFloat(a.dataset.price || "0");
            const priceB = parseFloat(b.dataset.price || "0");
            const titleA = a.dataset.title || "";
            const titleB = b.dataset.title || "";

            if (sortValue === "high-low") return priceB - priceA;
            if (sortValue === "title") return titleA.localeCompare(titleB);
            return priceA - priceB;
        });

        resultCards.forEach(card => {
            card.style.display = "none";
        });

        visibleCards.forEach(card => {
            card.style.display = "flex";
            resultsGrid.appendChild(card);
        });

        if (resultsCount) {
            resultsCount.textContent = `Showing ${visibleCards.length} result${visibleCards.length === 1 ? "" : "s"}`;
        }
    }

    if (sortSelect) {
        sortSelect.addEventListener("change", applySearchFilters);
    }

    if (clearFiltersButton) {
        clearFiltersButton.addEventListener("click", e => {
            e.preventDefault();
            window.location.href = "search.html";
        });
    }

    applySearchFilters();
});

console.log('main.js loaded');

document.addEventListener("DOMContentLoaded", () => {
    const toggleEmail = document.getElementById("toggleLoginType");
    const toggleUsername = document.getElementById("toggleLoginType2");
    const emailInput = document.getElementById("email");
    const usernameInput = document.getElementById("username");
    const loginModeField = document.getElementById("login_mode");

    if (!toggleEmail || !toggleUsername || !emailInput || !usernameInput) return;

    usernameInput.disabled = true;

    toggleEmail.addEventListener("click", () => {
        emailInput.parentElement.style.display = "none";
        usernameInput.parentElement.style.display = "block";
        emailInput.disabled = true;
        usernameInput.disabled = false;
        loginModeField.value = "username";
        usernameInput.focus();
    });

    toggleUsername.addEventListener("click", () => {
        usernameInput.parentElement.style.display = "none";
        emailInput.parentElement.style.display = "block";
        usernameInput.disabled = true;
        emailInput.disabled = false;
        loginModeField.value = "email";
        emailInput.focus();
    });
});

