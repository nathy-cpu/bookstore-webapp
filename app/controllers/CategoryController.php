<?php

require_once __DIR__ . '/../models/Category.php';
require_once __DIR__ . '/../utils/View.php';

class CategoryController
{
    private $categoryModel;

    public function __construct()
    {
        $this->categoryModel = new Category();
    }

    public function index()
    {
        // Check if user is admin
        if (!isset($_SESSION['user_id']) || !isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
            header('Location: /login');
            exit;
        }

        $categories = $this->categoryModel->getAll();
        View::render('admin/categories/index', ['categories' => $categories]);
    }

    public function create()
    {
        // Check if user is admin
        if (!isset($_SESSION['user_id']) || !isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
            header('Location: /login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'] ?? '';
            $description = $_POST['description'] ?? '';

            if (empty($name)) {
                View::render('admin/categories/create', [
                    'error' => 'Category name is required',
                    'old' => $_POST
                ]);
                return;
            }

            if ($this->categoryModel->create($name, $description)) {
                header('Location: /admin/categories');
                exit;
            } else {
                View::render('admin/categories/create', [
                    'error' => 'Failed to create category',
                    'old' => $_POST
                ]);
            }
        } else {
            View::render('admin/categories/create');
        }
    }

    public function edit($id)
    {
        // Check if user is admin
        if (!isset($_SESSION['user_id']) || !isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
            header('Location: /login');
            exit;
        }

        $category = $this->categoryModel->getById($id);
        if (!$category) {
            header('Location: /admin/categories');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'] ?? '';
            $description = $_POST['description'] ?? '';

            if (empty($name)) {
                View::render('admin/categories/edit', [
                    'error' => 'Category name is required',
                    'category' => $category,
                    'old' => $_POST
                ]);
                return;
            }

            if ($this->categoryModel->update($id, $name, $description)) {
                header('Location: /admin/categories');
                exit;
            } else {
                View::render('admin/categories/edit', [
                    'error' => 'Failed to update category',
                    'category' => $category,
                    'old' => $_POST
                ]);
            }
        } else {
            View::render('admin/categories/edit', ['category' => $category]);
        }
    }

    public function delete($id)
    {
        // Check if user is admin
        if (!isset($_SESSION['user_id']) || !isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
            header('Location: /login');
            exit;
        }

        // Check if category has books
        $bookCount = $this->categoryModel->getBookCount($id);
        if ($bookCount > 0) {
            header('Location: /admin/categories?error=Cannot delete category with books');
            exit;
        }

        if ($this->categoryModel->delete($id)) {
            header('Location: /admin/categories?success=Category deleted');
        } else {
            header('Location: /admin/categories?error=Failed to delete category');
        }
        exit;
    }
}
