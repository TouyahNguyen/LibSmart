<?php require_once __DIR__ . '/../partials/header.php'; ?>
<div class="content-card">
    <?php if (!$book): ?>
        <h2>Không tìm thấy sách!</h2>
    <?php else: ?>
        <div style="display: flex; gap: 30px; align-items: flex-start;">
            <div>
                <?php if ($book['image']): ?>
                    <img src="../public/uploads/<?php echo htmlspecialchars($book['image']); ?>" alt="<?php echo htmlspecialchars($book['title']); ?>" style="width: 150px; border-radius: 8px;">
                <?php endif; ?>
            </div>
            <div>
                <h2 style="margin-bottom: 10px; color: #4CAFEF; font-size: 2rem;"><?php echo htmlspecialchars($book['title']); ?></h2>
                <p><strong>Tác giả:</strong> <?php echo htmlspecialchars($book['author']); ?></p>
                <p><strong>Danh mục:</strong> <?php echo htmlspecialchars($book['category_name'] ?? 'Chưa phân loại'); ?></p>
                <p><strong>Năm xuất bản:</strong> <?php echo htmlspecialchars($book['published_year']); ?></p>
                <p><strong>Trạng thái:</strong> 
                    <?php if ($book['quantity'] <= 0): ?>
                        <span style="color: red; font-weight: bold;">Hết sách</span>
                    <?php else: ?>
                        <span style="color: green; font-weight: bold;">Còn <?php echo htmlspecialchars($book['quantity']); ?> cuốn</span>
                    <?php endif; ?>
                </p>
                <div style="margin-bottom: 30px;">
                    <h4 style="border-bottom: 1px solid #eee; padding-bottom: 5px; margin-bottom: 10px;">Mô tả sách</h4>
                    <div><?php echo nl2br(htmlspecialchars($book['description'])); ?></div>
                </div>
                <?php if ($book['quantity'] > 0): ?>
                    <a href="index.php?action=add_to_cart&book_id=<?php echo $book['id']; ?>" class="btn btn-primary">Thêm vào giỏ mượn</a>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>
</div>
<?php require_once __DIR__ . '/../partials/footer.php'; ?>
