<?php

require_once __DIR__ . '/../../config/Database.php';
require_once __DIR__ . '/../../factories/UserFactory.php';

class AuthController
{
    private mysqli $conn;

    public function __construct()
    {
        $this->conn = Database::getInstance()->getConnection();
    }

    public function login(string $email, string $password): void
    {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $userData = $result->fetch_assoc();

            // تحقق من تفعيل الإيميل للعملاء فقط
            if ($userData['user_type'] === 'customer' && (int)$userData['is_verified'] !== 1) {
                $_SESSION['error'] = 'Please verify your email before logging in.';
                $this->redirectToLogin();
            }

            if (password_verify($password, $userData['password'])) {
                // إنشاء كائن المستخدم من خلال Factory
                $userObject = UserFactory::createUser($userData);

                $_SESSION['user_id'] = $userObject->getId();
                $_SESSION['user_type'] = $userObject->getUserType();
                $_SESSION['user_name'] = $userObject->getName();

                $this->redirectToDashboard($userObject->getUserType());
            } else {
                $_SESSION['error'] = 'Incorrect password.';
                $this->redirectToLogin();
            }
        } else {
            $_SESSION['error'] = 'No user found with this email.';
            $this->redirectToLogin();
        }
    }

    private function redirectToDashboard(string $user_type): void
    {
        $base = "/car_assist_app/public/dashboard/";

        switch ($user_type) {
            case 'admin':
                header("Location: {$base}admin/index.php");
                break;
            case 'mechanic':
                header("Location: {$base}mechanic/index.php");
                break;
            case 'customer':
                header("Location: {$base}customer/index.php");
                break;
        }
        exit;
    }

    private function redirectToLogin(): void
    {
        header("Location: /car_assist_app/public/auth/login/login.php");
        exit;
    }
}
