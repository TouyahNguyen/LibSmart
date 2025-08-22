<?php require_once __DIR__ . '/../partials/header.php'; ?>
<div class="content-card">
    <div class="content-header">
        <h2>Quản lý Phiếu phạt</h2>
        <a href="index.php?action=fine_create" class="btn btn-primary">Thêm phiếu phạt</a>
    </div>
    <?php if (isset($_SESSION['success_message'])): ?>
        <p style="color: green; margin-bottom: 15px;"><?php echo htmlspecialchars($_SESSION['success_message']); unset($_SESSION['success_message']); ?></p>
    <?php endif; ?>
    <?php if (isset($_SESSION['error_message'])): ?>
        <p style="color: red; margin-bottom: 15px;"><?php echo htmlspecialchars($_SESSION['error_message']); unset($_SESSION['error_message']); ?></p>
    <?php endif; ?>
    <table class="data-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Tài khoản bị phạt</th>
                <th>Số tiền</th>
                <th>Lý do</th>
                <th>Ngày tạo</th>
                <th>Trạng thái</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($fines)): ?>
                <?php foreach ($fines as $fine): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($fine['id']); ?></td>
                        <td><?php echo htmlspecialchars($fine['username'] ?? $fine['user_id']); ?></td>
                        <td><?php echo htmlspecialchars(number_format($fine['amount'], 0, ',', '.')); ?> VNĐ</td>
                        <td><?php echo htmlspecialchars($fine['reason']); ?></td>
                        <td><?php echo htmlspecialchars($fine['created_at']); ?></td>
                        <td><?php echo $fine['paid'] ? '<span style="color:green">Đã thanh toán</span>' : '<span style="color:red">Chưa thanh toán</span>'; ?></td>
                        <td>
                            <a href="index.php?action=fine_edit&id=<?php echo $fine['id']; ?>">Sửa</a>
                            <a href="index.php?action=fine_delete&id=<?php echo $fine['id']; ?>" onclick="return confirm('Bạn có chắc chắn muốn xóa phiếu phạt này?');" style="color: red; margin-left: 8px;">Xóa</a>
                            <?php if (!$fine['paid']): ?>
                                <a href="index.php?action=fine_mark_paid&id=<?php echo $fine['id']; ?>" onclick="return confirm('Xác nhận đã đóng phạt?');" style="color: green; margin-left: 8px;">Xác nhận đã đóng phạt</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="7">Không có phiếu phạt nào.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
<?php require_once __DIR__ . '/../partials/footer.php'; ?>
