<?php require_once __DIR__ . '/../partials/header.php'; ?>

<div class="content-card">
    <div class="content-header">
        <h2>Phiếu mượn của tôi</h2>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th>ID Phiếu</th>
                <th>Ngày yêu cầu</th>
                <th>Ngày hẹn trả</th>
                <th>Ngày trả thực tế</th>
                <th>Trạng thái</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($requests)): ?>
                <?php foreach ($requests as $request): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($request['id']); ?></td>
                        <td><?php echo htmlspecialchars($request['request_date']); ?></td>
                        <td><?php echo htmlspecialchars($request['due_date']); ?></td>
                        <td><?php echo htmlspecialchars($request['return_date'] ?? 'Chưa trả'); ?></td>
                        <td>
                            <?php 
                                $status_text = '';
                                $status_color = '';
                                switch ($request['status']) {
                                    case 'pending':
                                        $status_text = 'Chờ duyệt';
                                        $status_color = 'orange';
                                        break;
                                    case 'approved':
                                        $status_text = 'Đã duyệt';
                                        $status_color = 'blue';
                                        break;
                                    case 'rejected':
                                        $status_text = 'Bị từ chối';
                                        $status_color = 'red';
                                        break;
                                    case 'returned':
                                        $status_text = 'Đã trả';
                                        $status_color = 'green';
                                        break;
                                }
                                echo "<span style='color: $status_color; font-weight: bold;'>" . htmlspecialchars($status_text) . "</span>";
                            ?>
                        </td>
                        <td class="action-buttons">
                            <a href="index.php?action=borrow_detail&id=<?php echo $request['id']; ?>">Xem chi tiết</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6">Bạn chưa có yêu cầu mượn nào.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <!-- Pagination -->
    <div class="pagination" style="margin-top: 20px; text-align: center;">
        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <a href="index.php?action=my_borrows&page=<?php echo $i; ?>" style="padding: 8px 12px; margin: 0 2px; border: 1px solid #ddd; <?php echo ($i == $page) ? 'background-color: #4CAFEF; color: white;' : ''; ?>">
                <?php echo $i; ?>
            </a>
        <?php endfor; ?>
    </div>

</div>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>