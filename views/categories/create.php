<?php require_once __DIR__ . '/../partials/header.php'; ?>

<div class="content-card">
    <div class="content-header">
        <h2>Thêm Danh mục mới</h2>
    </div>

    <?php if (!empty($error)):
        echo "<p style='color: red; margin-bottom: 15px;'>" . htmlspecialchars($error) . "</p>";
    endif; ?>

    <?php if (isset($_SESSION['success_message'])): ?>
        <p style="color: green; margin-bottom: 15px;"><?php echo htmlspecialchars($_SESSION['success_message']); unset($_SESSION['success_message']); ?></p>
    <?php endif; ?>

    <form action="index.php?action=category_create" method="post">
        <div class="form-group">
            <label for="name">Tên danh mục</label>
            <input type="text" id="name" name="name" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="description">Mô tả</label>
            <textarea id="description" name="description" class="form-control" rows="4"></textarea>
        </div>

        <button type="submit" class="btn btn-primary">Lưu Danh mục</button>
        <a href="index.php?action=categories" class="btn btn-secondary" style="margin-left: 10px;">Hủy</a>
    </form>

</div>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>