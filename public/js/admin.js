document.addEventListener("DOMContentLoaded", () => {
  window.adminBooks = generateMockBooks(); // Store books in a global variable
  displayAdminBooks(window.adminBooks);
  setupAdminEventListeners();
});

function generateMockBooks(count = 10) {
  const categories = ["Fantasy", "Sci-Fi", "Mystery", "Romance"];
  const books = [];

  for (let i = 1; i <= count; i++) {
    books.push({
      id: Date.now() + i,
      title: `Book Title ${i}`,
      author: `Author ${i}`,
      price: (Math.random() * 30 + 5).toFixed(2),
      stock: Math.floor(Math.random() * 50 + 1),
      category: categories[Math.floor(Math.random() * categories.length)],
      image: `https://picsum.photos/seed/book${i}/200/300`,
    });
  }

  return books;
}

function displayAdminBooks(books) {
  const bookList = document.getElementById("admin-book-list");
  bookList.innerHTML = "";

  if (books.length === 0) {
    bookList.innerHTML = "<p>No books available.</p>";
    return;
  }

  books.forEach((book) => {
    const bookCard = document.createElement("div");
    bookCard.className = "admin-book-card";
    bookCard.innerHTML = `
            <div class="book-info">
                <h3>${book.title}</h3>
                <p>${book.author} | ${book.category} | Stock: ${book.stock} | $${book.price}</p>
            </div>
            <div class="admin-book-actions">
                <button class="edit-btn" data-id="${book.id}">Edit</button>
                <button class="delete-btn" data-id="${book.id}">Delete</button>
            </div>
        `;
    bookList.appendChild(bookCard);
  });
}

function setupAdminEventListeners() {
  document.getElementById("add-book-btn").addEventListener("click", () => {
    openBookForm();
  });

  document.getElementById("book-form").addEventListener("submit", (e) => {
    e.preventDefault();
    saveBook();
  });

  document
    .querySelector("#book-form-modal .close-btn")
    .addEventListener("click", () => {
      document.getElementById("book-form-modal").style.display = "none";
    });

  document.getElementById("admin-book-list").addEventListener("click", (e) => {
    if (e.target.classList.contains("edit-btn")) {
      const bookId = parseInt(e.target.getAttribute("data-id"));
      const book = window.adminBooks.find((b) => b.id === bookId);
      if (book) openBookForm(book);
    } else if (e.target.classList.contains("delete-btn")) {
      const bookId = parseInt(e.target.getAttribute("data-id"));
      deleteBook(bookId);
    }
  });

  // Optional: Live search
  document.getElementById("admin-search").addEventListener("input", (e) => {
    const query = e.target.value.toLowerCase();
    const filteredBooks = window.adminBooks.filter(
      (book) =>
        book.title.toLowerCase().includes(query) ||
        book.author.toLowerCase().includes(query)
    );
    displayAdminBooks(filteredBooks);
  });
}

function openBookForm(book = null) {
  const form = document.getElementById("book-form");
  const modal = document.getElementById("book-form-modal");
  const formTitle = document.getElementById("form-title");

  if (book) {
    formTitle.textContent = "Edit Book";
    document.getElementById("book-id").value = book.id;
    document.getElementById("title").value = book.title;
    document.getElementById("author").value = book.author;
    document.getElementById("price").value = book.price;
    document.getElementById("stock").value = book.stock;
    document.getElementById("category").value = book.category;
    document.getElementById("image").value = book.image;
  } else {
    formTitle.textContent = "Add New Book";
    form.reset();
    document.getElementById("book-id").value = "";
  }

  modal.style.display = "block";
}

function saveBook() {
  const form = document.getElementById("book-form");
  const bookId = document.getElementById("book-id").value;

  const newBook = {
    id: bookId ? parseInt(bookId) : Date.now(),
    title: document.getElementById("title").value.trim(),
    author: document.getElementById("author").value.trim(),
    price: parseFloat(document.getElementById("price").value),
    stock: parseInt(document.getElementById("stock").value),
    category: document.getElementById("category").value,
    image: document.getElementById("image").value.trim(),
  };

  if (bookId) {
    // Edit
    const index = window.adminBooks.findIndex(
      (book) => book.id === parseInt(bookId)
    );
    if (index !== -1) {
      window.adminBooks[index] = newBook;
      alert("Book updated successfully!");
    }
  } else {
    // Add
    window.adminBooks.push(newBook);
    alert("Book added successfully!");
  }

  form.reset();
  document.getElementById("book-form-modal").style.display = "none";
  displayAdminBooks(window.adminBooks);
}

function deleteBook(bookId) {
  if (confirm("Are you sure you want to delete this book?")) {
    window.adminBooks = window.adminBooks.filter((book) => book.id !== bookId);
    displayAdminBooks(window.adminBooks);
    alert("Book deleted successfully!");
  }
}
