<?php

require_once __DIR__ . '/../../config/Database.php';

class RequestController
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

    public function handleRequest(array $post): void
    {
        if (!isset($post['order_id'])) {
            $this->fail("Invalid request.");
        }

        $order_id = intval($post['order_id']);
        $mechanic_id = $_SESSION['user_id'];

        if (isset($post['accept'])) {
            $stmt = $this->conn->prepare("UPDATE orders SET status = 'accepted', mechanic_id = ? WHERE id = ? AND mechanic_id IS NULL");
            $stmt->bind_param("ii", $mechanic_id, $order_id);

            if ($stmt->execute()) {
                $_SESSION['success'] = "Order accepted successfully.";
            } else {
                $_SESSION['error'] = "Failed to accept the order.";
            }
        }

        elseif (isset($post['reject'])) {
            $stmt = $this->conn->prepare("UPDATE orders SET status = 'rejected' WHERE id = ? AND mechanic_id IS NULL");
            $stmt->bind_param("i", $order_id);

            if ($stmt->execute()) {
                $_SESSION['success'] = "Order rejected.";
            } else {
                $_SESSION['error'] = "Failed to reject the order.";
            }
        }

        header("Location: ../../public/dashboard/mechanic/requests/new_requests.php");
        exit;
    }

    private function fail(string $message): void
    {
        $_SESSION['error'] = $message;
        header("Location: ../../public/dashboard/mechanic/requests/new_requests.php");
        exit;
    }
}
