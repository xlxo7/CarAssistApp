<?php

require_once __DIR__ . '/../../config/Database.php';
require_once __DIR__ . '/../../services/EmailService.php';

use Services\EmailService;

class ForgotPasswordController
{
    private mysqli $conn;
    private EmailService $emailService;

    public function __construct()
    {
        session_start();
        $this->conn = Database::getInstance()->getConnection();
        $this->emailService = new EmailService();
    }

    public function handleResetRequest(string $email): void
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->fail("Invalid email address.");
        }

        $stmt = $this->conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows === 1) {
            $user = $result->fetch_assoc();
            $token = bin2hex(random_bytes(32));
            $expires_at = date('Y-m-d H:i:s', time() + 60); // ساعة واحدة

            // تحديث قاعدة البيانات بالتوكن وتاريخ الانتهاء
            $update = $this->conn->prepare("UPDATE users SET reset_token = ?, reset_token_expires_at = ? WHERE id = ?");
            $update->bind_param("ssi", $token, $expires_at, $user['id']);
            $update->execute();

            // إرسال الإيميل
            $this->emailService->sendResetPasswordEmail($email, $token);
            $_SESSION['success'] = "A reset link has been sent.";
        }
        else {
            $_SESSION['error'] = "this email is not registered";
        }

        // عرض نفس الرسالة أياً كانت النتيجة
        header("Location: ../../../public/auth/login/forgot_password.php");
        exit;
    }

    private function fail(string $msg): void
    {
        $_SESSION['error'] = $msg;
        header("Location: ../../../public/auth/login/forgot_password.php");
        exit;
    }
}
