<?php
// Khởi tạo database và bảng users cho LibSmart
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "libsmart";

$conn = new mysqli($servername, $username, $password);
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}
// Tạo database nếu chưa có
$sql = "CREATE DATABASE IF NOT EXISTS $dbname CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci";
if ($conn->query($sql) === TRUE) {
    echo "Tạo database thành công!<br>";
} else {
    die("Lỗi tạo database: " . $conn->error);
}
$conn->select_db($dbname);
// Tạo bảng users
$sql = "CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('reader', 'manager') NOT NULL
) ENGINE=InnoDB;";
if ($conn->query($sql) === TRUE) {
    echo "Tạo bảng users thành công!<br>";
} else {
    die("Lỗi tạo bảng: " . $conn->error);
}
// Thêm tài khoản mẫu
$hash = password_hash('123456', PASSWORD_DEFAULT);
$conn->query("INSERT IGNORE INTO users (username, password, role) VALUES ('admin', '$hash', 'manager')");
$conn->query("INSERT IGNORE INTO users (username, password, role) VALUES ('reader', '$hash', 'reader')");
echo "Đã thêm tài khoản mẫu: admin/123456 (manager), reader/123456 (reader)";
$conn->close();
