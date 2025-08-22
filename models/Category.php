<?php
// models/Category.php
require_once __DIR__ . '/../config/db.php';
class Category {
    // Lấy tất cả danh mục
    public static function all() {
        global $conn;
        $sql = "SELECT * FROM categories ORDER BY id DESC";
        $result = $conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    // Tìm danh mục theo id
    public static function find($id) {
        global $conn;
        $stmt = $conn->prepare("SELECT * FROM categories WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }
    // Thêm danh mục mới
    public static function create($name, $description) {
        global $conn;
        $stmt = $conn->prepare("INSERT INTO categories (name, description) VALUES (?, ?)");
        $stmt->bind_param("ss", $name, $description);
        return $stmt->execute();
    }
    // Sửa danh mục
    public static function update($id, $name, $description) {
        global $conn;
        $stmt = $conn->prepare("UPDATE categories SET name = ?, description = ? WHERE id = ?");
        $stmt->bind_param("ssi", $name, $description, $id);
        return $stmt->execute();
    }
    // Xóa danh mục
    public static function delete($id) {
        global $conn;
        $stmt = $conn->prepare("DELETE FROM categories WHERE id = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
