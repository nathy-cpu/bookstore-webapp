import { generateMockBooks } from "./data.js";

const catalog = document.getElementById("book-list");
const searchInput = document.getElementById("search-input");
const genreSelect = document.getElementById("genre-filter");
const applyFiltersBtn = document.getElementById("search-btn");
const stockCheckbox = document.querySelector('input[name="stock"]');
const cartCount = document.getElementById("cart-count");
const cartLink = document.getElementById("cart-link");

let books = generateMockBooks();
let cart = [];

document.addEventListener("DOMContentLoaded", () => {
  displayBooks(books);
  populateGenreFilter(books);
  setupEventListeners();
});

function displayBooks(bookArray) {
  catalog.innerHTML = "";

  bookArray.forEach((book) => {
    const card = document.createElement("div");
    card.className = `book-card ${book.stock === 0 ? "out-of-stock" : ""}`;
    card.innerHTML = `
      <img src="images/${book.image}" alt="${book.title}">
      <h3>${book.title}</h3>
      <p>${book.author}</p>
      <p>$${book.price}</p>
      <button class="add-to-cart" data-id="${book.id}" ${
      book.stock === 0 ? "disabled" : ""
    }>
        Add to Cart
      </button>
    `;
    catalog.appendChild(card);
  });
}

function populateGenreFilter(bookArray) {
  const genres = [...new Set(bookArray.map((b) => b.category))];
  genres.forEach((genre) => {
    const option = document.createElement("option");
    option.value = genre;
    option.textContent = genre;
    genreSelect.appendChild(option);
  });
}

function setupEventListeners() {
  document.getElementById("menu-btn").addEventListener("click", () => {
    document.getElementById("nav-links").classList.toggle("active");
  });

  applyFiltersBtn.addEventListener("click", () => applyFilters());

  catalog.addEventListener("click", (e) => {
    if (e.target.classList.contains("add-to-cart")) {
      const bookId = parseInt(e.target.dataset.id);
      addToCart(bookId);
      showToast("Added to cart!");
    }
  });

  // Cart modal open
  cartLink.addEventListener("click", (e) => {
    if (e.target.textContent.includes("Cart")) {
      e.preventDefault();
      showCartModal();
    }
  });

  // Cart modal close
  document.querySelector(".close-btn").addEventListener("click", () => {
    document.getElementById("cart-modal").style.display = "none";
  });

  window.addEventListener("click", (e) => {
    const modal = document.getElementById("cart-modal");
    if (e.target === modal) {
      modal.style.display = "none";
    }
  });

  // Checkout button
  document.getElementById("checkout-btn").addEventListener("click", checkout);
}

function applyFilters() {
  const term = searchInput.value.toLowerCase();
  const genre = genreSelect.value;
  const inStock = stockCheckbox.checked;

  const filtered = books.filter((book) => {
    const matchesSearch =
      book.title.toLowerCase().includes(term) ||
      book.author.toLowerCase().includes(term);
    const matchesGenre = !genre || book.category === genre;
    const matchesStock = !inStock || book.stock > 0;
    return matchesSearch && matchesGenre && matchesStock;
  });

  displayBooks(filtered);
}

function addToCart(bookId) {
  const book = books.find((b) => b.id === bookId);
  const existing = cart.find((item) => item.id === bookId);
  if (existing) {
    existing.quantity += 1;
  } else {
    cart.push({ ...book, quantity: 1 });
  }
  updateCartCount();
}

function updateCartCount() {
  const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);
  cartCount.textContent = totalItems;
}

function showCartModal() {
  const modal = document.getElementById("cart-modal");
  const cartItems = document.getElementById("cart-items");
  cartItems.innerHTML = "";

  if (cart.length === 0) {
    cartItems.innerHTML = "<p>Your cart is empty</p>";
  } else {
    cart.forEach((item) => {
      const itemEl = document.createElement("div");
      itemEl.className = "cart-item";
      itemEl.innerHTML = `
        <span>${item.title}</span>
        <div class="quantity-controls">
          <button class="decrease-qty" data-id="${item.id}">-</button>
          <span>${item.quantity}</span>
          <button class="increase-qty" data-id="${item.id}">+</button>
          <span>$${(item.price * item.quantity).toFixed(2)}</span>
          <button class="remove-item" data-id="${item.id}">×</button>
        </div>
      `;
      cartItems.appendChild(itemEl);
    });

    const total = cart.reduce(
      (sum, item) => sum + item.price * item.quantity,
      0
    );
    document.getElementById(
      "cart-total"
    ).textContent = `Total: $${total.toFixed(2)}`;
  }

  modal.style.display = "block";

  // Quantity change
  document.querySelectorAll(".increase-qty").forEach((btn) =>
    btn.addEventListener("click", () => {
      const id = parseInt(btn.getAttribute("data-id"));
      updateCartItem(id, 1);
    })
  );
  document.querySelectorAll(".decrease-qty").forEach((btn) =>
    btn.addEventListener("click", () => {
      const id = parseInt(btn.getAttribute("data-id"));
      updateCartItem(id, -1);
    })
  );
  document.querySelectorAll(".remove-item").forEach((btn) =>
    btn.addEventListener("click", () => {
      const id = parseInt(btn.getAttribute("data-id"));
      removeFromCart(id);
    })
  );
}

function updateCartItem(id, change) {
  const item = cart.find((i) => i.id === id);
  if (item) {
    item.quantity += change;
    if (item.quantity <= 0) {
      cart = cart.filter((i) => i.id !== id);
    }
    updateCartCount();
    showCartModal();
  }
}

function removeFromCart(id) {
  cart = cart.filter((i) => i.id !== id);
  updateCartCount();
  showCartModal();
}

function checkout() {
  if (cart.length === 0) return;
  const total = cart.reduce((sum, i) => sum + i.price * i.quantity, 0);
  alert(`Order placed! Total: $${total.toFixed(2)}`);
  cart = [];
  updateCartCount();
  document.getElementById("cart-modal").style.display = "none";
}

function showToast(message) {
  const toast = document.createElement("div");
  toast.className = "toast";
  toast.textContent = message;
  document.body.appendChild(toast);
  setTimeout(() => toast.remove(), 2000);
}
