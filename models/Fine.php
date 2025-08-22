<?php
// models/Fine.php
require_once __DIR__ . '/../config/db.php';
class Fine {
    public static function create($request_id, $user_id, $amount, $reason) {
        global $conn;
        require_once __DIR__ . '/../models/User.php';
        require_once __DIR__ . '/../helpers/notification.php';
        $stmt = $conn->prepare("INSERT INTO fines (request_id, user_id, amount, reason) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("iids", $request_id, $user_id, $amount, $reason);
        $result = $stmt->execute();
        // Gửi email thông báo phạt
        $user = User::find($user_id);
        if ($user && $user['email']) {
            $subject = 'Bạn bị phạt do trả sách trễ';
            $body = 'Chào ' . htmlspecialchars($user['fullname'] ?: $user['username']) . ',<br>Bạn đã bị phạt <b>' . number_format($amount, 0, ',', '.') . 'đ</b> cho phiếu mượn #' . $request_id . ' vì lý do: ' . htmlspecialchars($reason) . '.<br>Vui lòng thanh toán tại thư viện.';
            sendNotificationEmail($user['email'], $subject, $body);
        }
        return $result;
    }
    public static function getUserFines($user_id) {
        global $conn;
        $stmt = $conn->prepare("SELECT * FROM fines WHERE user_id = ? ORDER BY created_at DESC");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    public static function markAsPaid($fine_id) {
        global $conn;
        $stmt = $conn->prepare("UPDATE fines SET paid = 1 WHERE id = ?");
        $stmt->bind_param("i", $fine_id);
        return $stmt->execute();
    }
    public static function getPagedFines($user_id, $offset, $limit) {
        global $conn;
        $stmt = $conn->prepare("SELECT SQL_CALC_FOUND_ROWS * FROM fines WHERE user_id = ? ORDER BY created_at DESC LIMIT ?, ?");
        $stmt->bind_param("iii", $user_id, $offset, $limit);
        $stmt->execute();
        $result = $stmt->get_result();
        $fines = $result->fetch_all(MYSQLI_ASSOC);
        $total = $conn->query("SELECT FOUND_ROWS() as total")->fetch_assoc()['total'];
        return ['fines' => $fines, 'total' => $total];
    }
    public static function find($id) {
        global $conn;
        $stmt = $conn->prepare("SELECT * FROM fines WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }
    public static function update($id, $user_id, $amount, $reason) {
        global $conn;
        $stmt = $conn->prepare("UPDATE fines SET user_id = ?, amount = ?, reason = ? WHERE id = ?");
        $stmt->bind_param("idsi", $user_id, $amount, $reason, $id);
        return $stmt->execute();
    }
    public static function delete($id) {
        global $conn;
        $stmt = $conn->prepare("DELETE FROM fines WHERE id = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
