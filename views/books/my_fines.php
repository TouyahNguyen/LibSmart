<?php require_once __DIR__ . '/../partials/header.php'; ?>

<div class="content-card">
    <div class="content-header">
        <h2>Phiếu phạt của tôi</h2>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th>ID Phạt</th>
                <th>ID Phiếu mượn</th>
                <th>Số tiền</th>
                <th>Lý do</th>
                <th>Ngày tạo</th>
                <th>Trạng thái</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($fines)): ?>
                <?php foreach ($fines as $fine): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($fine['id']); ?></td>
                        <td><?php echo isset($fine['request_id']) && $fine['request_id'] ? htmlspecialchars($fine['request_id']) : '<span style="color:gray">Không liên kết</span>'; ?></td>
                        <td><?php echo htmlspecialchars(number_format($fine['amount'], 0, ',', '.')); ?> VNĐ</td>
                        <td><?php echo htmlspecialchars($fine['reason']); ?></td>
                        <td><?php echo htmlspecialchars($fine['created_at']); ?></td>
                        <td>
                             <?php 
                                $status_text = $fine['paid'] ? 'Đã thanh toán' : 'Chưa thanh toán';
                                $status_color = $fine['paid'] ? 'green' : 'red';
                                echo "<span style='color: $status_color; font-weight: bold;'>" . htmlspecialchars($status_text) . "</span>";
                            ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6">Bạn không có khoản phạt nào.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <!-- Pagination -->
    <div class="pagination" style="margin-top: 20px; text-align: center;">
        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <a href="index.php?action=my_fines&page=<?php echo $i; ?>" style="padding: 8px 12px; margin: 0 2px; border: 1px solid #ddd; <?php echo ($i == $page) ? 'background-color: #4CAFEF; color: white;' : ''; ?>">
                <?php echo $i; ?>
            </a>
        <?php endfor; ?>
    </div>

</div>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>