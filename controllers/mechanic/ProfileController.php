<?php

require_once __DIR__ . '/../../config/Database.php';

class ProfileController
{
    private mysqli $conn;

    public function __construct()
    {
        session_start();
        if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'mechanic') {
            header("Location: /car_assist_app/public/auth/login/login.php");
            exit;
        }

        $this->conn = Database::getInstance()->getConnection();
    }

    public function getMechanicProfile(int $mechanicId): array|null
    {
        $stmt = $this->conn->prepare("SELECT name, phone, availability FROM users WHERE id = ?");
        $stmt->bind_param("i", $mechanicId);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_assoc() ?? null;
    }
}
