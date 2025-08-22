<?php require_once __DIR__ . '/../partials/header.php'; ?>
<div class="content-card" style="max-width: 600px; margin: 40px auto; text-align: center;">
    <h2><?php echo htmlspecialchars($title ?? 'Thông báo'); ?></h2>
    <p style="font-size: 1.2em; margin: 30px 0; color: #333;">
        <?php echo nl2br(htmlspecialchars($message ?? '')); ?>
    </p>
    <a href="index.php?action=admin_borrows" class="btn btn-primary">Quay lại quản lý mượn trả</a>
</div>
<?php require_once __DIR__ . '/../partials/footer.php'; ?>
