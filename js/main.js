const categoryData = {
    "Technology": [
        { icon: "ðŸŽ§", title: "Wireless Headphones", price: "Â£45.00" },
        { icon: "âŒ¨ï¸", title: "Mechanical Keyboard", price: "Â£70.00" },
        { icon: "ðŸ“±", title: "Smartphone", price: "Â£220.00" },
        { icon: "ðŸ–±ï¸", title: "Gaming Mouse", price: "Â£25.00" },
        { icon: "ðŸ’»", title: "Laptop Stand", price: "Â£18.00" }
    ],
    "Clothing": [
        { icon: "ðŸ‘•", title: "Vintage T-Shirt", price: "Â£12.00" },
        { icon: "ðŸ‘Ÿ", title: "Running Trainers", price: "Â£35.00" },
        { icon: "ðŸ§¥", title: "Winter Jacket", price: "Â£48.00" },
        { icon: "ðŸ§¢", title: "Cap", price: "Â£8.00" },
        { icon: "ðŸ‘–", title: "Denim Jeans", price: "Â£20.00" }
    ],
    "Trading Cards": [
        { icon: "ðŸƒ", title: "Rare Card Bundle", price: "Â£55.00" },
        { icon: "ðŸ“¦", title: "Graded Card", price: "Â£110.00" },
        { icon: "ðŸŽ´", title: "Collector Set", price: "Â£80.00" },
        { icon: "ðŸª™", title: "Limited Edition Card", price: "Â£40.00" },
        { icon: "ðŸ“‡", title: "Starter Deck", price: "Â£18.00" }
    ],
    "Gardening": [
        { icon: "ðŸª´", title: "Plant Pot Set", price: "Â£14.00" },
        { icon: "ðŸŒ±", title: "Seed Pack Bundle", price: "Â£9.00" },
        { icon: "ðŸš¿", title: "Garden Hose Nozzle", price: "Â£11.00" },
        { icon: "âœ‚ï¸", title: "Pruning Shears", price: "Â£17.00" },
        { icon: "ðŸ§¤", title: "Gardening Gloves", price: "Â£7.00" }
    ],
    "Home": [
        { icon: "ðŸ›‹ï¸", title: "Cushion Set", price: "Â£16.00" },
        { icon: "ðŸ•¯ï¸", title: "Decor Candle Pack", price: "Â£10.00" },
        { icon: "ðŸªž", title: "Wall Mirror", price: "Â£24.00" },
        { icon: "ðŸ½ï¸", title: "Dining Plate Set", price: "Â£28.00" },
        { icon: "ðŸ§º", title: "Laundry Basket", price: "Â£13.00" }
    ],
    "Collectables": [
        { icon: "ðŸŽ", title: "Vintage Figure", price: "Â£60.00" },
        { icon: "ðŸª™", title: "Old Coin", price: "Â£95.00" },
        { icon: "ðŸŽ®", title: "Retro Game", price: "Â£42.00" },
        { icon: "ðŸ“¼", title: "Classic VHS", price: "Â£15.00" },
        { icon: "ðŸ§¸", title: "Collector Toy", price: "Â£34.00" }
    ],
    "Sports": [
        { icon: "âš½", title: "Football", price: "Â£14.00" },
        { icon: "ðŸ€", title: "Basketball", price: "Â£18.00" },
        { icon: "ðŸŽ¾", title: "Tennis Racket", price: "Â£29.00" },
        { icon: "ðŸ‹ï¸", title: "Dumbbell Set", price: "Â£52.00" },
        { icon: "ðŸš´", title: "Cycling Helmet", price: "Â£26.00" }
    ],
    "Books": [
        { icon: "ðŸ“˜", title: "Programming Book", price: "Â£12.00" },
        { icon: "ðŸ“•", title: "Novel Collection", price: "Â£15.00" },
        { icon: "ðŸ“—", title: "Maths Textbook", price: "Â£20.00" },
        { icon: "ðŸ“™", title: "History Book", price: "Â£11.00" },
        { icon: "ðŸ“š", title: "Book Bundle", price: "Â£25.00" }
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

const toggleBtn = document.getElementById('toggleLoginType');
const toggleBtn2 = document.getElementById('toggleLoginType2');

if (toggleBtn) {
    toggleBtn.addEventListener('click', function(e) {
        e.preventDefault();
        const emailGroup = document.getElementById('email').closest('.form-group');
        const usernameGroup = document.getElementById('usernameGroup');
        emailGroup.style.display = 'none';
        usernameGroup.style.display = 'block';

        document.getElementById('email').removeAttribute('required');
        document.getElementById('username').setAttribute('required','required');
    });
}

if (toggleBtn2) {
    toggleBtn2.addEventListener('click', function(e) {
        e.preventDefault();
        const emailGroup = document.getElementById('email').closest('.form-group');
        const usernameGroup = document.getElementById('usernameGroup');
        emailGroup.style.display = 'block';
        usernameGroup.style.display = 'none';

        document.getElementById('username').removeAttribute('required');
        document.getElementById('email').setAttribute('required','required');
    });
}

