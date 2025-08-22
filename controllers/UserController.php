<?php
require_once __DIR__ . '/../models/User.php';
class UserController {
    public static function manage() {
        require __DIR__ . '/../views/auth/user_manage.php';
    }
    public static function delete() {
        $id = intval($_GET['id'] ?? 0);
        if ($id) {
            \User::delete($id);
            header('Location: index.php?action=user_manage');
            exit();
        }
        echo 'Không tìm thấy tài khoản.';
    }
    public static function edit() {
        $id = intval($_GET['id'] ?? 0);
        $user = \User::find($id);
        if (!$user) {
            echo 'Không tìm thấy tài khoản.';
            return;
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $role = $_POST['role'] ?? $user['role'];
            \User::updateRole($id, $role);
            header('Location: index.php?action=user_manage');
            exit();
        }
        require __DIR__ . '/../views/auth/user_edit.php';
    }
}
