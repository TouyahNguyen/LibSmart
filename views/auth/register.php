<?php if (session_status() === PHP_SESSION_NONE) { session_start(); } ?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng ký - LibSmart</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../public/style.css">
</head>
<body>

    <div class="login-container">
        <div class="login-box" style="max-width: 500px;">
            <div class="logo">LibSmart</div>
            <h3 style="margin-bottom: 20px; font-weight: 500;">Tạo tài khoản mới</h3>
            
            <?php if (!empty($error)): ?>
                <p style="color: red; margin-bottom: 15px;"><?php echo htmlspecialchars($error); ?></p>
            <?php endif; ?>

            <form action="../public/index.php?action=register" method="post">
                <div class="form-group" style="text-align: left;">
                    <label for="username">Tên đăng nhập</label>
                    <input type="text" id="username" name="username" class="form-control" required>
                </div>
                <div class="form-group" style="text-align: left;">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" class="form-control" required>
                </div>
                <div class="form-group" style="text-align: left;">
                    <label for="password">Mật khẩu</label>
                    <input type="password" id="password" name="password" class="form-control" required>
                </div>
                 <div class="form-group" style="text-align: left;">
                    <label for="fullname">Họ và tên</label>
                    <input type="text" id="fullname" name="fullname" class="form-control">
                </div>
                <button type="submit" class="btn btn-primary">Đăng ký</button>
            </form>

            <p style="margin-top: 20px;">
                Đã có tài khoản? <a href="../public/index.php?action=login">Đăng nhập</a>
            </p>
            <a href="index.php?action=about" class="back-link" style="color: #4CAFEF; font-weight: 500; text-decoration: underline; margin-bottom: 18px; display: inline-block;">Quay về trang chủ</a>
        </div>
    </div>

</body>
</html>