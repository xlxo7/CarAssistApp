<?php

require_once __DIR__ . '/../../config/Database.php';

class OrderController
{
    private mysqli $conn;

    public function __construct()
    {
        session_start();
        $this->conn = Database::getInstance()->getConnection();
    }

    public function getCompletedOrders(): array
    {
        $query = "SELECT o.id, u.name AS customer_name, m.name AS mechanic_name, o.created_at
                  FROM orders o
                  JOIN users u ON o.customer_id = u.id
                  JOIN users m ON o.mechanic_id = m.id
                  WHERE o.status = 'completed'";

        $result = $this->conn->query($query);
        $orders = [];

        while ($row = $result->fetch_assoc()) {
            $orders[] = $row;
        }

        return $orders;
    }
}
