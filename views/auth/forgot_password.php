<?php if (session_status() === PHP_SESSION_NONE) { session_start(); } ?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quên mật khẩu - LibSmart</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../public/style.css">
</head>
<body>

    <div class="login-container">
        <div class="login-box">
            <div class="logo">LibSmart</div>
            <h3 style="margin-bottom: 20px; font-weight: 500;">Quên mật khẩu</h3>
            
            <?php if (!empty($msg)): ?>
                <p style="margin-bottom: 15px; color: green;"><?php echo htmlspecialchars($msg); ?></p>
            <?php endif; ?>

            <form action="../public/index.php?action=forgot_password" method="post">
                <div class="form-group" style="text-align: left;">
                    <label for="email">Nhập email của bạn</label>
                    <input type="email" id="email" name="email" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary">Gửi lại mật khẩu</button>
            </form>

            <p style="margin-top: 20px;">
                <a href="../public/index.php?action=login">Quay lại Đăng nhập</a>
            </p>
            <p style="margin-top: 10px;">
                <a href="../public/index.php?action=home">Quay về trang chủ</a>
            </p>
        </div>
    </div>

</body>
</html>