<?php require_once __DIR__ . '/../partials/header.php'; ?>

<div class="content-card">
    <div class="content-header">
        <h2>Danh sách Danh mục</h2>
        <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'manager'): ?>
        <a href="index.php?action=category_create" class="btn btn-primary">Thêm danh mục mới</a>
        <?php endif; ?>
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
                <!-- <th>ID</th> -->
                <th>Tên danh mục</th>
                <th>Mô tả</th>
                <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'manager'): ?>
                <th>Hành động</th>
                <?php endif; ?>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($categories)): ?>
                <?php foreach ($categories as $category): ?>
                    <tr>
                        <!-- <td><?php echo $category['id']; ?></td> -->
                        <td><?php echo htmlspecialchars($category['name']); ?></td>
                        <td><?php echo htmlspecialchars($category['description']); ?></td>
                        <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'manager'): ?>
                        <td class="action-buttons">
                            <a href="index.php?action=category_edit&id=<?php echo $category['id']; ?>">Sửa</a>
                            <a href="index.php?action=category_delete&id=<?php echo $category['id']; ?>" onclick="return confirm('Bạn có chắc chắn muốn xóa?');" style="color: red;">Xóa</a>
                        </td>
                        <?php endif; ?>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4">Không có danh mục nào.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

</div>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>