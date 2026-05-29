<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// تحميل ملفات المكتبة يدوياً لتفادي مشاكل الاستضافة
require_once __DIR__ . '/PHPMailer/Exception.php';
require_once __DIR__ . '/PHPMailer/PHPMailer.php';
require_once __DIR__ . '/PHPMailer/SMTP.php';

function sendEmail($toEmail, $subject, $body) {
    $mail = new PHPMailer(true);

    try {
        // إعدادات الخادم (SMTP)
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com'; // خادم Gmail
        $mail->SMTPAuth   = true;
        
        // ضع إيميلك هنا
        $mail->Username   = 'YOUR_GMAIL_ADDRESS@gmail.com'; 
        
        // ضع كلمة مرور التطبيقات هنا (App Password)
        $mail->Password   = 'YOUR_APP_PASSWORD'; 
        
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        // إعدادات الرسالة
        $mail->setFrom('YOUR_GMAIL_ADDRESS@gmail.com', 'متجر أبو عماد'); // يجب أن يكون نفس الإيميل
        $mail->addAddress($toEmail);
        $mail->CharSet = 'UTF-8';

        $mail->isHTML(false);
        $mail->Subject = $subject;
        $mail->Body    = $body;

        $mail->send();
        return true;
    } catch (Exception $e) {
        // يمكن تسجيل الخطأ في ملف السجل
        error_log("Mail Error: {$mail->ErrorInfo}");
        return false;
    }
}
?>
