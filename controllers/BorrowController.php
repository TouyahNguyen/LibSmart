<?php
// controllers/BorrowController.php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
require_once __DIR__ . '/../models/Borrow.php';
require_once __DIR__ . '/../models/Book.php';

class BorrowController {
    // Thêm sách vào giỏ
    public static function add_to_cart() {
        $book_id = $_GET['book_id'] ?? null;
        if ($book_id) {
            require_once __DIR__ . '/../models/Book.php';
            if (Book::isAvailable($book_id)) {
                Borrow::addToCart($_SESSION['user_id'], $book_id);
            } else {
                $_SESSION['error_message'] = 'Sách này hiện không có sẵn để mượn.';
            }
        }
        header('Location: ../public/index.php?action=cart');
        exit();
    }
    // Xóa sách khỏi giỏ
    public static function remove_from_cart() {
        $book_id = $_GET['book_id'] ?? null;
        if ($book_id) {
            Borrow::removeFromCart($book_id);
        }
        header('Location: ../public/index.php?action=cart');
        exit();
    }
    // Hiển thị giỏ sách
    public static function cart() {
        $cart = Borrow::getCart();
        $books = [];
        foreach ($cart as $book_id) {
            $books[] = Book::find($book_id);
        }
        include __DIR__ . '/../views/books/cart.php';
    }
    // Đăng ký mượn sách
    public static function request_borrow() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: ../public/index.php?action=login');
            exit();
        }
        $cart = Borrow::getCart();
        if (empty($cart)) {
            $_SESSION['error_message'] = 'Giỏ sách của bạn đang trống.';
            header('Location: ../public/index.php?action=cart');
            exit();
        }
        require_once __DIR__ . '/../models/Book.php';
        $available_books = array_filter($cart, function($book_id) {
            return Book::isAvailable($book_id);
        });
        if (empty($available_books)) {
            $_SESSION['error_message'] = 'Tất cả sách trong giỏ đã bị mượn hoặc không còn sẵn có.';
            header('Location: ../public/index.php?action=cart');
            exit();
        }
        $borrow_days = isset($_POST['borrow_days']) ? max(1, min(30, intval($_POST['borrow_days']))) : 14;
        Borrow::requestBorrow($_SESSION['user_id'], $available_books, $borrow_days);
        Borrow::clearCart();
        $_SESSION['success_message'] = 'Yêu cầu mượn sách đã được gửi.';
        header('Location: ../public/index.php?action=my_borrows');
        exit();
    }
    // Danh sách phiếu mượn của user
    public static function my_borrows() {
        $page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
        $limit = 10;
        $offset = ($page - 1) * $limit;
        $paged = Borrow::getPagedRequests($_SESSION['user_id'], $offset, $limit);
        $requests = $paged['requests'];
        $total = $paged['total'];
        $totalPages = ceil($total / $limit);
        include __DIR__ . '/../views/books/my_borrows.php';
    }
    // Danh sách phiếu mượn cho admin duyệt
    public static function admin_borrows() {
        $page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
        $limit = 10;
        $offset = ($page - 1) * $limit;
        $paged = Borrow::getPagedAllRequests($offset, $limit);
        $requests = $paged['requests'];
        $total = $paged['total'];
        $totalPages = ceil($total / $limit);
        include __DIR__ . '/../views/books/admin_borrows.php';
    }
    // Duyệt hoặc từ chối/trả phiếu mượn
    public static function update_status() {
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'manager') {
            $title = 'Lỗi quyền truy cập';
            $message = 'Bạn không có quyền thực hiện hành động này!';
            include __DIR__ . '/../views/books/borrow_action_result.php';
            return;
        }
        require_once __DIR__ . '/../models/User.php';
        require_once __DIR__ . '/../helpers/notification.php';
        $request_id = $_GET['id'] ?? null;
        $status = $_GET['status'] ?? null;
        if ($request_id && in_array($status, ['approved','rejected','returned'])) {
            $borrow = Borrow::find($request_id);
            if (!$borrow) {
                $title = 'Lỗi';
                $message = 'Phiếu mượn không tồn tại!';
                include __DIR__ . '/../views/books/borrow_action_result.php';
                return;
            }
            $user = User::find($borrow['user_id']);
            $success = Borrow::updateStatus($request_id, $status);
            if ($success) {
                if ($status === 'approved') {
                    $title = 'Duyệt phiếu mượn thành công';
                    $message = 'Phiếu mượn sách đã được duyệt. Người dùng sẽ nhận được thông báo.';
                } elseif ($status === 'rejected') {
                    $title = 'Từ chối phiếu mượn thành công';
                    $message = 'Phiếu mượn sách đã bị từ chối. Người dùng sẽ nhận được thông báo.';
                } elseif ($status === 'returned') {
                    $title = 'Trả sách thành công';
                    $message = 'Phiếu mượn đã được đánh dấu là trả sách thành công. Người dùng sẽ nhận được thông báo.';
                }
                // Gửi email thông báo
                if ($user && $user['email']) {
                    $subject = '';
                    $body = '';
                    if ($status === 'approved') {
                        $subject = 'Phiếu mượn sách đã được duyệt';
                        $body = 'Chào ' . htmlspecialchars($user['fullname'] ?: $user['username']) . ',<br>Phiếu mượn sách #' . $request_id . ' của bạn đã được <b>duyệt</b>.<br>Vui lòng đến thư viện nhận sách.';
                    } elseif ($status === 'rejected') {
                        $subject = 'Phiếu mượn sách bị từ chối';
                        $body = 'Chào ' . htmlspecialchars($user['fullname'] ?: $user['username']) . ',<br>Phiếu mượn sách #' . $request_id . ' của bạn đã bị <b>từ chối</b>.';
                    } elseif ($status === 'returned') {
                        $subject = 'Phiếu mượn sách đã trả';
                        $body = 'Chào ' . htmlspecialchars($user['fullname'] ?: $user['username']) . ',<br>Bạn đã <b>trả</b> sách cho phiếu mượn #' . $request_id . '.';
                    }
                    if ($subject && $body) {
                        sendNotificationEmail($user['email'], $subject, $body);
                    }
                }
            } else {
                $title = 'Lỗi';
                $message = 'Cập nhật trạng thái phiếu mượn thất bại!';
            }
        } else {
            $title = 'Lỗi';
            $message = 'Yêu cầu không hợp lệ!';
        }
        include __DIR__ . '/../views/books/borrow_action_result.php';
    }
    // Xem chi tiết phiếu mượn
    public static function borrow_detail() {
        $request_id = $_GET['id'] ?? null;
        if ($request_id) {
            $books = Borrow::getRequestBooks($request_id);
            include __DIR__ . '/../views/books/borrow_detail.php';
        }
    }
    // Danh sách phiếu phạt của user
    public static function my_fines() {
        require_once __DIR__ . '/../models/Fine.php';
        $page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
        $limit = 10;
        $offset = ($page - 1) * $limit;
        $paged = Fine::getPagedFines($_SESSION['user_id'], $offset, $limit);
        $fines = $paged['fines'];
        $total = $paged['total'];
        $totalPages = ceil($total / $limit);
        include __DIR__ . '/../views/books/my_fines.php';
    }
    // Xóa phiếu mượn (chỉ cho admin)
    public static function borrow_delete() {
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'manager') {
            $title = 'Lỗi quyền truy cập';
            $message = 'Bạn không có quyền xóa phiếu mượn!';
            include __DIR__ . '/../views/books/borrow_action_result.php';
            return;
        }
        $id = $_GET['id'] ?? null;
        if ($id) {
            require_once __DIR__ . '/../models/Borrow.php';
            $success = Borrow::delete($id);
            if ($success) {
                $title = 'Xóa phiếu mượn thành công';
                $message = 'Phiếu mượn đã được xóa.';
            } else {
                $title = 'Lỗi';
                $message = 'Xóa phiếu mượn thất bại!';
            }
        } else {
            $title = 'Lỗi';
            $message = 'Yêu cầu không hợp lệ!';
        }
        include __DIR__ . '/../views/books/borrow_action_result.php';
    }
}
