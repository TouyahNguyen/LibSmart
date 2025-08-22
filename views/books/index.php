<?php require_once __DIR__ . '/../partials/header.php'; ?>

<div class="content-card">
    <div class="content-header">
        <h2>Danh sách Sách</h2>
        <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'manager'): ?>
            <a href="index.php?action=book_create" class="btn btn-primary" style="margin-bottom:10px;">Thêm sách mới</a>
        <?php endif; ?>
    </div>

    <?php if (isset($_SESSION['success_message'])): ?>
        <p style="color: green; margin-bottom: 15px;"><?php echo htmlspecialchars($_SESSION['success_message']); unset($_SESSION['success_message']); ?></p>
    <?php endif; ?>

    <?php if (isset($_SESSION['error_message'])): ?>
        <p style="color: red; margin-bottom: 15px;"><?php echo htmlspecialchars($_SESSION['error_message']); unset($_SESSION['error_message']); ?></p>
    <?php endif; ?>

    <!-- Search, Filter, and Sort Form -->
    <form method="get" action="index.php" class="filter-form" style="margin-bottom: 20px; display: flex; gap: 15px; align-items: center;">
        <input type="hidden" name="action" value="books">
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
                            <?php if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'manager'): ?>
                                <a href="index.php?action=book_detail&id=<?php echo $book['id']; ?>" class="btn btn-info" style="margin-right: 8px; background-color: #e3f2fd; color: #1976d2; border: 1px solid #90caf9;">Xem chi tiết</a>
                            <?php endif; ?>
                            <?php if ($book['is_available']): ?>
                                <?php if (isset($_SESSION['user_id']) && (!isset($_SESSION['role']) || $_SESSION['role'] !== 'manager')): ?>
                                    <a href="index.php?action=add_to_cart&book_id=<?php echo $book['id']; ?>" class="btn btn-primary" style="margin-left: 0;">Thêm vào giỏ mượn</a>
                                <?php elseif (!isset($_SESSION['user_id'])): ?>
                                    <a href="index.php?action=login" class="btn btn-primary" style="margin-left: 0;">Đăng nhập để mượn</a>
                                <?php endif; ?>
                            <?php endif; ?>
                            <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'manager'): ?>
                                <a href="index.php?action=book_edit&id=<?php echo $book['id']; ?>" class="btn btn-warning" style="margin-left: 8px; background-color: #ffecb3; color: #b26a00; border: 1px solid #ffe082;">Sửa</a>
                                <a href="index.php?action=book_delete&id=<?php echo $book['id']; ?>" class="btn btn-danger" style="margin-left: 8px; background-color: #ffcdd2; color: #c62828; border: 1px solid #ef9a9a;" onclick="return confirm('Bạn có chắc chắn muốn xóa sách này?');">Xóa</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="7" style="text-align:center; font-size:1.2rem; color:#4CAFEF; font-weight:500;">Không tìm thấy sách nào trên trang này.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <!-- Pagination -->
    <div class="pagination" style="margin-top: 20px; text-align: center;">
        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <a href="index.php?action=books&page=<?php echo $i; ?>" style="padding: 8px 12px; margin: 0 2px; border: 1px solid #ddd; <?php echo ($i == $page) ? 'background-color: #4CAFEF; color: white;' : ''; ?>">
                <?php echo $i; ?>
            </a>
        <?php endfor; ?>
    </div>
</div>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>