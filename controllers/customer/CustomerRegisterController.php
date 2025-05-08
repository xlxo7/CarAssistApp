<?php

require_once __DIR__ . '/../../config/Database.php';
require_once __DIR__ . '/../../services/EmailService.php';
require_once __DIR__ . '/../../services/ValidationService.php';

class CustomerRegisterController
{
    private mysqli $conn;

    public function __construct()
    {
        session_start();
        $this->conn = Database::getInstance()->getConnection();
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
        $requiredErrors = ValidationService::validateRequiredFields($data, ['name', 'email', 'phone', 'password', 'confirm_password']);
        if (!empty($requiredErrors)) {
            $this->fail(implode(', ', $requiredErrors));
        }

        if (!ValidationService::validateEmail($email)) {
            $this->fail("Invalid email format.");
        }

        if (!ValidationService::validatePasswordMatch($password, $confirm_password)) {
            $this->fail("Passwords do not match.");
        }

        $check = $this->conn->prepare("SELECT id FROM users WHERE email = ?");
        $check->bind_param("s", $email);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
            $this->fail("Email already exists.");
        }

        $verification_token = bin2hex(random_bytes(32));
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        $stmt = $this->conn->prepare("INSERT INTO users (name, email, phone, password, user_type, is_verified, verification_token) VALUES (?, ?, ?, ?, ?, 0, ?)");
        $stmt->bind_param("ssssss", $name, $email, $phone, $hashed_password, $user_type, $verification_token);

        if ($stmt->execute()) {
            $emailService = new EmailService();
            $emailService->sendVerificationEmail($email, $verification_token);

            $_SESSION['success'] = "Registration successful. Please check your email to verify your account.";
            header("Location: ../../public/auth/register/registration_pending.php");
            exit;
        } else {
            $this->fail("Something went wrong. Please try again.");
        }
    }

    private function fail(string $message): void
    {
        $_SESSION['error'] = $message;
        header("Location: ../../public/auth/register/register.php");
        exit;
    }
}
