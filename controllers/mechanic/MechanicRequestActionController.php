<?php

require_once __DIR__ . '/../../config/Database.php';

class MechanicRequestActionController
{
    private mysqli $conn;

    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $this->conn = Database::getInstance()->getConnection();
    }

    public function handle(array $data): void
    {
        $order_id = $data['order_id'] ?? null;
        $mechanic_id = $_SESSION['user_id'] ?? null;

        if (!$order_id || !$mechanic_id) {
            $_SESSION['error'] = "Invalid request.";
            header("Location: /car_assist_app/public/dashboard/mechanic/requests/new_requests.php");
            exit;
        }

        if (isset($data['accept'])) {
            $this->updateStatus($order_id, $mechanic_id, 'accepted');
        } elseif (isset($data['reject'])) {
            $this->updateStatus($order_id, $mechanic_id, 'cancelled');
        }

        header("Location: /car_assist_app/public/dashboard/mechanic/requests/new_requests.php");
        exit;
    }

    private function updateStatus(int $order_id, int $mechanic_id, string $status): void
    {
        $stmt = $this->conn->prepare("UPDATE orders SET status = ? WHERE id = ? AND mechanic_id = ?");
        $stmt->bind_param("sii", $new_status, $order_id, $this->mechanic_id);        
        $stmt->execute();
    }
}
