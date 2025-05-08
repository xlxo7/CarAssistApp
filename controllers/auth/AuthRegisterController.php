<?php

require_once __DIR__ . '/../../config/Database.php';
require_once __DIR__ . '/../../services/EmailService.php';
require_once __DIR__ . '/../../services/ValidationService.php';

use Services\EmailService;
use Services\ValidationService;

class AuthRegisterController
{
    private mysqli $conn;
    private EmailService $emailService;

    public function __construct()
    {
        session_start();
        $this->conn = Database::getInstance()->getConnection();
        $this->emailService = new EmailService();
    }

    public function register(array $data): void
    {
        $name = trim($data['name']);
        $email = trim($data['email']);
        $phone = trim($data['phone']);
        $password = $data['password'];
        $confirm_password = $data['confirm_password'];
        $user_type = $data['user_type'] ?? 'customer';

        // ✅ التحقق من الحقول المطلوبة
        $errors = ValidationService::validateRequiredFields($data, ['name', 'email', 'phone', 'password', 'confirm_password']);
        if (!empty($errors)) {
            $this->setErrorAndRedirect(implode(', ', $errors));
        }

        if (!ValidationService::validateEmail($email)) {
            $this->setErrorAndRedirect("Invalid email format.");
        }

        if (!ValidationService::validatePasswordMatch($password, $confirm_password)) {
            $this->setErrorAndRedirect("Passwords do not match.");
        }

        if ($this->emailExists($email)) {
            $this->setErrorAndRedirect("Email already exists.");
        }

        $hashed_password = password_hash($password, PASSWORD_BCRYPT);
        $verification_token = bin2hex(random_bytes(16));

        $stmt = $this->conn->prepare("INSERT INTO users (name, email, phone, password, user_type, verification_token) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $name, $email, $phone, $hashed_password, $user_type, $verification_token);

        if ($stmt->execute()) {
            $this->emailService->sendVerificationEmail($email, $verification_token);
            header("Location: /car_assist_app/public/auth/register/email_pending_verification.php");
            exit;
        } else {
            $this->setErrorAndRedirect("Something went wrong. Please try again.");
        }
    }

    private function emailExists(string $email): bool
    {
        $check = $this->conn->prepare("SELECT id FROM users WHERE email = ? LIMIT 1");
        $check->bind_param("s", $email);
        $check->execute();
        $check->store_result();
        return $check->num_rows > 0;
    }

    private function setErrorAndRedirect(string $message): void
    {
        $_SESSION['error'] = $message;
        header("Location: /car_assist_app/public/auth/register/register.php");
        exit;
    }
}
