<?php

require_once __DIR__ . '/../../config/Database.php';

class EmailVerificationController
{
    private mysqli $conn;

    public function __construct()
    {
        session_start();
        $this->conn = Database::getInstance()->getConnection();
    }

    public function verify(string $token): void
    {
        $stmt = $this->conn->prepare("SELECT id FROM users WHERE verification_token = ? AND is_verified = 0 LIMIT 1");
        $stmt->bind_param("s", $token);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows === 1) {
            $user = $result->fetch_assoc();
            $user_id = $user['id'];

            $update = $this->conn->prepare("UPDATE users SET is_verified = 1, verification_token = NULL WHERE id = ?");
            $update->bind_param("i", $user_id);

            if ($update->execute()) {
                $_SESSION['success'] = "Your email has been verified. You can now log in.";
            } else {
                $_SESSION['error'] = "Verification failed. Please try again.";
            }
        } else {
            $_SESSION['error'] = "Invalid or expired verification link.";
        }

        header("Location: ../login/login.php");
        exit;
    }
}
