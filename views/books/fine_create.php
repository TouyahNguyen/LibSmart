<?php require_once __DIR__ . '/../partials/header.php'; ?>
<div class="content-card" style="max-width: 500px; margin: 40px auto;">
    <div class="content-header">
        <h2>Thêm Phiếu Phạt</h2>
    </div>
    <?php if (!empty($error)): ?>
        <p style="color: red; margin-bottom: 15px;"><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>
    <form action="index.php?action=fine_create" method="post">
        <div class="form-group">
            <label for="user_id">Tài khoản bị phạt</label>
            <select name="user_id" id="user_id" class="form-control" required>
                <?php foreach ($users as $user): ?>
                    <option value="<?php echo $user['id']; ?>"><?php echo htmlspecialchars($user['username']); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="amount">Số tiền phạt (VNĐ)</label>
            <input type="number" name="amount" id="amount" class="form-control" min="0" required>
        </div>
        <div class="form-group">
            <label for="reason">Lý do phạt</label>
            <input type="text" name="reason" id="reason" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Thêm phiếu phạt</button>
        <a href="index.php?action=admin_fines" class="btn btn-secondary" style="margin-left: 10px;">Hủy</a>
    </form>
</div>
<?php require_once __DIR__ . '/../partials/footer.php'; ?>
