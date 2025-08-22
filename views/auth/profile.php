<?php require_once __DIR__ . '/../partials/header.php'; ?>

<div class="content-card">
    <div class="content-header">
        <h2>Hồ sơ của bạn</h2>
    </div>

    <?php if (!empty($user)): ?>
    <form action="index.php?action=profile_update" method="post">
        <div class="form-group">
            <label for="username">Tên đăng nhập</label>
            <input type="text" id="username" name="username" class="form-control" value="<?php echo htmlspecialchars($user['username']); ?>" disabled>
        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" class="form-control" value="<?php echo htmlspecialchars($user['email']); ?>" disabled>
        </div>
        <div class="form-group">
            <label for="fullname">Họ và tên</label>
            <input type="text" id="fullname" name="fullname" class="form-control" value="<?php echo htmlspecialchars($user['fullname'] ?? ''); ?>">
        </div>
        <div class="form-group">
            <label for="dob">Ngày sinh</label>
            <input type="date" id="dob" name="dob" class="form-control" value="<?php echo htmlspecialchars($user['dob'] ?? ''); ?>">
        </div>
        <div class="form-group">
            <label for="address">Địa chỉ</label>
            <textarea id="address" name="address" class="form-control" rows="3"><?php echo htmlspecialchars($user['address'] ?? ''); ?></textarea>
        </div>

        <button type="submit" class="btn btn-primary">Cập nhật hồ sơ</button>
        <a href="index.php?action=change_password" class="btn btn-secondary" style="margin-left: 10px;">Đổi mật khẩu</a>
    </form>
    <?php else: ?>
        <p>Không tìm thấy thông tin người dùng.</p>
    <?php endif; ?>

</div>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>