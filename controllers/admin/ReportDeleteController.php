<?php

require_once __DIR__ . '/../../config/Database.php';

class ReportDeleteController
{
    private mysqli $conn;

    public function __construct()
    {
        session_start();

        if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin') {
            header("Location: /car_assist_app/public/auth/login/login.php");
            exit;
        }

        $this->conn = Database::getInstance()->getConnection();
    }

    public function deleteReport(int $report_id): void
    {
        $stmt = $this->conn->prepare("DELETE FROM customer_reports WHERE id = ?");
        $stmt->bind_param("i", $report_id);

        if ($stmt->execute()) {
            $_SESSION['success'] = "Report deleted successfully.";
        } else {
            $_SESSION['error'] = "Failed to delete the report.";
        }

        header("Location: /car_assist_app/public/dashboard/admin/reports/customer_reports.php");
        exit;
    }
}
