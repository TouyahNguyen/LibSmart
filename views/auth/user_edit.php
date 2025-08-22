<?php require_once __DIR__ . '/../partials/header.php'; ?>
<div class="content-card" style="max-width:500px;margin:40px auto;">
    <h2>Sửa quyền tài khoản</h2>
    <form method="post">
        <div class="form-group">
            <label>Tên đăng nhập:</label>
            <input type="text" class="form-control" value="<?= htmlspecialchars($user['username']) ?>" disabled>
        </div>
        <div class="form-group">
            <label>Email:</label>
            <input type="text" class="form-control" value="<?= htmlspecialchars($user['email']) ?>" disabled>
        </div>
        <div class="form-group">
            <label>Quyền:</label>
            <select name="role" class="form-control">
                <option value="reader" <?= $user['role']==='reader'?'selected':'' ?>>Độc giả</option>
                <option value="staff" <?= $user['role']==='staff'?'selected':'' ?>>Nhân viên</option>
                <option value="admin" <?= $user['role']==='admin'?'selected':'' ?>>Quản trị viên</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
        <a href="index.php?action=user_manage" class="btn btn-secondary">Quay lại</a>
    </form>
</div>
<?php require_once __DIR__ . '/../partials/footer.php'; ?>
