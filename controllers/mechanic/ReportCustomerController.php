<?php

require_once __DIR__ . '/../../config/Database.php';

class ReportCustomerController
{
    private mysqli $conn;

    public function __construct()
    {
        session_start();

        if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'mechanic') {
            header("Location: ../../public/auth/login/login.php");
            exit;
        }

        $this->conn = Database::getInstance()->getConnection();
    }

    public function submit(array $post): void
    {
        if (!isset($post['submit_report'], $post['customer_id'], $post['reason'])) {
            $this->fail("Invalid submission.");
        }

        $mechanic_id = $_SESSION['user_id'];
        $customer_id = intval($post['customer_id']);
        $reason = trim($post['reason']);

        if (empty($reason)) {
            $this->fail("Reason cannot be empty.");
        }

        $stmt = $this->conn->prepare("INSERT INTO customer_reports (mechanic_id, customer_id, reason) VALUES (?, ?, ?)");
        $stmt->bind_param("iis", $mechanic_id, $customer_id, $reason);

        if ($stmt->execute()) {
            $_SESSION['success'] = "Report submitted successfully.";
        } else {
            $_SESSION['error'] = "Failed to submit the report.";
        }

        $this->redirect();
    }

    private function fail(string $msg): void
    {
        $_SESSION['error'] = $msg;
        $this->redirect();
    }

    private function redirect(): void
    {
        header("Location: ../../public/dashboard/mechanic/reports/report_customer.php");
        exit;
    }
}
