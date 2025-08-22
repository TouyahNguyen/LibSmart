<?php require_once __DIR__ . '/../partials/header.php'; ?>
<div class="content-card" style="max-width:900px;margin:40px auto;">
    <h2>Quản lý tài khoản người dùng</h2>
    <?php 
        $users = \User::all();
        $adminCount = 0;
        $userCount = 0;
        foreach ($users as $u) {
            if ($u['role'] === 'manager') $adminCount++;
            if ($u['role'] === 'reader') $userCount++;
        }
    ?>
    <div style="display:flex;gap:30px;margin-bottom:20px;">
        <div style="background:#e3f2fd;padding:18px 32px;border-radius:8px;box-shadow:0 2px 8px #eee;">
            <span style="font-size:18px;font-weight:500;color:#1976d2;">Tổng tài khoản manager:</span>
            <span style="font-size:22px;font-weight:bold;color:#1976d2;"> <?= $adminCount ?> </span>
        </div>
        <div style="background:#fce4ec;padding:18px 32px;border-radius:8px;box-shadow:0 2px 8px #eee;">
            <span style="font-size:18px;font-weight:500;color:#d81b60;">Tổng tài khoản người dùng:</span>
            <span style="font-size:22px;font-weight:bold;color:#d81b60;"> <?= $userCount ?> </span>
        </div>
    </div>
    <table class="table" style="width:100%;background:#fff;border-radius:8px;box-shadow:0 2px 8px #eee;">
        <thead>
            <tr>
                <th>Tên đăng nhập</th>
                <th>Email</th>
                <th>Họ tên</th>
                <th>Quyền</th>
                <th>Trạng thái</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($users as $u): ?>
            <tr>
                <td><?= htmlspecialchars($u['username']) ?></td>
                <td><?= htmlspecialchars($u['email']) ?></td>
                <td><?= htmlspecialchars($u['fullname']) ?></td>
                <td><?= htmlspecialchars($u['role']) ?></td>
                <td><?= $u['verified'] ? 'Đã xác thực' : 'Chưa xác thực' ?></td>
                <td>
                    <?php if ($u['role'] !== 'staff'): ?>
                    <a href="index.php?action=user_delete&id=<?= $u['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Xóa tài khoản này?');">Xóa</a>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php require_once __DIR__ . '/../partials/footer.php'; ?>
