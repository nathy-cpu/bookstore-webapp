import { generateMockBooks } from "./data.js";

const catalog = document.getElementById("book-list");
const searchInput = document.getElementById("search-input");
const genreSelect = document.getElementById("genre-filter");
const applyFiltersBtn = document.getElementById("search-btn");
const stockCheckbox = document.querySelector('input[name="stock"]');
const cartCount = document.getElementById("cart-count");

let books = generateMockBooks();

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
  // Mobile menu toggle
  document.getElementById("menu-btn").addEventListener("click", () => {
    document.getElementById("nav-links").classList.toggle("active");
  });

  // Search & filter
  applyFiltersBtn.addEventListener("click", () => applyFilters());

  // Add to cart
  catalog.addEventListener("click", (e) => {
    if (e.target.classList.contains("add-to-cart")) {
      const bookId = parseInt(e.target.dataset.id);
      addToCart(bookId);
      showToast("Added to cart!");
    }
  });
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
  cartCount.textContent = parseInt(cartCount.textContent) + 1;
}

function showToast(message) {
  const toast = document.createElement("div");
  toast.className = "toast";
  toast.textContent = message;
  document.body.appendChild(toast);
  setTimeout(() => toast.remove(), 2000);
}
