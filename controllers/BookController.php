<?php
// controllers/BookController.php
require_once __DIR__ . '/../models/Book.php';
require_once __DIR__ . '/../models/Category.php';

class BookController {
    public static function index() {
        $page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
        $limit = 10;
        $offset = ($page - 1) * $limit;
        $search = isset($_GET['search']) ? trim($_GET['search']) : '';
        $author = isset($_GET['author']) ? trim($_GET['author']) : '';
        $category_id = isset($_GET['category_id']) && is_numeric($_GET['category_id']) ? intval($_GET['category_id']) : null;
        $sort = isset($_GET['sort']) ? $_GET['sort'] : '';
        $paged = Book::getPaged($offset, $limit, $search, $category_id, $sort, $author);
        $books = $paged['books'];
        $total = $paged['total'];
        $totalPages = ceil($total / $limit);
        $categories = Category::all();
        include __DIR__ . '/../views/books/index.php';
    }
    public static function create() {
        $error = '';
        $categories = Category::all();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title = trim($_POST['title']);
            $author = trim($_POST['author']);
            $category_id = $_POST['category_id'] ?? '';
            $description = trim($_POST['description']);
            $published_year = $_POST['published_year'] ?? '';
            $content = $_POST['content'] ?? null;
            $image = null;
            $quantity = isset($_POST['quantity']) ? max(1, intval($_POST['quantity'])) : 1;
            // Xử lý upload ảnh
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
                $image = uniqid('book_', true) . '.' . $ext;
                $uploadDir = __DIR__ . '/../public/uploads/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                if (!move_uploaded_file($_FILES['image']['tmp_name'], $uploadDir . $image)) {
                    $error = 'Lỗi khi tải ảnh lên!';
                    $image = null;
                }
            } elseif (isset($_FILES['image']) && $_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE) {
                $error = 'Lỗi upload file: ' . $_FILES['image']['error'];
            }
            if (empty($title) || empty($author)) {
                $error = 'Tên sách và tác giả không được để trống!';
            } elseif ($error) {
                // Giữ nguyên lỗi upload
            } else {
                $result = Book::create($title, $author, $category_id, $description, $published_year, $image, $content, $quantity);
                if ($result) {
                    $_SESSION['success_message'] = 'Thêm sách thành công!';
                    header('Location: ../public/index.php?action=books');
                    exit();
                } else {
                    $error = 'Lỗi khi thêm sách vào cơ sở dữ liệu!';
                }
            }
        }
        include __DIR__ . '/../views/books/create.php';
    }
    public static function edit() {
        $error = '';
        $categories = Category::all();
        $id = $_GET['id'] ?? null;
        $book = Book::find($id);
        if (!$book) {
            header('Location: ../public/index.php?action=books');
            exit();
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title = trim($_POST['title']);
            $author = trim($_POST['author']);
            $category_id = $_POST['category_id'] ?? '';
            $description = trim($_POST['description']);
            $published_year = $_POST['published_year'] ?? '';
            $content = $_POST['content'] ?? null;
            $image = $book['image'];
            $quantity = isset($_POST['quantity']) ? max(1, intval($_POST['quantity'])) : $book['quantity'];
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
                $image = uniqid('book_', true) . '.' . $ext;
                $uploadDir = __DIR__ . '/../public/uploads/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                if (!move_uploaded_file($_FILES['image']['tmp_name'], $uploadDir . $image)) {
                    $error = 'Lỗi khi tải ảnh mới lên!';
                    $image = $book['image'];
                }
            } elseif (isset($_FILES['image']) && $_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE) {
                $error = 'Lỗi upload file: ' . $_FILES['image']['error'];
            }
            if (empty($title) || empty($author)) {
                $error = 'Tên sách và tác giả không được để trống!';
            } elseif ($error) {
                // Giữ nguyên lỗi upload
            } else {
                $result = Book::update($id, $title, $author, $category_id, $description, $published_year, $image, $content, $quantity);
                if ($result) {
                    $_SESSION['success_message'] = 'Cập nhật sách thành công!';
                    header('Location: ../public/index.php?action=books');
                    exit();
                } else {
                    $error = 'Lỗi khi cập nhật sách!';
                }
            }
        }
        include __DIR__ . '/../views/books/edit.php';
    }
    public static function delete() {
        $id = $_GET['id'] ?? null;
        if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'manager') {
            $_SESSION['error_message'] = 'Bạn không có quyền thực hiện hành động này!';
            header('Location: ../public/index.php?action=books');
            exit();
        }
        if ($id) {
            $result = Book::delete($id);
            if ($result) {
                $_SESSION['success_message'] = 'Xóa sách thành công!';
            } else {
                $_SESSION['error_message'] = 'Lỗi khi xóa sách!';
            }
        }
        header('Location: ../public/index.php?action=books');
        exit();
    }
    public static function detail() {
        require_once __DIR__ . '/../models/Book.php';
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        $book = Book::findWithCategory($id);
        include __DIR__ . '/../views/books/detail.php';
    }
    public static function admin_report() {
        require_once __DIR__ . '/../models/Book.php';
        require_once __DIR__ . '/../models/User.php';
        require_once __DIR__ . '/../models/Borrow.php';
        require_once __DIR__ . '/../models/Fine.php';
        global $conn;
        $book_count = $conn->query("SELECT COUNT(*) AS cnt FROM books")->fetch_assoc()['cnt'];
        $user_count = $conn->query("SELECT COUNT(*) AS cnt FROM users")->fetch_assoc()['cnt'];
        $borrow_count = $conn->query("SELECT COUNT(*) AS cnt FROM borrow_requests")->fetch_assoc()['cnt'];
        $fine_count = $conn->query("SELECT COUNT(*) AS cnt FROM fines")->fetch_assoc()['cnt'];
        $returned_count = $conn->query("SELECT COUNT(*) AS cnt FROM borrow_requests WHERE status='returned'")->fetch_assoc()['cnt'];
        $not_returned_count = $conn->query("SELECT COUNT(*) AS cnt FROM borrow_requests WHERE status!='returned'")->fetch_assoc()['cnt'];
        // Phiếu phạt đã thanh toán và chưa thanh toán
        $paid_fine_count = $conn->query("SELECT COUNT(*) AS cnt FROM fines WHERE paid=1")->fetch_assoc()['cnt'];
        $unpaid_fine_count = $conn->query("SELECT COUNT(*) AS cnt FROM fines WHERE paid=0")->fetch_assoc()['cnt'];
        // Nếu có filter ngày tháng năm
        $date_filter = isset($_GET['report_date']) ? $_GET['report_date'] : date('Y-m-d');
        $books_on_date = (int)$conn->query("SELECT COUNT(*) AS cnt FROM books WHERE DATE(created_at) = '$date_filter'")->fetch_assoc()['cnt'];
        $borrows_on_date = (int)$conn->query("SELECT COUNT(*) AS cnt FROM borrow_requests WHERE DATE(created_at) = '$date_filter'")->fetch_assoc()['cnt'];
        $paid_fines_on_date = (int)$conn->query("SELECT COUNT(*) AS cnt FROM fines WHERE DATE(created_at) = '$date_filter' AND paid=1")->fetch_assoc()['cnt'];
        $unpaid_fines_on_date = (int)$conn->query("SELECT COUNT(*) AS cnt FROM fines WHERE DATE(created_at) = '$date_filter' AND paid=0")->fetch_assoc()['cnt'];
        include __DIR__ . '/../views/books/admin_report.php';
    }
    public static function single() {
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        $book = Book::findWithCategory($id);
        if (!$book) {
            include __DIR__ . '/../views/books/not_found.php';
            return;
        }
        include __DIR__ . '/../views/books/single.php';
    }
    public static function book() {
        $page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
        $limit = 10;
        $offset = ($page - 1) * $limit;
        $search = isset($_GET['search']) ? trim($_GET['search']) : '';
        $author = isset($_GET['author']) ? trim($_GET['author']) : '';
        $category_id = isset($_GET['category_id']) && is_numeric($_GET['category_id']) ? intval($_GET['category_id']) : null;
        $sort = isset($_GET['sort']) ? $_GET['sort'] : '';
        $paged = Book::getPaged($offset, $limit, $search, $category_id, $sort, $author);
        $books = $paged['books'];
        $total = $paged['total'];
        $totalPages = ceil($total / $limit);
        $categories = Category::all();
        include __DIR__ . '/../views/books/index.php';
    }
}
