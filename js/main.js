/* HOMEPAGE CAROUSEL SCROLL */
const carouselTrack = document.getElementById("featuredProducts");
const prevCategoryBtn = document.getElementById("prevCategory");
const nextCategoryBtn = document.getElementById("nextCategory");

if (carouselTrack && prevCategoryBtn && nextCategoryBtn) {
    prevCategoryBtn.addEventListener("click", () => carouselTrack.scrollBy({ left: -carouselTrack.clientWidth, behavior: "smooth" }));
    nextCategoryBtn.addEventListener("click", () => carouselTrack.scrollBy({ left:  carouselTrack.clientWidth, behavior: "smooth" }));
}

/* HOMEPAGE CATEGORY FILTER */
const featuredTitle  = document.getElementById("featuredTitle");
const carouselFooter = document.getElementById("carouselFooter");
const viewMoreBtn    = document.getElementById("viewMoreBtn");
const categoryNavLinks = document.querySelectorAll(".category-nav a[data-category]");

categoryNavLinks.forEach(link => {
    link.addEventListener("click", e => {
        e.preventDefault();
        const category = link.dataset.category;

        categoryNavLinks.forEach(l => l.classList.remove("active"));
        link.classList.add("active");

        if (featuredTitle) featuredTitle.textContent = category ;
        if (viewMoreBtn)    viewMoreBtn.href = "search.php?category=" + encodeURIComponent(category);
        if (carouselFooter) carouselFooter.style.display = "";

        if (!carouselTrack) return;
        carouselTrack.innerHTML = "<p>Loading...</p>";

        fetch("php/category_items.php?category=" + encodeURIComponent(category))
            .then(r => r.json())
            .then(items => {
                carouselTrack.innerHTML = "";
                if (!items.length) {
                    carouselTrack.innerHTML = '<p>No listings in this category yet. <a href="sell.php">Be the first to sell!</a></p>';
                    return;
                }
                items.forEach(item => {
                    const card = document.createElement("div");
                    card.className = "product-card";
                    card.innerHTML = `
                        <img src="${item.image}" alt="${item.title}">
                        <div class="product-card-details">
                            <h3>${item.title}</h3>
                            <p class="category">${item.category}</p>
                            <p class="condition">${item.condition}</p>
                            <p class="price">£${item.price}</p>
                            <a href="item.php?id=${item.itemId}" class="primary-button">View</a>
                        </div>
                    `;
                    carouselTrack.appendChild(card);
                });
            })
            .catch(() => {
                carouselTrack.innerHTML = "<p>Could not load listings.</p>";
            });
    });
});

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
        "£" + (priceInput && priceInput.value ? Number(priceInput.value).toFixed(2) : "0.00");

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

// -- Star rating --
document.querySelectorAll('.star-rating').forEach(container => {
    const labels = Array.from(container.querySelectorAll('label'));
    const inputs = Array.from(container.querySelectorAll('input'));
    function highlight(upTo) {
        labels.forEach((l, i) => { l.style.color = i <= upTo ? '#f5a623' : '#ccc'; });
    }
    labels.forEach((label, index) => {
        label.addEventListener('mouseover', () => highlight(index));
        label.addEventListener('mouseout',  () => highlight(inputs.findIndex(inp => inp.checked)));
    });
    inputs.forEach((input, index) => input.addEventListener('change', () => highlight(index)));
    highlight(inputs.findIndex(inp => inp.checked));
});

// -- Chatbot --
function toggleChat() {
    const win = document.getElementById('chat-window');
    if (win) {
        win.classList.toggle('open');
        if (win.classList.contains('open')) document.getElementById('chat-input').focus();
    }
}

async function sendChat() {
    const input    = document.getElementById('chat-input');
    const messages = document.getElementById('chat-messages');
    const text     = input.value.trim();
    if (!text) return;

    const userMsg = document.createElement('div');
    userMsg.classList.add('chat-msg', 'user');
    userMsg.textContent = text;
    messages.appendChild(userMsg);
    input.value = '';
    messages.scrollTop = messages.scrollHeight;

    const typing = document.createElement('div');
    typing.classList.add('chat-msg', 'bot');
    typing.textContent = '...';
    messages.appendChild(typing);
    messages.scrollTop = messages.scrollHeight;

    try {
        const res  = await fetch('php/chat.php', {
            method:  'POST',
            headers: { 'Content-Type': 'application/json' },
            body:    JSON.stringify({ message: text })
        });
        const data = await res.json();
        typing.textContent = data.reply;
    } catch {
        typing.textContent = 'Sorry, something went wrong. Please try again.';
    }

    messages.scrollTop = messages.scrollHeight;
}