<?php
// models/User.php
require_once __DIR__ . '/../config/db.php';
class User {
    public static function findByUsername($username) {
        global $conn;
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public static function create($username, $email, $password, $role, $verify_code, $fullname = '', $dob = null, $address = '') {
        if ($role === 'staff') return false; // Không cho phép tạo tài khoản nhân viên
        global $conn;
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO users (username, email, password, role, verify_token, fullname, dob, address, plain_password) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssssss", $username, $email, $hash, $role, $verify_code, $fullname, $dob, $address, $password);
        return $stmt->execute();
    }

    public static function verifyEmail($email, $code) {
        global $conn;
        $stmt = $conn->prepare("UPDATE users SET verified = 1, verify_token = NULL WHERE email = ? AND verify_token = ?");
        $stmt->bind_param("ss", $email, $code);
        $stmt->execute();
        return $stmt->affected_rows > 0;
    }

    public static function findByEmail($email) {
        global $conn;
        $stmt = $conn->prepare("SELECT *, plain_password FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public static function updateProfile($id, $fullname, $dob, $address) {
        global $conn;
        $stmt = $conn->prepare("UPDATE users SET fullname = ?, dob = ?, address = ? WHERE id = ?");
        $stmt->bind_param("sssi", $fullname, $dob, $address, $id);
        return $stmt->execute();
    }

    public static function setResetToken($email, $token, $expire) {
        global $conn;
        $stmt = $conn->prepare("UPDATE users SET verify_token = ?, reset_token_expire = ? WHERE email = ?");
        $stmt->bind_param("sss", $token, $expire, $email);
        return $stmt->execute();
    }

    public static function find($id) {
        global $conn;
        $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public static function updatePassword($userId, $oldPassword, $newPassword) {
        global $conn;
        $user = self::find($userId);
        if (!$user || !password_verify($oldPassword, $user['password'])) {
            return false; // Old password does not match
        }

        $hash = password_hash($newPassword, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE users SET password = ?, plain_password = ? WHERE id = ?");
        $stmt->bind_param("ssi", $hash, $newPassword, $userId);
        return $stmt->execute();
    }

    public static function all() {
        global $conn;
        $sql = "SELECT * FROM users ORDER BY id DESC";
        $result = $conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public static function delete($id) {
        global $conn;
        $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    public static function updateRole($id, $role) {
        global $conn;
        if ($role === 'staff') return false; // Không cho phép cập nhật thành nhân viên
        $stmt = $conn->prepare("UPDATE users SET role = ? WHERE id = ?");
        $stmt->bind_param("si", $role, $id);
        return $stmt->execute();
    }
}
