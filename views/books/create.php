<?php require_once __DIR__ . '/../partials/header.php'; ?>

<div class="content-card">
    <div class="content-header">
        <h2>Thêm Sách mới</h2>
    </div>

    <?php if (!empty($error)):
        echo "<p style='color: red; margin-bottom: 15px;'>" . htmlspecialchars($error) . "</p>";
    endif; ?>

    <?php if (isset($_SESSION['success_message'])): ?>
        <p style="color: green; margin-bottom: 15px;"><?php echo htmlspecialchars($_SESSION['success_message']); unset($_SESSION['success_message']); ?></p>
    <?php endif; ?>

    <form action="index.php?action=book_create" method="post" enctype="multipart/form-data">
        <div class="form-group">
            <label for="title">Tên sách</label>
            <input type="text" id="title" name="title" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="author">Tác giả</label>
            <input type="text" id="author" name="author" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="category_id">Danh mục</label>
            <select id="category_id" name="category_id" class="form-control">
                <option value="">Chọn danh mục</option>
                <?php foreach ($categories as $category):
                    echo "<option value='" . htmlspecialchars($category['id']) . "'>" . htmlspecialchars($category['name']) . "</option>";
                endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="published_year">Năm xuất bản</label>
            <input type="number" id="published_year" name="published_year" class="form-control" min="1000" max="<?php echo date('Y'); ?>">
        </div>
        <div class="form-group">
            <label for="description">Mô tả</label>
            <textarea id="description" name="description" class="form-control" rows="5"></textarea>
        </div>
        <div class="form-group">
            <label for="image">Ảnh bìa</label>
            <input type="file" id="image" name="image" class="form-control">
        </div>
        <div class="form-group">
            <label for="quantity">Số lượng sách</label>
            <input type="number" id="quantity" name="quantity" class="form-control" min="1" value="1" required>
        </div>

        <button type="submit" class="btn btn-primary">Lưu sách</button>
        <a href="index.php?action=books" class="btn btn-secondary" style="margin-left: 10px;">Hủy</a>
    </form>

</div>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>