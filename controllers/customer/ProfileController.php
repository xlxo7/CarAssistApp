<?php
require_once __DIR__ . '/../../config/Database.php';

class ProfileController
{
    private mysqli $conn;

    public function __construct()
    {
        $this->conn = Database::getInstance()->getConnection();
    }

    public function getUserById(int $user_id): array|null
    {
        $stmt = $this->conn->prepare("SELECT name, phone, email, availability FROM users WHERE id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function updateProfile(int $user_id, string $name, string $phone): bool
    {
        $stmt = $this->conn->prepare("UPDATE users SET name = ?, phone = ? WHERE id = ?");
        $stmt->bind_param("ssi", $name, $phone, $user_id);
        return $stmt->execute();
    }
}
