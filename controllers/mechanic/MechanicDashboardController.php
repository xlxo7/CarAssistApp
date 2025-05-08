<?php

require_once __DIR__ . '/../../config/Database.php';

class MechanicDashboardController
{
    private mysqli $conn;

    public function __construct()
    {
        session_start();
        $this->conn = Database::getInstance()->getConnection();
    }

    public function getMechanicData(int $id): ?array
    {
        $stmt = $this->conn->prepare("SELECT name, availability FROM users WHERE id = ? AND user_type = 'mechanic'");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->num_rows > 0 ? $result->fetch_assoc() : null;
    }
}
