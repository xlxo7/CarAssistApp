<?php

require_once __DIR__ . '/../../config/Database.php';

class RoleController
{
    private mysqli $conn;

    public function __construct()
    {
        $this->conn = Database::getInstance()->getConnection();
    }

    public function getAllRoles(): mysqli_result
    {
        return $this->conn->query("SELECT * FROM roles");
    }

    public function addRole(string $name): bool
    {
        $stmt = $this->conn->prepare("INSERT INTO roles (name) VALUES (?)");
        $stmt->bind_param("s", $name);
        return $stmt->execute();
    }

    public function deleteRole(int $id): bool
    {
        $stmt = $this->conn->prepare("DELETE FROM roles WHERE id = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
