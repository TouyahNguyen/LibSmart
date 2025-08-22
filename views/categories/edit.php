<?php require_once __DIR__ . '/../partials/header.php'; ?>

<div class="content-card">
    <div class="content-header">
        <h2>Chỉnh sửa Danh mục</h2>
    </div>

    <?php if (!empty($error)):
        echo "<p style='color: red; margin-bottom: 15px;'>" . htmlspecialchars($error) . "</p>";
    endif; ?>

    <?php if (isset($_SESSION['success_message'])): ?>
        <p style="color: green; margin-bottom: 15px;"><?php echo htmlspecialchars($_SESSION['success_message']); unset($_SESSION['success_message']); ?></p>
    <?php endif; ?>

    <form action="index.php?action=category_edit&id=<?php echo htmlspecialchars($category['id']); ?>" method="post">
        <div class="form-group">
            <label for="name">Tên danh mục</label>
            <input type="text" id="name" name="name" class="form-control" value="<?php echo htmlspecialchars($category['name']); ?>" required>
        </div>
        <div class="form-group">
            <label for="description">Mô tả</label>
            <textarea id="description" name="description" class="form-control" rows="4"><?php echo htmlspecialchars($category['description']); ?></textarea>
        </div>

        <button type="submit" class="btn btn-primary">Cập nhật</button>
        <a href="index.php?action=categories" class="btn btn-secondary" style="margin-left: 10px;">Hủy</a>
    </form>

    <div class="mt-3">
        <a href="index.php?action=category_edit&id=<?php echo $category['id']; ?>">Sửa</a>
        <a href="index.php?action=category_delete&id=<?php echo $category['id']; ?>" onclick="return confirm('Bạn có chắc chắn muốn xóa?');" style="color: red;">Xóa</a>
    </div>

</div>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>