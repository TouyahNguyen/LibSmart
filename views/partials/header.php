<?php if (session_status() === PHP_SESSION_NONE) { session_start(); } ?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LibSmart - Quản lý thư viện</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../public/style.css">
</head>
<body>

    <!-- Header -->
    <header class="header">
        <a href="#" class="logo">LibSmart</a>
        <div class="search-bar">
            <form method="get" action="index.php" style="width:100%;">
                <input type="hidden" name="action" value="books">
                <input type="text" name="search" placeholder="Tìm kiếm sách, tác giả..." value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>" style="width: 100%; padding: 8px; border-radius: 20px; border: 1px solid #ccc;">
            </form>
        </div>
        <div class="header-icons">
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="index.php?action=notifications">&#128276;</a>
                <a href="index.php?action=profile">&#128100;</a>
            <?php endif; ?>
        </div>
    </header>

    <div class="main-wrapper">
        <!-- Sidebar -->
        <aside class="sidebar">
            <ul class="sidebar-menu">
                <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'manager'): ?>
                    <li><a href="index.php?action=manager_dashboard">Trang chủ</a></li>
                    <li><a href="index.php?action=books">Quản lý sách</a></li>
                    <li><a href="index.php?action=categories">Quản lý danh mục</a></li>
                    <li><a href="index.php?action=admin_borrows">Quản lý mượn trả</a></li>
                    <li><a href="index.php?action=admin_fines">Quản lý phiếu phạt</a></li>
                    <li><a href="index.php?action=admin_report">Báo cáo thống kê</a></li>
                    <li><a href="index.php?action=user_manage">Quản lý tài khoản</a></li>
                    <li><a href="index.php?action=profile">Tài khoản (admin)</a></li>
                    <li><a href="index.php?action=chatbot">Chatbot AI</a></li>
                    <li><a href="index.php?action=logout">Đăng xuất</a></li>
                <?php elseif (isset($_SESSION['role']) && $_SESSION['role'] === 'reader'): ?>
                    <li><a href="index.php?action=home">Trang chủ</a></li>
                    <li><a href="index.php?action=books">Danh sách sách</a></li>
                    <li><a href="index.php?action=cart">Giỏ sách</a></li>
                    <li><a href="index.php?action=my_borrows">Phiếu mượn của tôi</a></li>
                    <li><a href="index.php?action=my_fines">Phiếu phạt của tôi</a></li>
                    <li><a href="index.php?action=profile">Tài khoản (khách)</a></li>
                    <li><a href="index.php?action=logout">Đăng xuất</a></li>
                    <li><a href="index.php?action=chatbot">Chatbot AI</a></li>
                <?php else: ?>
                    <li><a href="index.php?action=about">Giới thiệu LibSmart</a></li>
                    <li><a href="index.php?action=books">Danh sách sách</a></li>
                    <li><a href="index.php?action=login">Đăng nhập</a></li>
                    <li><a href="index.php?action=register">Đăng ký</a></li>
                <?php endif; ?>
            </ul>
        </aside>

        <!-- Main Content -->
        <main class="main-content">