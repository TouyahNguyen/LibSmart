<?php
// controllers/CategoryController.php
require_once __DIR__ . '/../models/Category.php';

class CategoryController {
    public static function index() {
        $categories = Category::all();
        include __DIR__ . '/../views/categories/index.php';
    }
    public static function create() {
        $error = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name']);
            $description = trim($_POST['description']);
            if (empty($name)) {
                $error = 'Tên danh mục không được để trống!';
            } else {
                $result = Category::create($name, $description);
                if ($result) {
                    $_SESSION['success_message'] = 'Thêm danh mục thành công!';
                    header('Location: ../public/index.php?action=categories');
                    exit();
                } else {
                    $error = 'Lỗi khi thêm danh mục vào cơ sở dữ liệu!';
                }
            }
        }
        include __DIR__ . '/../views/categories/create.php';
    }
    public static function edit() {
        $error = '';
        $id = $_GET['id'] ?? null;
        $category = Category::find($id);
        if (!$category) {
            header('Location: ../public/index.php?action=categories');
            exit();
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name']);
            $description = trim($_POST['description']);
            if (empty($name)) {
                $error = 'Tên danh mục không được để trống!';
            } else {
                $result = Category::update($id, $name, $description);
                if ($result) {
                    $_SESSION['success_message'] = 'Cập nhật danh mục thành công!';
                    header('Location: ../public/index.php?action=categories');
                    exit();
                } else {
                    $error = 'Lỗi khi cập nhật danh mục!';
                }
            }
        }
        include __DIR__ . '/../views/categories/edit.php';
    }
    public static function delete() {
        $id = $_GET['id'] ?? null;
        if ($id) {
            $result = Category::delete($id);
            if ($result) {
                $_SESSION['success_message'] = 'Xóa danh mục thành công!';
            } else {
                $_SESSION['error_message'] = 'Lỗi khi xóa danh mục!';
            }
        }
        header('Location: ../public/index.php?action=categories');
        exit();
    }
}
