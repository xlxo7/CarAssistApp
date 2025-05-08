<?php

namespace Services;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../libraries/PHPMailer/src/Exception.php';
require_once __DIR__ . '/../libraries/PHPMailer/src/PHPMailer.php';
require_once __DIR__ . '/../libraries/PHPMailer/src/SMTP.php';

class EmailService
{
    private PHPMailer $mail;

    public function __construct()
    {
        $this->mail = new PHPMailer(true);
        $this->setup();
    }

    private function setup(): void
    {
        $this->mail->isSMTP();
        $this->mail->Host = 'smtp.gmail.com';
        $this->mail->SMTPAuth = true;
        $this->mail->Username = 'salma21532153@gmail.com'; // 👈 عدليهم لو حابة
        $this->mail->Password = 'ykmffhdhzmduequu';
        $this->mail->SMTPSecure = 'tls';
        $this->mail->Port = 587;
        $this->mail->setFrom('salma21532153@gmail.com', 'RoaFix');
    }

    public function sendVerificationEmail(string $to, string $token): bool
    {
        try {
            $this->mail->clearAllRecipients();
            $this->mail->addAddress($to);
            $this->mail->isHTML(true);
            $this->mail->Subject = 'Verify Your Email';
            $link = "http://localhost/car_assist_app/public/auth/register/verify_email.php?token=$token";
            $this->mail->Body = "Click to verify your email: <a href='$link'>$link</a>";
            $this->mail->send();
            return true;
        } catch (Exception $e) {
            error_log("Email send error: " . $this->mail->ErrorInfo);
            return false;
        }
    }

    public function sendResetPasswordEmail(string $to, string $token): bool
    {
        try {
            $this->mail->clearAllRecipients();
            $this->mail->addAddress($to);
            $this->mail->isHTML(true);
            $this->mail->Subject = 'Reset Your RoaFix Password';
            $link = "http://localhost/car_assist_app/public/auth/login/reset_password.php?token=$token";
            $this->mail->Body = "
                <h3>Password Reset Request</h3>
                <p>Click the link below to reset your password (valid for 1 hour):</p>
                <a href='$link'>$link</a>
            ";
            $this->mail->send();
            return true;
        } catch (Exception $e) {
            error_log("Reset email failed: " . $this->mail->ErrorInfo);
            return false;
        }
    }

    public function sendWithAttachment(string $to, string $name, string $subject, string $body, string $attachmentPath): bool
    {
        try {
            $this->mail->clearAllRecipients();
            $this->mail->clearAttachments();
    
            $this->mail->addAddress($to, $name);
            $this->mail->isHTML(true);
            $this->mail->Subject = $subject;
            $this->mail->Body = $body;
    
            if (file_exists($attachmentPath)) {
                $this->mail->addAttachment($attachmentPath);
            } else {
                error_log("Attachment not found: $attachmentPath");
                return false;
            }
    
            $this->mail->send();
            return true;
        } catch (Exception $e) {
            error_log("Attachment email failed: " . $this->mail->ErrorInfo);
            return false;
        }
    }
    

}
