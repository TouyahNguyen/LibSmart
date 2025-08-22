<?php require_once __DIR__ . '/../partials/header.php'; ?>

<div class="content-card">
    <div class="content-header">
        <h2>Danh sách Sách</h2>
    </div>

    <?php if (isset($_SESSION['success_message'])): ?>
        <p style="color: green; margin-bottom: 15px;"><?php echo htmlspecialchars($_SESSION['success_message']); unset($_SESSION['success_message']); ?></p>
    <?php endif; ?>

    <?php if (isset($_SESSION['error_message'])): ?>
        <p style="color: red; margin-bottom: 15px;"><?php echo htmlspecialchars($_SESSION['error_message']); unset($_SESSION['error_message']); ?></p>
    <?php endif; ?>

    <!-- Search, Filter, and Sort Form -->
    <form method="get" action="index.php" class="filter-form" style="margin-bottom: 20px; display: flex; gap: 15px; align-items: center;">
        <input type="hidden" name="action" value="home">
        <input type="text" name="search" class="form-control" placeholder="Tìm theo tên sách hoặc tác giả..." value="<?php echo htmlspecialchars($search ?? ''); ?>" style="width: 300px;">
        <select name="category_id" class="form-control" style="width: 200px;">
            <option value="">Tất cả danh mục</option>
            <?php foreach ($categories as $category): ?>
                <option value="<?php echo $category['id']; ?>" <?php echo (isset($category_id) && $category_id == $category['id']) ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($category['name']); ?>
                </option>
            <?php endforeach; ?>
        </select>
        <select name="sort" class="form-control" style="width: 180px;">
            <option value="">Sắp xếp theo...</option>
            <option value="title_asc" <?php echo (isset($sort) && $sort == 'title_asc') ? 'selected' : ''; ?>>Tên sách A-Z</option>
            <option value="title_desc" <?php echo (isset($sort) && $sort == 'title_desc') ? 'selected' : ''; ?>>Tên sách Z-A</option>
            <option value="year_desc" <?php echo (isset($sort) && $sort == 'year_desc') ? 'selected' : ''; ?>>Năm xuất bản mới nhất</option>
            <option value="year_asc" <?php echo (isset($sort) && $sort == 'year_asc') ? 'selected' : ''; ?>>Năm xuất bản cũ nhất</option>
        </select>
        <button type="submit" class="btn btn-primary">Lọc/Sắp xếp</button>
    </form>

    <table class="data-table">
        <thead>
            <tr>
                <th>Ảnh bìa</th>
                <th>Tên sách</th>
                <th>Tác giả</th>
                <th>Danh mục</th>
                <th>Trạng thái</th>
                <th>Số lượng</th>
                <th>Hành động</th>
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
                        <td><?php echo htmlspecialchars($book['category_name'] ?? 'N/A'); ?></td>
                        <td>
                            <?php if ($book['is_available']): ?>
                                <span style="color: green;">Có sẵn</span>
                            <?php else: ?>
                                <span style="color: red;">Đã mượn</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($book['quantity'] <= 0): ?>
                                <span style="color: red; font-weight: bold;">Hết sách</span>
                            <?php else: ?>
                                <span style="color: green;">Còn <?php echo htmlspecialchars($book['quantity']); ?> cuốn</span>
                            <?php endif; ?>
                        </td>
                        <td class="action-buttons">
                            <a href="index.php?action=book_detail&id=<?php echo $book['id']; ?>">Xem chi tiết</a>
                            <?php if ($book['is_available']): ?>
                                <a href="index.php?action=add_to_cart&book_id=<?php echo $book['id']; ?>" class="btn btn-primary" style="margin-left: 8px;">Thêm vào giỏ mượn</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="7">Không tìm thấy sách nào.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <!-- Pagination -->
    <div class="pagination" style="margin-top: 20px; text-align: center;">
        <?php if (isset($totalPages) && $totalPages > 1): ?>
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <a href="index.php?action=home&page=<?php echo $i; ?><?php echo isset($search) ? '&search=' . urlencode($search) : ''; ?><?php echo isset($category_id) ? '&category_id=' . $category_id : ''; ?><?php echo isset($sort) ? '&sort=' . urlencode($sort) : ''; ?>" style="padding: 8px 12px; margin: 0 2px; border: 1px solid #ddd; <?php echo ($i == $page) ? 'background-color: #4CAFEF; color: white;' : ''; ?>">
                    <?php echo $i; ?>
                </a>
            <?php endfor; ?>
        <?php endif; ?>
    </div>
</div>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>