<?php

require_once __DIR__ . '/../../config/Database.php';

class ServiceController
{
    private mysqli $conn;

    public function __construct()
    {
        $this->conn = Database::getInstance()->getConnection();
    }

    public function getAllServices(): mysqli_result|false
    {
        return $this->conn->query("SELECT id, name FROM services");
    }
}
