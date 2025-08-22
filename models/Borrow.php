<?php
// models/Borrow.php
require_once __DIR__ . '/../config/db.php';
class Borrow {
    public static function addToCart($user_id, $book_id) {
        require_once __DIR__ . '/Book.php';
        if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];
        // Only add if available
        if (!in_array($book_id, $_SESSION['cart']) && Book::isAvailable($book_id)) {
            $_SESSION['cart'][] = $book_id;
        }
    }
    public static function removeFromCart($book_id) {
        if (isset($_SESSION['cart'])) {
            $_SESSION['cart'] = array_diff($_SESSION['cart'], [$book_id]);
        }
    }
    public static function getCart() {
        return $_SESSION['cart'] ?? [];
    }
    public static function clearCart() {
        unset($_SESSION['cart']);
    }
    // Đăng ký mượn: tạo phiếu mượn ở trạng thái chờ duyệt
    public static function requestBorrow($user_id, $book_ids, $borrow_days = 14) {
        global $conn;
        $stmt = $conn->prepare("INSERT INTO borrow_requests (user_id, status, created_at, due_at) VALUES (?, 'pending', NOW(), NULL)");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $request_id = $conn->insert_id;
        // Lưu số ngày mượn vào session tạm thời để khi duyệt sẽ lấy ra tính due_at
        if (!isset($_SESSION['borrow_days'])) $_SESSION['borrow_days'] = [];
        $_SESSION['borrow_days'][$request_id] = $borrow_days;
        foreach ($book_ids as $book_id) {
            $stmt2 = $conn->prepare("INSERT INTO borrow_request_books (request_id, book_id) VALUES (?, ?)");
            $stmt2->bind_param("ii", $request_id, $book_id);
            $stmt2->execute();
            // Giảm số lượng sách khi mượn
            require_once __DIR__ . '/Book.php';
            Book::decreaseQuantity($book_id, 1);
        }
        return $request_id;
    }
    // Chuẩn hóa dữ liệu phiếu mượn cho view
    public static function normalizeRequest($request) {
        $request['request_date'] = isset($request['created_at']) && $request['created_at'] ? $request['created_at'] : '';
        $request['due_date'] = isset($request['due_at']) && $request['due_at'] ? $request['due_at'] : '';
        $request['return_date'] = isset($request['returned_at']) && $request['returned_at'] ? $request['returned_at'] : '';
        return $request;
    }
    // Lấy danh sách phiếu mượn cho admin duyệt
    public static function getAllRequests() {
        global $conn;
        $sql = "SELECT br.*, u.username, u.fullname FROM borrow_requests br JOIN users u ON br.user_id = u.id ORDER BY br.created_at DESC";
        $result = $conn->query($sql);
        $requests = $result->fetch_all(MYSQLI_ASSOC);
        // Chuẩn hóa dữ liệu cho view
        foreach ($requests as &$request) {
            $request = self::normalizeRequest($request);
        }
        return $requests;
    }
    // Lấy chi tiết sách trong phiếu mượn
    public static function getRequestBooks($request_id) {
        global $conn;
        $sql = "SELECT b.* FROM borrow_request_books brb JOIN books b ON brb.book_id = b.id WHERE brb.request_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $request_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    // Duyệt hoặc từ chối phiếu mượn
    public static function updateStatus($request_id, $status) {
        global $conn;
        if ($status === 'approved') {
            $borrowed_at = date('Y-m-d H:i:s');
            // Lấy số ngày mượn do user chọn từ session (nếu có), mặc định 14
            $borrow_days = 14;
            if (isset($_SESSION['borrow_days'][$request_id])) {
                $borrow_days = max(1, min(30, intval($_SESSION['borrow_days'][$request_id])));
                unset($_SESSION['borrow_days'][$request_id]);
            }
            $due_at = date('Y-m-d H:i:s', strtotime("+$borrow_days days"));
            $stmt = $conn->prepare("UPDATE borrow_requests SET status = ?, borrowed_at = ?, due_at = ? WHERE id = ?");
            $stmt->bind_param("sssi", $status, $borrowed_at, $due_at, $request_id);
            return $stmt->execute();
        } elseif ($status === 'returned') {
            $returned_at = date('Y-m-d H:i:s');
            // Lấy due_at và user_id để kiểm tra phạt
            $stmt = $conn->prepare("SELECT due_at, user_id FROM borrow_requests WHERE id = ?");
            $stmt->bind_param("i", $request_id);
            $stmt->execute();
            $stmt->bind_result($due_at, $user_id);
            $stmt->fetch();
            $stmt->close();
            // Luôn set status là 'returned' (không truyền biến $status)
            $stmt2 = $conn->prepare("UPDATE borrow_requests SET status = 'returned', returned_at = ? WHERE id = ?");
            $stmt2->bind_param("si", $returned_at, $request_id);
            $stmt2->execute();
            // Nếu trả trễ hạn thì tạo phiếu phạt
            if ($due_at && strtotime($returned_at) > strtotime($due_at)) {
                require_once __DIR__ . '/Fine.php';
                $daysLate = ceil((strtotime($returned_at) - strtotime($due_at)) / 86400);
                $amount = $daysLate * 5000; // 5.000đ/ngày trễ
                $reason = "Trả sách trễ $daysLate ngày";
                Fine::create($request_id, $user_id, $amount, $reason);
            }
            return true;
        } else {
            $stmt = $conn->prepare("UPDATE borrow_requests SET status = ? WHERE id = ?");
            $stmt->bind_param("si", $status, $request_id);
            return $stmt->execute();
        }
    }
    // Lấy phiếu mượn của user
    public static function getUserRequests($user_id) {
        global $conn;
        $sql = "SELECT * FROM borrow_requests WHERE user_id = ? ORDER BY created_at DESC";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    public static function getPagedRequests($user_id, $offset, $limit) {
        global $conn;
        $stmt = $conn->prepare("SELECT SQL_CALC_FOUND_ROWS * FROM borrow_requests WHERE user_id = ? ORDER BY created_at DESC LIMIT ?, ?");
        $stmt->bind_param("iii", $user_id, $offset, $limit);
        $stmt->execute();
        $result = $stmt->get_result();
        $requests = $result->fetch_all(MYSQLI_ASSOC);
        foreach ($requests as &$request) {
            $request = self::normalizeRequest($request);
        }
        $total = $conn->query("SELECT FOUND_ROWS() as total")->fetch_assoc()['total'];
        return ['requests' => $requests, 'total' => $total];
    }
    public static function getPagedAllRequests($offset, $limit) {
        global $conn;
        $sql = "SELECT SQL_CALC_FOUND_ROWS br.*, u.username, u.fullname FROM borrow_requests br JOIN users u ON br.user_id = u.id ORDER BY br.created_at DESC LIMIT ?, ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $offset, $limit);
        $stmt->execute();
        $result = $stmt->get_result();
        $requests = $result->fetch_all(MYSQLI_ASSOC);
        foreach ($requests as &$request) {
            $request = self::normalizeRequest($request);
        }
        $total = $conn->query("SELECT FOUND_ROWS() as total")->fetch_assoc()['total'];
        return ['requests' => $requests, 'total' => $total];
    }
    // Tìm phiếu mượn theo id
    public static function find($id) {
        global $conn;
        $stmt = $conn->prepare("SELECT * FROM borrow_requests WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }
    // Xóa phiếu mượn
    public static function delete($id) {
        global $conn;
        // Xóa chi tiết sách trong phiếu mượn
        $stmt1 = $conn->prepare("DELETE FROM borrow_request_books WHERE request_id = ?");
        $stmt1->bind_param("i", $id);
        $stmt1->execute();
        // Xóa phiếu phạt liên quan
        $stmt2 = $conn->prepare("DELETE FROM fines WHERE request_id = ?");
        $stmt2->bind_param("i", $id);
        $stmt2->execute();
        // Xóa phiếu mượn
        $stmt3 = $conn->prepare("DELETE FROM borrow_requests WHERE id = ?");
        $stmt3->bind_param("i", $id);
        $result = $stmt3->execute();
        return $result;
    }
}
