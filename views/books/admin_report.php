<?php
// views/books/admin_report.php
require_once __DIR__ . '/../partials/header.php';
?>
<div class="content-card">
    <div class="content-header">
        <h2>Báo cáo thống kê hệ thống</h2>
    </div>
    <form method="get" action="index.php" style="margin-bottom: 24px; text-align: center;">
        <input type="hidden" name="action" value="admin_report">
        <label for="report_date" style="font-size: 1.1em; margin-right: 8px;">Chọn ngày báo cáo:</label>
        <input type="date" id="report_date" name="report_date" value="<?php echo htmlspecialchars($_GET['report_date'] ?? date('Y-m-d')); ?>" style="font-size: 1.1em; padding: 4px 8px;">
        <button type="submit" class="btn" style="margin-left: 8px;">Xem báo cáo</button>
    </form>
    <div style="width:100%; max-width:900px; margin:0 auto 40px auto; background:#f8f8f8; border-radius:12px; box-shadow:0 2px 8px #eee; padding:20px;">
        <h3 style="text-align:center; color:#2196F3;">Thống kê theo ngày: <?php echo htmlspecialchars($_GET['report_date'] ?? date('Y-m-d')); ?></h3>
        <canvas id="dateChart"></canvas>
        <ul style="font-size: 1.2em; margin-top: 24px;">
            <li><b>Số lượng sách mới:</b> <?php echo htmlspecialchars($books_on_date); ?></li>
            <li><b>Số lượng phiếu mượn:</b> <?php echo htmlspecialchars($borrows_on_date); ?></li>
            <li><b>Phiếu phạt đã thanh toán:</b> <?php echo htmlspecialchars($paid_fines_on_date); ?></li>
            <li><b>Phiếu phạt chưa thanh toán:</b> <?php echo htmlspecialchars($unpaid_fines_on_date); ?></li>
        </ul>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Biểu đồ thống kê theo ngày
        const booksOnDate = <?php echo json_encode($books_on_date); ?>;
        const borrowsOnDate = <?php echo json_encode($borrows_on_date); ?>;
        const paidFinesOnDate = <?php echo json_encode($paid_fines_on_date); ?>;
        const unpaidFinesOnDate = <?php echo json_encode($unpaid_fines_on_date); ?>;
        new Chart(document.getElementById('dateChart'), {
            type: 'bar',
            data: {
                labels: ['Sách mới', 'Phiếu mượn', 'Phiếu phạt đã thanh toán', 'Phiếu phạt chưa thanh toán'],
                datasets: [{
                    label: 'Thống kê theo ngày',
                    data: [booksOnDate, borrowsOnDate, paidFinesOnDate, unpaidFinesOnDate],
                    backgroundColor: ['#4CAF50', '#FF9800', '#009688', '#F44336'],
                    borderRadius: 8
                }]
            },
            options: {
                plugins: {
                    legend: { display: false },
                    title: { display: true, text: 'Thống kê theo ngày' }
                },
                responsive: true,
                maintainAspectRatio: true,
                scales: {
                    x: { title: { display: true, text: 'Loại thống kê' }, ticks: { font: { size: 12 } } },
                    y: { title: { display: true, text: 'Số lượng' }, beginAtZero: true, ticks: { font: { size: 12 } } }
                }
            }
        });
    </script>
    <div style="width:100%; max-width:900px; margin:0 auto 40px auto; background:#f8f8f8; border-radius:12px; box-shadow:0 2px 8px #eee; padding:20px;">
        <h3 style="text-align:center; color:#2196F3;">Thống kê tổng hợp</h3>
        <canvas id="summaryChart"></canvas>
        <ul style="font-size: 1.2em; margin-top: 24px;">
            <li><b>Số lượng người dùng:</b> <?php echo htmlspecialchars($user_count); ?></li>
            <li><b>Số lượng sách:</b> <?php echo htmlspecialchars($book_count); ?></li>
            <li><b>Tổng số phiếu mượn:</b> <?php echo htmlspecialchars($borrow_count); ?></li>
            <li><b>Phiếu mượn đã trả:</b> <?php echo htmlspecialchars($returned_count); ?></li>
            <li><b>Phiếu mượn chưa trả:</b> <?php echo htmlspecialchars($not_returned_count); ?></li>
            <li><b>Tổng số phiếu phạt:</b> <?php echo htmlspecialchars($fine_count); ?></li>
            <li><b>Phiếu phạt chưa thanh toán:</b> <?php echo htmlspecialchars($unpaid_fine_count); ?></li>
            <li><b>Phiếu phạt đã thanh toán:</b> <?php echo htmlspecialchars($paid_fine_count); ?></li>
        </ul>
    </div>
    <script>
        // Biểu đồ thống kê tổng hợp
        const summaryChart = new Chart(document.getElementById('summaryChart'), {
            type: 'bar',
            data: {
                labels: ['Người dùng', 'Sách', 'Phiếu mượn', 'Phiếu mượn đã trả', 'Phiếu mượn chưa trả', 'Phiếu phạt', 'Phiếu phạt chưa thanh toán', 'Phiếu phạt đã thanh toán'],
                datasets: [{
                    label: 'Thống kê tổng hợp',
                    data: [<?php echo implode(',', [$user_count, $book_count, $borrow_count, $returned_count, $not_returned_count, $fine_count, $unpaid_fine_count, $paid_fine_count]); ?>],
                    backgroundColor: ['#2196F3', '#4CAF50', '#FF9800', '#FF5722', '#9C27B0', '#009688', '#795548', '#607D8B'],
                    borderRadius: 8
                }]
            },
            options: {
                plugins: {
                    legend: { display: false },
                    title: { display: true, text: 'Thống kê tổng hợp' }
                },
                responsive: true,
                maintainAspectRatio: true,
                scales: {
                    x: { title: { display: true, text: 'Loại thống kê' }, ticks: { font: { size: 12 } } },
                    y: { title: { display: true, text: 'Số lượng' }, beginAtZero: true, ticks: { font: { size: 12 } } }
                }
            }
        });
    </script>
    <?php require_once __DIR__ . '/../partials/footer.php'; ?>
