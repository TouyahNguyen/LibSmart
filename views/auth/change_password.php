<?php require_once __DIR__ . '/../partials/header.php'; ?>

<div class="content-card">
    <div class="content-header">
        <h2>Đổi mật khẩu</h2>
    </div>

    <?php if (!empty($msg)): ?>
        <p style="margin-bottom: 15px; color: <?php echo (strpos($msg, 'thành công') !== false) ? 'green' : 'red'; ?>;"><?php echo htmlspecialchars($msg); ?></p>
    <?php endif; ?>

    <form action="index.php?action=change_password" method="post">
        <div class="form-group">
            <label for="current_password">Mật khẩu hiện tại</label>
            <input type="password" id="current_password" name="current_password" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="new_password">Mật khẩu mới</label>
            <input type="password" id="new_password" name="new_password" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="confirm_password">Xác nhận mật khẩu mới</label>
            <input type="password" id="confirm_password" name="confirm_password" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary">Đổi mật khẩu</button>
        <a href="index.php?action=profile" class="btn btn-secondary" style="margin-left: 10px;">Hủy</a>
    </form>

</div>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>
