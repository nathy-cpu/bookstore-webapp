document.addEventListener("DOMContentLoaded", () => {
  // Initialize both books and users
  window.adminBooks = generateMockBooks();
  window.adminUsers = generateMockUsers();
  
  // Display books by default
  displayAdminBooks(window.adminBooks);
  setupAdminEventListeners();
});

// Mock data generators
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

function generateMockUsers(count = 5) {
  const roles = ["customer", "admin"];
  const statuses = ["active", "inactive", "suspended"];
  const firstNames = ["John", "Jane", "Robert", "Emily", "Michael", "Sarah"];
  const lastNames = ["Smith", "Johnson", "Williams", "Brown", "Jones"];
  
  const users = [];
  
  for (let i = 1; i <= count; i++) {
    const firstName = firstNames[Math.floor(Math.random() * firstNames.length)];
    const lastName = lastNames[Math.floor(Math.random() * lastNames.length)];
    
    users.push({
      id: Date.now() + i + 1000, // Different ID range from books
      name: `${firstName} ${lastName}`,
      email: `${firstName.toLowerCase()}.${lastName.toLowerCase()}@example.com`,
      role: roles[Math.floor(Math.random() * roles.length)],
      status: statuses[Math.floor(Math.random() * statuses.length)],
      createdAt: new Date(Date.now() - Math.floor(Math.random() * 1000 * 60 * 60 * 24 * 365)).toISOString()
    });
  }
  
  return users;
}

// Display functions
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
          <button class="edit-btn" data-id="${book.id}" data-type="book">Edit</button>
          <button class="delete-btn" data-id="${book.id}" data-type="book">Delete</button>
      </div>
    `;
    bookList.appendChild(bookCard);
  });
}

function displayAdminUsers(users) {
  const userList = document.getElementById("admin-user-list");
  userList.innerHTML = "";

  if (users.length === 0) {
    userList.innerHTML = "<p>No users available.</p>";
    return;
  }

  users.forEach((user) => {
    const userCard = document.createElement("div");
    userCard.className = "admin-user-card";
    userCard.innerHTML = `
      <div class="user-info">
          <h3>${user.name}</h3>
          <p>${user.email} | ${user.role} | ${user.status}</p>
          <small>Joined: ${new Date(user.createdAt).toLocaleDateString()}</small>
      </div>
      <div class="admin-user-actions">
          <button class="edit-btn" data-id="${user.id}" data-type="user">Edit</button>
          <button class="delete-btn" data-id="${user.id}" data-type="user">Delete</button>
      </div>
    `;
    userList.appendChild(userCard);
  });
}

// Event listeners
function setupAdminEventListeners() {
  // Tab switching
  document.getElementById("books-tab").addEventListener("click", (e) => {
    e.preventDefault();
    switchTab("books");
  });
  
  document.getElementById("users-tab").addEventListener("click", (e) => {
    e.preventDefault();
    switchTab("users");
  });

  // Book controls
  document.getElementById("add-book-btn").addEventListener("click", () => {
    openBookForm();
  });

  document.getElementById("book-form").addEventListener("submit", (e) => {
    e.preventDefault();
    saveBook();
  });

  document.getElementById("close-book-modal").addEventListener("click", () => {
    document.getElementById("book-form-modal").classList.add("hidden");
  });

  // User controls
  document.getElementById("add-user-btn").addEventListener("click", () => {
    openUserForm();
  });

  document.getElementById("user-form").addEventListener("submit", (e) => {
    e.preventDefault();
    saveUser();
  });

  document.getElementById("close-user-modal").addEventListener("click", () => {
    document.getElementById("user-form-modal").classList.add("hidden");
  });

  // Delegated event listeners for both books and users
  document.addEventListener("click", (e) => {
    // Edit buttons
    if (e.target.classList.contains("edit-btn")) {
      const id = parseInt(e.target.getAttribute("data-id"));
      const type = e.target.getAttribute("data-type");
      
      if (type === "book") {
        const book = window.adminBooks.find(b => b.id === id);
        if (book) openBookForm(book);
      } else if (type === "user") {
        const user = window.adminUsers.find(u => u.id === id);
        if (user) openUserForm(user);
      }
    }
    
    // Delete buttons
    else if (e.target.classList.contains("delete-btn")) {
      const id = parseInt(e.target.getAttribute("data-id"));
      const type = e.target.getAttribute("data-type");
      
      if (type === "book") {
        deleteBook(id);
      } else if (type === "user") {
        deleteUser(id);
      }
    }
  });

  // Search functionality
  document.getElementById("admin-search").addEventListener("input", (e) => {
    const query = e.target.value.toLowerCase();
    const filteredBooks = window.adminBooks.filter(
      book => book.title.toLowerCase().includes(query) ||
             book.author.toLowerCase().includes(query)
    );
    displayAdminBooks(filteredBooks);
  });

  document.getElementById("user-search").addEventListener("input", (e) => {
    const query = e.target.value.toLowerCase();
    const filteredUsers = window.adminUsers.filter(
      user => user.name.toLowerCase().includes(query) ||
             user.email.toLowerCase().includes(query))
    );
    displayAdminUsers(filteredUsers);
  });
}

// Tab switching
function switchTab(tab) {
  // Update active tab in navigation
  document.querySelectorAll(".nav-links a").forEach(link => {
    link.classList.remove("active");
  });
  
  if (tab === "books") {
    document.getElementById("books-tab").classList.add("active");
    document.getElementById("books-section").classList.remove("hidden");
    document.getElementById("users-section").classList.add("hidden");
  } else {
    document.getElementById("users-tab").classList.add("active");
    document.getElementById("users-section").classList.remove("hidden");
    document.getElementById("books-section").classList.add("hidden");
    displayAdminUsers(window.adminUsers);
  }
}

// Book form functions
function openBookForm(book = null) {
  const form = document.getElementById("book-form");
  const modal = document.getElementById("book-form-modal");
  const formTitle = document.getElementById("book-form-title");

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

  modal.classList.remove("hidden");
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
    const index = window.adminBooks.findIndex(book => book.id === parseInt(bookId));
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
  document.getElementById("book-form-modal").classList.add("hidden");
  displayAdminBooks(window.adminBooks);
}

function deleteBook(bookId) {
  if (confirm("Are you sure you want to delete this book?")) {
    window.adminBooks = window.adminBooks.filter(book => book.id !== bookId);
    displayAdminBooks(window.adminBooks);
    alert("Book deleted successfully!");
  }
}

// User form functions
function openUserForm(user = null) {
  const form = document.getElementById("user-form");
  const modal = document.getElementById("user-form-modal");
  const formTitle = document.getElementById("user-form-title");

  if (user) {
    formTitle.textContent = "Edit User";
    document.getElementById("user-id").value = user.id;
    document.getElementById("name").value = user.name;
    document.getElementById("email").value = user.email;
    document.getElementById("role").value = user.role;
    document.getElementById("status").value = user.status;
    document.getElementById("password").value = "";
    
    // Add password note for existing users
    const passwordNote = document.createElement("small");
    passwordNote.className = "password-note";
    passwordNote.textContent = "Leave blank to keep current password";
    document.getElementById("password").insertAdjacentElement("afterend", passwordNote);
  } else {
    formTitle.textContent = "Add New User";
    form.reset();
    document.getElementById("user-id").value = "";
    
    // Remove password note if it exists
    const existingNote = document.querySelector(".password-note");
    if (existingNote) existingNote.remove();
  }

  modal.classList.remove("hidden");
}

function saveUser() {
  const form = document.getElementById("user-form");
  const userId = document.getElementById("user-id").value;

  const newUser = {
    id: userId ? parseInt(userId) : Date.now() + 1000, // Different ID range from books
    name: document.getElementById("name").value.trim(),
    email: document.getElementById("email").value.trim(),
    role: document.getElementById("role").value,
    status: document.getElementById("status").value,
    createdAt: userId ? 
      window.adminUsers.find(u => u.id === parseInt(userId)).createdAt : 
      new Date().toISOString()
  };

  // Only include password if it was provided
  const password = document.getElementById("password").value.trim();
  if (password) {
    newUser.password = password; // In a real app, you would hash this
  }

  if (userId) {
    // Edit
    const index = window.adminUsers.findIndex(user => user.id === parseInt(userId));
    if (index !== -1) {
      window.adminUsers[index] = newUser;
      alert("User updated successfully!");
    }
  } else {
    // Add
    window.adminUsers.push(newUser);
    alert("User added successfully!");
  }

  form.reset();
  document.getElementById("user-form-modal").classList.add("hidden");
  displayAdminUsers(window.adminUsers);
}

function deleteUser(userId) {
  if (confirm("Are you sure you want to delete this user?")) {
    window.adminUsers = window.adminUsers.filter(user => user.id !== userId);
    displayAdminUsers(window.adminUsers);
    alert("User deleted successfully!");
  }
}