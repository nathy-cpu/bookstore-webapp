export function generateMockBooks(count = 5) {
  const genres = [
    "Fantasy",
    "Sci-Fi",
    "Mystery",
    "Romance",
    "Thriller",
    "Biography",
  ];
  const titles = [
    "The Great Adventure",
    "Stars Beyond",
    "Mystery of the Old House",
    "Love in Paris",
    "The Last Kingdom",
    "Deep Space",
    "The Hidden Truth",
  ];
  const authors = [
    "Jane Doe",
    "John Smith",
    "Arthur Conan",
    "Emily Bronte",
    "George Orwell",
    "J.K. Rowling",
    "Stephen King",
  ];

  return Array.from({ length: count }, (_, i) => {
    return {
      id: i + 1,
      title: `${titles[Math.floor(Math.random() * titles.length)]} ${
        Math.floor(Math.random() * 10) + 1
      }`,
      author: authors[Math.floor(Math.random() * authors.length)],
      price: (Math.random() * 30 + 5).toFixed(2),
      image: "../assets/placeholder.jpg", // Uses local placeholder when needed
      stock: Math.floor(Math.random() * 15),
      category: genres[Math.floor(Math.random() * genres.length)],
    };
  });
}
