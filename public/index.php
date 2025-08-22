<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
// public/index.php - Điểm vào của ứng dụng
require_once __DIR__ . '/../controllers/AuthController.php';
require_once __DIR__ . '/../controllers/CategoryController.php';
require_once __DIR__ . '/../controllers/BookController.php';
require_once __DIR__ . '/../controllers/BorrowController.php';
require_once __DIR__ . '/../controllers/UserController.php';

$action = $_GET['action'] ?? '';
if ($action === '') {
    header('Location: index.php?action=about');
    exit();
} elseif ($action === 'logout') {
    AuthController::logout();
} elseif ($action === 'register') {
    AuthController::register();
} elseif ($action === 'verify') {
    AuthController::verify();
} elseif ($action === 'login') {
    AuthController::login();
} elseif ($action === 'categories') {
    CategoryController::index();
} elseif ($action === 'category_create') {
    CategoryController::create();
} elseif ($action === 'category_edit') {
    CategoryController::edit();
} elseif ($action === 'category_delete') {
    CategoryController::delete();
} elseif ($action === 'books') {
    BookController::index();
} elseif ($action === 'book_create') {
    BookController::create();
} elseif ($action === 'book_edit') {
    BookController::edit();
} elseif ($action === 'book_delete') {
    BookController::delete();
} elseif ($action === 'book_detail') {
    BookController::detail();
} elseif ($action === 'book') {
    BookController::single();
} elseif ($action === 'profile') {
    AuthController::profile();
} elseif ($action === 'profile_update') {
    AuthController::profile_update();
} elseif ($action === 'add_to_cart') {
    BorrowController::add_to_cart();
} elseif ($action === 'remove_from_cart') {
    BorrowController::remove_from_cart();
} elseif ($action === 'cart') {
    BorrowController::cart();
} elseif ($action === 'request_borrow') {
    BorrowController::request_borrow();
} elseif ($action === 'my_borrows') {
    BorrowController::my_borrows();
} elseif ($action === 'admin_borrows') {
    BorrowController::admin_borrows();
} elseif ($action === 'update_borrow_status') {
    BorrowController::update_status();
} elseif ($action === 'borrow_detail') {
    BorrowController::borrow_detail();
} elseif ($action === 'my_fines') {
    BorrowController::my_fines();
} elseif ($action === 'forgot_password') {
    AuthController::forgot_password();
} elseif ($action === 'change_password') {
    AuthController::change_password();
} elseif ($action === 'manager_dashboard') {
    AuthController::manager_dashboard();
} elseif ($action === 'reader_dashboard') {
    AuthController::reader_dashboard();
} elseif ($action === 'borrow_delete') {
    BorrowController::borrow_delete();
} elseif ($action === 'admin_fines') {
    require_once __DIR__ . '/../models/Fine.php';
    // Lấy danh sách phiếu phạt, join với bảng users để lấy username
    global $conn;
    $sql = "SELECT f.*, u.username FROM fines f JOIN users u ON f.user_id = u.id ORDER BY f.created_at DESC";
    $result = $conn->query($sql);
    $fines = $result->fetch_all(MYSQLI_ASSOC);
    include __DIR__ . '/../views/books/admin_fines.php';
} elseif ($action === 'fine_create') {
    require_once __DIR__ . '/../models/Fine.php';
    require_once __DIR__ . '/../models/User.php';
    $error = '';
    $users = User::all();
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $user_id = intval($_POST['user_id']);
        $amount = floatval($_POST['amount']);
        $reason = trim($_POST['reason']);
        if ($user_id && $amount > 0 && $reason) {
            $result = Fine::create(null, $user_id, $amount, $reason);
            if ($result) {
                $_SESSION['success_message'] = 'Thêm phiếu phạt thành công!';
                header('Location: ../public/index.php?action=admin_fines');
                exit();
            } else {
                $error = 'Lỗi khi thêm phiếu phạt!';
            }
        } else {
            $error = 'Vui lòng nhập đầy đủ thông tin.';
        }
    }
    include __DIR__ . '/../views/books/fine_create.php';
} elseif ($action === 'fine_edit') {
    require_once __DIR__ . '/../models/Fine.php';
    require_once __DIR__ . '/../models/User.php';
    $error = '';
    $id = intval($_GET['id'] ?? 0);
    $fine = null;
    $users = User::all();
    if ($id) {
        $fine = Fine::find($id);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $user_id = intval($_POST['user_id']);
            $amount = floatval($_POST['amount']);
            $reason = trim($_POST['reason']);
            if ($user_id && $amount > 0 && $reason) {
                $result = Fine::update($id, $user_id, $amount, $reason);
                if ($result) {
                    $_SESSION['success_message'] = 'Cập nhật phiếu phạt thành công!';
                    header('Location: ../public/index.php?action=admin_fines');
                    exit();
                } else {
                    $error = 'Lỗi khi cập nhật phiếu phạt!';
                }
            } else {
                $error = 'Vui lòng nhập đầy đủ thông tin.';
            }
        }
    }
    include __DIR__ . '/../views/books/fine_edit.php';
} elseif ($action === 'fine_delete') {
    require_once __DIR__ . '/../models/Fine.php';
    $id = intval($_GET['id'] ?? 0);
    if ($id) {
        $result = Fine::delete($id);
        if ($result) {
            $_SESSION['success_message'] = 'Xóa phiếu phạt thành công!';
        } else {
            $_SESSION['error_message'] = 'Lỗi khi xóa phiếu phạt!';
        }
    }
    header('Location: ../public/index.php?action=admin_fines');
    exit();
} elseif ($action === 'fine_mark_paid') {
    require_once __DIR__ . '/../models/Fine.php';
    $id = intval($_GET['id'] ?? 0);
    if ($id) {
        $result = Fine::markAsPaid($id);
        if ($result) {
            $_SESSION['success_message'] = 'Xác nhận đã đóng phạt thành công!';
        } else {
            $_SESSION['error_message'] = 'Lỗi xác nhận đóng phạt!';
        }
    }
    header('Location: index.php?action=admin_fines');
    exit();
} elseif ($action === 'admin_report') {
    BookController::admin_report();
} elseif ($action === 'chatbot') {
    include __DIR__ . '/../views/books/chatbot.php';
} elseif ($action === 'chatbot_api') {
    // Debug: kiểm tra nhận request
    file_put_contents(__DIR__ . '/../chatbot_debug.log', date('Y-m-d H:i:s') . "\n" . file_get_contents('php://input') . "\n", FILE_APPEND);
    session_start();
    header('Content-Type: application/json; charset=utf-8');
    $input = json_decode(file_get_contents('php://input'), true);
    if (!$input || !isset($input['question'])) {
        echo json_encode(['answer' => 'Lỗi: Không nhận được dữ liệu từ frontend!', 'debug' => $input]);
        exit();
    }
    $question = trim($input['question']);
    if (!$question) {
        echo json_encode(['answer' => 'Vui lòng nhập câu hỏi.']);
        exit();
    }
    // Lưu context hội thoại
    if (!isset($_SESSION['chat_context'])) $_SESSION['chat_context'] = [];
    $_SESSION['chat_context'][] = ['role' => 'user', 'content' => $question];
    if (count($_SESSION['chat_context']) > 5) {
        $_SESSION['chat_context'] = array_slice($_SESSION['chat_context'], -5);
    }
    // Phân loại câu hỏi
    $book_keywords = ['sách', 'thư viện', 'tác giả', 'danh mục', 'mượn', 'đọc', 'thể loại', 'LibSmart'];
    $is_book_question = false;
    foreach ($book_keywords as $kw) {
        if (mb_stripos($question, $kw) !== false) {
            $is_book_question = true;
            break;
        }
    }
    require_once __DIR__ . '/../helpers/gemini_ai.php';
    if ($is_book_question) {
        require_once __DIR__ . '/../models/Book.php';
        $books = Book::search($question);
        if (empty($books)) {
            // Nếu không tìm thấy sách liên quan, lấy 1 sách mới nhất
            $books = Book::all();
            $books = array_slice($books, 0, 1);
        } else {
            // Luôn chỉ lấy 1 sách gợi ý
            $books = array_slice($books, 0, 1);
        }
        $suggestions = [];
        foreach ($books as $b) {
            $desc = isset($b['description']) ? $b['description'] : '';
            $suggestions[] = $b['title'] . ' - ' . $b['author'] . ($desc ? ": $desc" : "");
        }
        // Format xuống dòng rõ ràng
        $book_info = implode("<br>", $suggestions);
        $prompt = "Bạn là LibSmart Assistant. Trả lời ngắn gọn, lịch sự, trình bày rõ ràng, mỗi ý hoặc mỗi câu nên xuống dòng để khách dễ đọc. Không dùng emoji, không dùng biểu tượng cảm xúc. Chỉ tập trung vào nội dung câu hỏi.\nCâu hỏi: $question\nSách liên quan: $book_info";
        $answer = gemini_chat($question, $prompt, $book_info);
    } else {
        $context_msgs = array_map(function($msg) {
            return ($msg['role'] === 'user' ? 'Người dùng: ' : 'LibSmart Assistant: ') . $msg['content'];
        }, $_SESSION['chat_context']);
        $context_str = implode("\n", $context_msgs);
        $prompt = "Bạn là LibSmart Assistant, trợ lý AI thân thiện của thư viện LibSmart.\n";
        $prompt .= "Dưới đây là lịch sử hội thoại:\n" . $context_str . "\n";
        $prompt .= "Nếu câu hỏi vượt quá phạm vi kiến thức, hãy trả lời nhẹ nhàng, thân thiện, không nói 'không có thông tin', mà hãy trò chuyện tự nhiên hoặc đề xuất chủ đề khác. Luôn trả lời bằng tiếng Việt, có thể thêm emoji phù hợp.";
        $answer = gemini_chat($question, $prompt);
    }
    file_put_contents(__DIR__ . '/../chatbot_debug.log', "PROMPT: " . $question . "\nDATA: " . json_encode([
        'contents' => [
            [
                'parts' => [ ['text' => $question ] ]
            ]
        ]
    ]) . "\nRESPONSE: " . $answer . "\n", FILE_APPEND);
    $_SESSION['chat_context'][] = ['role' => 'assistant', 'content' => $answer];
    if (count($_SESSION['chat_context']) > 5) {
        $_SESSION['chat_context'] = array_slice($_SESSION['chat_context'], -5);
    }
    // Đảm bảo không có output nào trước khi trả về JSON
    if (ob_get_length()) ob_end_clean();
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(['answer' => $answer]);
    exit();
} elseif ($action === 'user_manage') {
    UserController::manage();
} elseif ($action === 'user_delete') {
    UserController::delete();
} elseif ($action === 'user_edit') {
    UserController::edit();
} elseif ($action === 'about') {
    include __DIR__ . '/../views/auth/about.php';
} else {
    if (isset($_SESSION['role']) && $_SESSION['role'] === 'reader') {
        AuthController::reader_dashboard();
    } else {
        AuthController::home(); // This will be the public book list
    }
}

