<?php require_once __DIR__ . '/../partials/header.php'; ?>

<div class="content-card">
    <div class="content-header">
        <h2>Giỏ sách của bạn</h2>
    </div>

    <?php if (!empty($books)) : ?>
        <form action="index.php?action=request_borrow" method="post">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Ảnh bìa</th>
                        <th>Tên sách</th>
                        <th>Tác giả</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($books as $book) : ?>
                        <tr>
                            <td>
                                <?php if ($book['image']) : ?>
                                    <img src="../public/uploads/<?php echo htmlspecialchars($book['image']); ?>" alt="<?php echo htmlspecialchars($book['title']); ?>" width="50">
                                <?php endif; ?>
                            </td>
                            <td><?php echo htmlspecialchars($book['title']); ?></td>
                            <td><?php echo htmlspecialchars($book['author']); ?></td>
                            <td class="action-buttons">
                                <?php if (isset($book['is_available']) && !$book['is_available']): ?>
                                    <span style="color: red; font-weight: bold;">Không có sẵn</span>
                                <?php else: ?>
                                    <a href="index.php?action=remove_from_cart&book_id=<?php echo $book['id']; ?>" style="color: red;">Xóa</a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <div class="form-group" style="margin-top: 20px; max-width: 300px;">
                <label for="borrow_days">Số ngày mượn (tối đa 30)</label>
                <input type="number" name="borrow_days" id="borrow_days" class="form-control" value="14" min="1" max="30">
            </div>

            <button type="submit" class="btn btn-primary" style="margin-top: 10px;">Gửi yêu cầu mượn</button>
        </form>
    <?php else : ?>
        <p>Giỏ sách của bạn đang trống.</p>
        <a href="index.php?action=home" class="btn btn-primary" style="margin-top: 20px;">Bắt đầu tìm sách</a>
    <?php endif; ?>

    <?php if (isset($_SESSION['error_message'])): ?>
        <p style="color: red; margin-bottom: 15px;"><?php echo htmlspecialchars($_SESSION['error_message']); unset($_SESSION['error_message']); ?></p>
    <?php endif; ?>
    <?php if (isset($_SESSION['success_message'])): ?>
        <p style="color: green; margin-bottom: 15px;"><?php echo htmlspecialchars($_SESSION['success_message']); unset($_SESSION['success_message']); ?></p>
    <?php endif; ?>

</div>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>