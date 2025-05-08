<?php

require_once __DIR__ . '/../../config/Database.php';

class AdminDashboardController
{
    private mysqli $conn;

    public function __construct()
    {
        $this->conn = Database::getInstance()->getConnection();
    }

    public function getStatistics(): array
    {
        $stats = [];

        $mechanicStmt = $this->conn->query("SELECT COUNT(*) as total FROM users WHERE user_type = 'mechanic' AND status = 'approved'");
        $stats['mechanics'] = $mechanicStmt ? $mechanicStmt->fetch_assoc()['total'] : 0;

        $customerStmt = $this->conn->query("SELECT COUNT(*) as total FROM users WHERE user_type = 'customer'");
        $stats['customers'] = $customerStmt ? $customerStmt->fetch_assoc()['total'] : 0;

        $orderStmt = $this->conn->query("SELECT COUNT(*) as total FROM orders WHERE status = 'completed'");
        $stats['orders'] = $orderStmt ? $orderStmt->fetch_assoc()['total'] : 0;

        return $stats;
    }
}
