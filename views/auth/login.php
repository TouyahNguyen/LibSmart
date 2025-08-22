<?php if (session_status() === PHP_SESSION_NONE) { session_start(); } ?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập - LibSmart</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../public/style.css">
</head>
<body>

    <div class="login-container">
        <div class="login-box">
            <div class="logo">LibSmart</div>
            
            <?php if (!empty($error)): ?>
                <p style="color: red; margin-bottom: 15px;"><?php echo htmlspecialchars($error); ?></p>
            <?php endif; ?>

            <form action="../public/index.php?action=login" method="post">
                <div class="form-group">
                    <input type="text" name="username" class="form-control" placeholder="Tên đăng nhập" required>
                </div>
                <div class="form-group">
                    <input type="password" name="password" class="form-control" placeholder="Mật khẩu" required>
                </div>
                <button type="submit" class="btn btn-primary">Đăng nhập</button>
            </form>

            <p style="margin-top: 20px;">
                <a href="../public/index.php?action=forgot_password">Quên mật khẩu?</a>
            </p>
            <p style="margin-top: 10px;">
                Chưa có tài khoản? <a href="../public/index.php?action=register">Đăng ký ngay</a>
            </p>
            <p style="margin-top: 10px;">
                <a href="index.php?action=about" class="back-link" style="color: #4CAFEF; font-weight: 500; text-decoration: underline; margin-bottom: 18px; display: inline-block;">Quay về trang chủ</a>
            </p>
        </div>
    </div>

</body>
</html>