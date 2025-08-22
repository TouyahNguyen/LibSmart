<?php require_once __DIR__ . '/../partials/header.php'; ?>

<div class="content-card">
    <div class="content-header">
        <h2>Chỉnh sửa Sách</h2>
    </div>

    <?php if (!empty($error)):
        echo "<p style='color: red; margin-bottom: 15px;'>" . htmlspecialchars($error) . "</p>";
    endif; ?>

    <?php if (isset($_SESSION['success_message'])): ?>
        <p style="color: green; margin-bottom: 15px;"><?php echo htmlspecialchars($_SESSION['success_message']); unset($_SESSION['success_message']); ?></p>
    <?php endif; ?>

    <form action="index.php?action=book_edit&id=<?php echo htmlspecialchars($book['id']); ?>" method="post" enctype="multipart/form-data">
        <div class="form-group">
            <label for="title">Tên sách</label>
            <input type="text" id="title" name="title" class="form-control" value="<?php echo htmlspecialchars($book['title']); ?>" required>
        </div>
        <div class="form-group">
            <label for="author">Tác giả</label>
            <input type="text" id="author" name="author" class="form-control" value="<?php echo htmlspecialchars($book['author']); ?>" required>
        </div>
        <div class="form-group">
            <label for="category_id">Danh mục</label>
            <select id="category_id" name="category_id" class="form-control">
                <option value="">Chọn danh mục</option>
                <?php foreach ($categories as $category):
                    $selected = ($category['id'] == $book['category_id']) ? 'selected' : '';
                    echo "<option value='" . htmlspecialchars($category['id']) . "' $selected>" . htmlspecialchars($category['name']) . "</option>";
                endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="published_year">Năm xuất bản</label>
            <input type="number" id="published_year" name="published_year" class="form-control" min="1000" max="<?php echo date('Y'); ?>" value="<?php echo htmlspecialchars($book['published_year']); ?>">
        </div>
        <div class="form-group">
            <label for="description">Mô tả</label>
            <textarea id="description" name="description" class="form-control" rows="5"><?php echo htmlspecialchars($book['description']); ?></textarea>
        </div>
        <div class="form-group">
            <label for="image">Ảnh bìa mới (để trống nếu không muốn thay đổi)</label>
            <input type="file" id="image" name="image" class="form-control">
            <?php if ($book['image']): ?>
                <img src="../public/uploads/<?php echo htmlspecialchars($book['image']); ?>" alt="Ảnh bìa hiện tại" width="100" style="margin-top: 10px; border-radius: 4px;">
            <?php endif; ?>
        </div>
        <div class="form-group">
            <label for="quantity">Số lượng sách</label>
            <input type="number" id="quantity" name="quantity" class="form-control" min="1" value="<?php echo isset($book['quantity']) ? htmlspecialchars($book['quantity']) : 1; ?>" required>
        </div>

        <button type="submit" class="btn btn-primary">Cập nhật</button>
        <a href="index.php?action=book_edit&id=<?php echo $book['id']; ?>" class="btn btn-secondary" style="margin-left: 10px;">Hủy</a>
        <a href="index.php?action=book_delete&id=<?php echo $book['id']; ?>" onclick="return confirm('Bạn có chắc chắn muốn xóa?');" class="btn btn-danger" style="margin-left: 10px;">Xóa</a>
    </form>

</div>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>