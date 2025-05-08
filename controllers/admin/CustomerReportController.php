<?php

require_once __DIR__ . '/../../config/Database.php';

class CustomerReportController
{
    private mysqli $conn;

    public function __construct()
    {
        session_start();
        $this->conn = Database::getInstance()->getConnection();
    }

    public function getAllReports(): array
    {
        $query = "
            SELECT cr.id, m.name AS mechanic_name, c.name AS customer_name, cr.reason, cr.status, cr.created_at
            FROM customer_reports cr
            JOIN users m ON cr.mechanic_id = m.id
            JOIN users c ON cr.customer_id = c.id
            ORDER BY cr.created_at DESC
        ";

        $result = $this->conn->query($query);
        $reports = [];

        while ($row = $result->fetch_assoc()) {
            $reports[] = $row;
        }

        return $reports;
    }

    public function getReportById(int $id): ?array
    {
        $stmt = $this->conn->prepare("
            SELECT cr.*, m.name AS mechanic_name, c.name AS customer_name
            FROM customer_reports cr
            JOIN users m ON cr.mechanic_id = m.id
            JOIN users c ON cr.customer_id = c.id
            WHERE cr.id = ?
        ");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->num_rows ? $result->fetch_assoc() : null;
    }

    public function updateReportStatus(int $id, string $status, int $adminId): bool
    {
        $stmt = $this->conn->prepare("
            UPDATE customer_reports 
            SET status = ?, reviewed_by = ?, reviewed_at = NOW() 
            WHERE id = ?
        ");
        $stmt->bind_param("sii", $status, $adminId, $id);
        return $stmt->execute();
    }
}
