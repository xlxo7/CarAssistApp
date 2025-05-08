<?php
require_once __DIR__ . '/../../config/Database.php';

class ReportController
{
    private mysqli $conn;

    public function __construct()
    {
        $this->conn = Database::getInstance()->getConnection();
    }

    public function submitReport(int $customer_id, int $mechanic_id, string $reason): bool
    {
        $stmt = $this->conn->prepare("INSERT INTO customer_reports (customer_id, mechanic_id, reason, created_at) VALUES (?, ?, ?, NOW())");
        $stmt->bind_param("iis", $customer_id, $mechanic_id, $reason);
        return $stmt->execute();
    }
}
