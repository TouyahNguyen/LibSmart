<?php
// helpers/gemini_ai.php
// Hàm gọi Gemini API Gemini chuẩn REST
function gemini_chat($user_message, $custom_prompt = null, $book_info = null) {
    $config = require __DIR__ . '/../config/gemini.php';
    $api_key = $config['api_key'];
    $endpoint = $config['endpoint'];
    // Tạo prompt ngắn gọn
    if ($book_info === null) {
        require_once __DIR__ . '/../models/Book.php';
        $books = Book::all();
        $books = array_slice($books, 0, 5);
        $book_list = array_map(function($b) {
            return $b['title'] . ' - ' . $b['author'];
        }, $books);
        $book_info = implode("; ", $book_list);
    }
    if ($custom_prompt) {
        $prompt = str_replace(['{books}', '{question}'], [$book_info, $user_message], $custom_prompt);
    } else {
        $prompt = "Bạn là LibSmart Assistant. Trả lời nghiêm túc, lịch sự, trình bày rõ ràng, mỗi ý hoặc mỗi câu nên xuống dòng để khách dễ đọc. Không dùng emoji, không dùng biểu tượng cảm xúc. Chỉ tập trung vào nội dung câu hỏi.";
        $prompt .= "\nCâu hỏi: $user_message";
        if ($book_info) {
            $prompt .= "\nSách liên quan: $book_info";
        }
    }
    // Payload Gemini chuẩn KHÔNG có 'role'
    $data = [
        'contents' => [
            [
                'parts' => [ ['text' => $prompt ] ]
            ]
        ]
    ];
    $ch = curl_init($endpoint);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'X-goog-api-key: ' . $api_key
    ]);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    $response = curl_exec($ch);
    if (curl_errno($ch)) {
        return 'Lỗi kết nối: ' . curl_error($ch);
    }
    curl_close($ch);
    $result = json_decode($response, true);
    if (isset($result['candidates'][0]['content']['parts'][0]['text'])) {
        return $result['candidates'][0]['content']['parts'][0]['text'];
    } elseif (isset($result['error']['message'])) {
        return 'Lỗi Gemini API: ' . htmlspecialchars($result['error']['message']) . '<br><pre>' . htmlspecialchars($response) . '</pre>';
    } else {
        return 'Không có phản hồi từ AI. Debug raw: <pre>' . htmlspecialchars($response) . '</pre>';
    }
}
