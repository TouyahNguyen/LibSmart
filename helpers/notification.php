<?php
// helpers/notification.php
require_once __DIR__ . '/../config/mailer.php';

function sendNotificationEmail($to, $subject, $body) {
    $mail = getMailer();
    try {
        $mail->addAddress($to);
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $body;
        $mail->send();
        return true;
    } catch (Exception $e) {
        // Log error if needed
        return false;
    }
}
