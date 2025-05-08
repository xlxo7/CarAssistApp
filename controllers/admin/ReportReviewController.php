<?php

require_once __DIR__ . '/../../config/Database.php';

class ReportReviewController
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

    public function reviewReport(int $report_id, string $action): void
    {
        $status = ($action === 'approve') ? 'approved' : 'rejected';
        $reviewed_by = $_SESSION['user_id'];
        $reviewed_at = date('Y-m-d H:i:s');

        $stmt = $this->conn->prepare("UPDATE customer_reports SET status = ?, reviewed_by = ?, reviewed_at = ? WHERE id = ?");
        $stmt->bind_param("sisi", $status, $reviewed_by, $reviewed_at, $report_id);

        if ($stmt->execute()) {
            $_SESSION['success'] = "Report has been $status successfully.";
        } else {
            $_SESSION['error'] = "Failed to update report status.";
        }

        header("Location: /car_assist_app/public/dashboard/admin/reports/customer_reports.php");
        exit;
    }
}
