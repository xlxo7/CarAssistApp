<?php

require_once __DIR__ . '/../../config/Database.php';

class MechanicEditController
{
    private mysqli $conn;

    public function __construct()
    {
        session_start();
        $this->conn = Database::getInstance()->getConnection();
    }

    public function getMechanicById(int $id): ?array
    {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE id = ? AND user_type = 'mechanic'");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->num_rows > 0 ? $result->fetch_assoc() : null;
    }

    public function updateMechanic(int $id, string $name, string $email, string $phone): void
    {
        $stmt = $this->conn->prepare("UPDATE users SET name = ?, email = ?, phone = ? WHERE id = ?");
        $stmt->bind_param("sssi", $name, $email, $phone, $id);
        $stmt->execute();
        $_SESSION['success'] = "Mechanic updated successfully.";
        header("Location: users.php");
        exit;
    }
}
