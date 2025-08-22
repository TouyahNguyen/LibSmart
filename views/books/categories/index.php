<?php
// ...existing code...

// Assuming you have a way to get the current page and total pages
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$totalPages = ceil($totalCategories / 10); // Assuming $totalCategories is the total number of categories

// Fetch categories for the current page
$categories = getCategories($page, 10); // Assuming this function exists and works as intended
?>

<table>
    <!-- ...existing table headers... -->
    <?php if (!empty($categories)): ?>
        <?php foreach ($categories as $category): ?>
            <tr>
                <!-- ...existing code for category row... -->
            </tr>
        <?php endforeach; ?>
    <?php else: ?>
        <tr>
            <td colspan="4">Không tìm thấy danh mục nào.</td>
        </tr>
    <?php endif; ?>
</table>

<!-- Pagination -->
<div class="pagination" style="margin-top: 20px; text-align: center;">
    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
        <a href="index.php?action=categories&page=<?php echo $i; ?>" style="padding: 8px 12px; margin: 0 2px; border: 1px solid #ddd; <?php echo ($i == $page) ? 'background-color: #4CAFEF; color: white;' : ''; ?>">
            <?php echo $i; ?>
        </a>
    <?php endfor; ?>
</div>

<?php
// ...existing code...
?>