<?php
// config/mailer.php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../vendor/autoload.php';

function send_verification_email($to, $code) {
    $mail = new PHPMailer(true);
    try {
        //Server settings
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'khangcfa@gmail.com'; // Thay bằng email của bạn
        $mail->Password   = 'puxq dshf cpbg jzez';    // Thay bằng app password (không phải mật khẩu Gmail)
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;
        $mail->CharSet    = 'UTF-8';

        //Recipients
        $mail->setFrom('khangcfa@gmail.com', 'LibSmart');
        $mail->addAddress($to);

        //Content
        $mail->isHTML(true);
        $mail->Subject = 'Mã xác minh tài khoản LibSmart';
        $mail->Body    = 'Mã xác minh của bạn là: <b>' . htmlspecialchars($code) . '</b>';

        $mail->send();
        return true;
    } catch (Exception $e) {
        echo "Mailer Error: {$mail->ErrorInfo}";
        return false;
    }
}

function getMailer() {
    $mail = new PHPMailer(true);
    //Server settings
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'khangcfa@gmail.com';
    $mail->Password   = 'puxq dshf cpbg jzez';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;
    $mail->CharSet    = 'UTF-8';
    $mail->isHTML(true);
    $mail->setFrom('khangcfa@gmail.com', 'LibSmart');
    return $mail;
}
