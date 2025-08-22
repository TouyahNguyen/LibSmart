<?php
// controllers/AuthController.php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../config/mailer.php';

class AuthController {
    public static function register() {
        $error = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim($_POST['username']);
            $email = trim($_POST['email']);
            $password = $_POST['password'];
            $fullname = trim($_POST['fullname'] ?? '');
            $dob = !empty($_POST['dob']) ? trim($_POST['dob']) : null;
            $address = trim($_POST['address'] ?? '');
            $role = 'reader';
            // Sinh mã xác minh 6 ký tự
            $verify_code = strtoupper(substr(bin2hex(random_bytes(3)), 0, 6));
            if (User::findByUsername($username)) {
                $error = 'Tên đăng nhập đã tồn tại!';
            } elseif (User::findByEmail($email)) {
                $error = 'Email đã được sử dụng!';
            } else {
                if (User::create($username, $email, $password, $role, $verify_code, $fullname, $dob, $address)) {
                    // Gửi mã xác minh qua email thực sự
                    if (send_verification_email($email, $verify_code)) {
                        $_SESSION['pending_email'] = $email;
                        $error = 'Đăng ký thành công! Vui lòng kiểm tra email để nhập mã xác minh.';
                        header('Location: ../public/index.php?action=verify');
                        exit();
                    } else {
                        $error = 'Không gửi được email xác minh. Vui lòng thử lại.';
                    }
                } else {
                    $error = 'Lỗi đăng ký!';
                }
            }
        }
        include __DIR__ . '/../views/auth/register.php';
    }

    public static function verify() {
        $success = false;
        $error = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_SESSION['pending_email'] ?? '';
            $code = trim($_POST['code']);
            if ($email && User::verifyEmail($email, $code)) {
                $success = true;
                unset($_SESSION['pending_email']);
            } else {
                $error = 'Mã xác minh không đúng hoặc đã hết hạn!';
            }
        }
        include __DIR__ . '/../views/auth/verify.php';
    }

    public static function login() {
        $error = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim($_POST['username']);
            $password = $_POST['password'];
            $user = User::findByUsername($username);
            if ($user && password_verify($password, $user['password'])) {
                if (!$user['verified']) {
                    $error = 'Tài khoản chưa xác minh email!';
                } else {
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['username'] = $user['username'];
                    $_SESSION['role'] = $user['role'];
                    // Chuyển hướng đúng dashboard
                    if ($user['role'] === 'manager') {
                        header('Location: ../public/index.php?action=manager_dashboard');
                    } else {
                        header('Location: ../public/index.php?action=reader_dashboard');
                    }
                    exit();
                }
            } else {
                $error = 'Sai tài khoản hoặc mật khẩu!';
            }
        }
        include __DIR__ . '/../views/auth/login.php';
    }
    public static function logout() {
        session_unset();
        session_destroy();
        header('Location: ../public/index.php?action=login');
        exit();
    }

    public static function manager_dashboard() {
        include __DIR__ . '/../views/auth/manager_dashboard.php';
    }

    public static function reader_dashboard() {
        require_once __DIR__ . '/../models/Book.php';
        require_once __DIR__ . '/../models/Category.php';
        $search = isset($_GET['search']) ? trim($_GET['search']) : '';
        $category_id = isset($_GET['category_id']) && is_numeric($_GET['category_id']) ? intval($_GET['category_id']) : null;
        $sort = isset($_GET['sort']) ? $_GET['sort'] : '';
        $books = Book::getPaged(0, 100, $search, $category_id, $sort)['books'];
        $categories = Category::all();
        include __DIR__ . '/../views/auth/reader_dashboard.php';
    }
    public static function profile() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: ../public/index.php?action=login');
            exit();
        }
        // Lấy thông tin user từ DB
        $user = User::findByUsername($_SESSION['username']);
        include __DIR__ . '/../views/auth/profile.php';
    }
    public static function profile_update() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: ../public/index.php?action=login');
            exit();
        }
        $user = User::findByUsername($_SESSION['username']);
        $fullname = trim($_POST['fullname'] ?? '');
        $dob = !empty($_POST['dob']) ? trim($_POST['dob']) : null;
        $address = trim($_POST['address'] ?? '');
        // Cập nhật DB (bổ sung hàm updateProfile vào model User)
        User::updateProfile($user['id'], $fullname, $dob, $address);
        // Cập nhật session nếu cần
        $_SESSION['fullname'] = $fullname;
        $_SESSION['dob'] = $dob;
        $_SESSION['address'] = $address;
        header('Location: ../public/index.php?action=profile');
        exit();
    }

    public static function change_password() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: ../public/index.php?action=login');
            exit();
        }
        $msg = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $current_password = $_POST['current_password'] ?? '';
            $new_password = $_POST['new_password'] ?? '';
            $confirm_password = $_POST['confirm_password'] ?? '';

            if (empty($current_password) || empty($new_password) || empty($confirm_password)) {
                $msg = 'Vui lòng điền đầy đủ các trường.';
            } elseif ($new_password !== $confirm_password) {
                $msg = 'Mật khẩu mới và xác nhận mật khẩu không khớp.';
            } elseif (strlen($new_password) < 6) {
                $msg = 'Mật khẩu mới phải có ít nhất 6 ký tự.';
            } else {
                require_once __DIR__ . '/../models/User.php';
                if (User::updatePassword($_SESSION['user_id'], $current_password, $new_password)) {
                    $msg = 'Đổi mật khẩu thành công!';
                } else {
                    $msg = 'Mật khẩu hiện tại không đúng.';
                }
            }
        }
        include __DIR__ . '/../views/auth/change_password.php';
    }

    public static function home() {
        require_once __DIR__ . '/../models/Book.php';
        require_once __DIR__ . '/../models/Category.php';
        // Fix: Ensure $totalPages and $page are always set
        $page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
        $limit = 10;
        $search = isset($_GET['search']) ? trim($_GET['search']) : '';
        $category_id = isset($_GET['category_id']) && is_numeric($_GET['category_id']) ? intval($_GET['category_id']) : null;
        $sort = isset($_GET['sort']) ? $_GET['sort'] : '';
        $paged = \Book::getPaged(($page-1)*$limit, $limit, $search, $category_id, $sort);
        $books = $paged['books'];
        $total = $paged['total'];
        $totalPages = ceil($total / $limit);
        $categories = \Category::all();
        // Pass $category_id, $search, $sort, $page, $totalPages to view
        include __DIR__ . '/../views/auth/home.php';
    }
    public static function forgot_password() {
        $msg = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email']);
            if (empty($email)) {
                $msg = 'Vui lòng nhập email.';
            } else {
                $user = User::findByEmail($email);
                if (!$user) {
                    $msg = 'Email không tồn tại.';
                } elseif (!$user['verified']) {
                    $msg = 'Tài khoản chưa xác minh email.';
                } else {
                    // Lấy mật khẩu thuần và gửi mail
                    $plain_password = $user['plain_password'];
                    if (empty($plain_password)) {
                         $msg = 'Không tìm thấy mật khẩu của bạn. Vui lòng liên hệ quản trị viên.';
                    } else {
                        require_once __DIR__ . '/../config/mailer.php';
                        $mail = getMailer();
                        $mail->addAddress($user['email'], $user['fullname'] ?: $user['username']);
                        $mail->Subject = 'LibSmart - Mật khẩu của bạn';
                        $mail->Body = 'Chào ' . htmlspecialchars($user['fullname'] ?: $user['username']) . ',<br><br>Mật khẩu của bạn là: <b>' . htmlspecialchars($plain_password) . '</b><br><br>Vì lý do bảo mật, chúng tôi khuyến nghị bạn nên đổi mật khẩu ngay sau khi đăng nhập.<br><br>Trân trọng,<br>Đội ngũ LibSmart';
                        
                        if ($mail->send()) {
                            $msg = 'Mật khẩu đã được gửi về email của bạn.';
                        } else {
                            $msg = 'Không gửi được email. Vui lòng thử lại.';
                        }
                    }
                }
            }
        }
        include __DIR__ . '/../views/auth/forgot_password.php';
    }
}
