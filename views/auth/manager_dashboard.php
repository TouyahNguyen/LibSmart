<?php require_once __DIR__ . '/../partials/header.php'; ?>

<div class="content-card" style="max-width: 900px; margin: 0 auto; box-shadow: 0 2px 12px rgba(76,175,239,0.08); border-radius: 16px; padding: 32px 40px; background: #fff;">
    <div class="content-header" style="display: flex; align-items: center; gap: 18px; margin-bottom: 18px;">
        <h2 style="color: #4CAFEF; font-size: 2.2rem; font-weight: 700; margin: 0;">Chào mừng, Quản lý!</h2>
    </div>
    <p style="font-size: 1.1rem; color: #333; margin-bottom: 18px;">Đây là trang tổng quan dành cho quản lý. Bạn có thể sử dụng các chức năng ở thanh bên trái để quản lý hệ thống.</p>
    <div style="background: #F6FBFF; border-radius: 10px; padding: 18px 24px; margin-bottom: 24px;">
        <h3 style="color: #4CAFEF; font-size: 1.3rem; margin-bottom: 10px;">Các chức năng chính:</h3>
        <ul style="font-size: 1.08rem; color: #444; line-height: 1.7; margin-left: 18px;">
            <li><strong>Quản lý sách:</strong> Thêm, sửa, xóa, tìm kiếm, sắp xếp các sách trong thư viện.</li>
            <li><strong>Quản lý danh mục:</strong> Tổ chức sách theo các danh mục.</li>
            <li><strong>Quản lý mượn trả:</strong> Xem, duyệt và quản lý các yêu cầu mượn/trả sách từ người dùng.</li>
            <li><strong>Quản lý phiếu phạt:</strong> Tạo, sửa, xóa, liên kết phiếu phạt với phiếu mượn, quản lý trạng thái thanh toán.</li>
            <li><strong>Quản lý tài khoản người dùng:</strong> Xem thông tin, thống kê tổng số tài khoản manager/người dùng, xóa tài khoản, kiểm soát quyền truy cập.</li>
            <li><strong>Chatbot AI:</strong> Hỗ trợ tra cứu thông tin sách, danh mục, hướng dẫn sử dụng thư viện, trả lời tự động cho người dùng.</li>
        </ul>
    </div>
    <div style="margin-top: 18px; text-align: center;">
        <a href="index.php?action=books" class="btn btn-primary" style="font-size: 1.1rem; padding: 12px 32px; border-radius: 8px; background: #4CAFEF; color: #fff; font-weight: 600; box-shadow: 0 2px 8px #4CAFEF22;">Quản lý sách</a>
    </div>
</div>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>