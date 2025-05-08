<?php

require_once __DIR__ . '/../../config/Database.php';

class AvailabilityController
{
    private mysqli $conn;

    public function __construct()
    {
        session_start();
        $this->conn = Database::getInstance()->getConnection();

        // تأمين الجلسة
        if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'mechanic') {
            header("Location: /car_assist_app/public/auth/login/login.php");
            exit;
        }
    }

    public function toggleAvailability(int $mechanicId): void
    {
        $stmt = $this->conn->prepare("UPDATE users SET availability = IF(availability = 'available', 'busy', 'available') WHERE id = ?");
        $stmt->bind_param("i", $mechanicId);
        $stmt->execute();
    }

    public function getAvailabilityStatus(int $mechanicId): string
    {
        $stmt = $this->conn->prepare("SELECT availability FROM users WHERE id = ?");
        $stmt->bind_param("i", $mechanicId);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return $row['availability'] ?? 'unknown';
    }
}
