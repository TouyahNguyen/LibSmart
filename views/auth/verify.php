<?php if (session_status() === PHP_SESSION_NONE) { session_start(); } ?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Xác minh Email - LibSmart</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../public/style.css">
</head>
<body>

    <div class="login-container">
        <div class="login-box">
            <div class="logo">LibSmart</div>
            <h3 style="margin-bottom: 20px; font-weight: 500;">Xác minh tài khoản</h3>

            <?php if (isset($success) && $success): ?>
                <p style="color: green;">Xác minh thành công! Bạn có thể đăng nhập ngay bây giờ.</p>
                <a href="../public/index.php?action=login" class="btn btn-primary" style="margin-top: 20px;">Đi đến trang Đăng nhập</a>
            <?php else: ?>
                <?php if (!empty($error)): ?>
                    <p style="color: red; margin-bottom: 15px;"><?php echo htmlspecialchars($error); ?></p>
                <?php else: ?>
                    <p>Một mã xác minh đã được gửi đến email của bạn. Vui lòng nhập mã vào ô bên dưới.</p>
                <?php endif; ?>

                <form action="../public/index.php?action=verify" method="post" style="margin-top: 20px;">
                    <div class="form-group" style="text-align: left;">
                        <label for="code">Mã xác minh</label>
                        <input type="text" id="code" name="code" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Xác minh</button>
                </form>
            <?php endif; ?>
        </div>
    </div>

</body>
</html>