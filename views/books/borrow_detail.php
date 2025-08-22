<?php require_once __DIR__ . '/../partials/header.php'; ?>

<div class="content-card">
    <div class="content-header">
        <h2>Chi tiết Phiếu mượn #<?php echo htmlspecialchars($_GET['id'] ?? ''); ?></h2>
        <a href="javascript:history.back()" class="btn">Quay lại</a>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th>Ảnh bìa</th>
                <th>Tên sách</th>
                <th>Tác giả</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($books)): ?>
                <?php foreach ($books as $book): ?>
                    <tr>
                        <td>
                            <?php if ($book['image']): ?>
                                <img src="../public/uploads/<?php echo htmlspecialchars($book['image']); ?>" alt="<?php echo htmlspecialchars($book['title']); ?>" width="50">
                            <?php endif; ?>
                        </td>
                        <td><?php echo htmlspecialchars($book['title']); ?></td>
                        <td><?php echo htmlspecialchars($book['author']); ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="3">Không có sách nào trong phiếu mượn này.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

</div>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>