<?php require_once __DIR__ . '/../partials/header.php'; ?>

<div class="content-card">
    <?php if ($book): ?>
        <div class="book-detail-container" style="display: flex; gap: 30px;">
            <div class="book-cover" style="flex-shrink: 0;">
                <img src="../public/uploads/<?php echo htmlspecialchars($book['image'] ?? 'default.png'); ?>" alt="<?php echo htmlspecialchars($book['title']); ?>" style="width: 200px; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.1);">
            </div>
            <div class="book-info">
                <h2 style="margin-bottom: 10px;"><?php echo htmlspecialchars($book['title']); ?></h2>
                <p style="margin-bottom: 15px; font-size: 1.1em; color: #555;"><strong>Tác giả:</strong> <?php echo htmlspecialchars($book['author']); ?></p>
                <p style="margin-bottom: 10px;"><strong>Danh mục:</strong> <?php echo htmlspecialchars($book['category_name'] ?? 'Chưa phân loại'); ?></p>
                <p style="margin-bottom: 10px;"><strong>Năm xuất bản:</strong> <?php echo htmlspecialchars($book['published_year']); ?></p>
                <p style="margin-bottom: 20px;"><strong>Trạng thái:</strong> 
                    <?php if ($book['quantity'] <= 0): ?>
                        <span style="color: red; font-weight: bold;">Hết sách</span>
                    <?php else: ?>
                        <span style="color: green; font-weight: bold;">Còn <?php echo htmlspecialchars($book['quantity']); ?> cuốn</span>
                    <?php endif; ?>
                </p>
                <div class="description" style="margin-bottom: 30px;">
                    <h4 style="border-bottom: 1px solid #eee; padding-bottom: 5px; margin-bottom: 10px;">Mô tả sách</h4>
                    <p><?php echo nl2br(htmlspecialchars($book['description'])); ?></p>
                </div>
                
                <?php if ($book['quantity'] > 0): ?>
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <a href="index.php?action=add_to_cart&book_id=<?php echo $book['id']; ?>" class="btn btn-primary">Thêm vào giỏ mượn</a>
                    <?php else: ?>
                        <a href="index.php?action=login" class="btn btn-primary">Đăng nhập để mượn</a>
                    <?php endif; ?>
                <?php endif; ?>
                 <a href="index.php?action=home" class="btn btn-secondary" style="margin-left: 10px;">Quay lại</a>
            </div>
        </div>
    <?php else: ?>
        <p>Không tìm thấy thông tin sách.</p>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>