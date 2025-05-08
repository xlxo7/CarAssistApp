<?php

require_once __DIR__ . '/../../config/Database.php';

class MechanicController
{
    private mysqli $conn;

    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }        $this->conn = Database::getInstance()->getConnection();
    }

    public function getApprovedMechanics(): array
    {
        $query = "SELECT id, name, email, phone FROM users WHERE user_type = 'mechanic' AND status = 'approved'";
        $result = $this->conn->query($query);
        $mechanics = [];

        while ($row = $result->fetch_assoc()) {
            $mechanics[] = $row;
        }

        return $mechanics;
    }
}
