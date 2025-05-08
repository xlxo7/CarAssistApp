<?php

require_once __DIR__ . '/../../config/Database.php';

class CustomerController
{
    private mysqli $conn;

    public function __construct()
    {
        $this->conn = Database::getInstance()->getConnection();
    }

    public function getAllCustomers(): array
    {
        $result = $this->conn->query("SELECT id, name, email, phone FROM users WHERE user_type = 'customer'");
        $customers = [];

        while ($row = $result->fetch_assoc()) {
            $customers[] = $row;
        }

        return $customers;
    }
}
