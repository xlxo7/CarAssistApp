<?php

require_once __DIR__ . '/../../config/Database.php';

class ReportController
{
    private mysqli $conn;

    public function __construct()
    {
        session_start();

        // السماح فقط للميكانيكي بالدخول
        if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'mechanic') {
            header("Location: ../../public/auth/login/login.php");
            exit;
        }

        $this->conn = Database::getInstance()->getConnection();
    }

    public function submitCustomerReport(array $post): void
    {
        if (!isset($post['submit_report'], $post['customer_id'], $post['message'])) {
            $this->fail("Invalid request.");
        }

        $mechanic_id = $_SESSION['user_id'];
        $customer_id = intval($post['customer_id']);
        $reason = trim($post['message']); // لاحظ تغيير التسمية داخليًا لـ reason

        if (empty($reason)) {
            $this->fail("Report message cannot be empty.");
        }

        $stmt = $this->conn->prepare("INSERT INTO customer_reports (mechanic_id, customer_id, reason) VALUES (?, ?, ?)");
        $stmt->bind_param("iis", $mechanic_id, $customer_id, $reason);

        if ($stmt->execute()) {
            $_SESSION['success'] = "Report submitted successfully. Awaiting admin review.";
        } else {
            $_SESSION['error'] = "Failed to submit the report. Please try again.";
        }

        $this->redirect();
    }

    private function fail(string $message): void
    {
        $_SESSION['error'] = $message;
        $this->redirect();
    }

    private function redirect(): void
    {
        header("Location: ../../public/dashboard/mechanic/reports/report_customer.php");
        exit;
    }
}
