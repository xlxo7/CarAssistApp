<?php

require_once __DIR__ . '/../../config/Database.php';

class MechanicOrderStatusController
{
    private mysqli $conn;
    private int $mechanic_id;

    public function __construct()
    {
        session_start();

        if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'mechanic') {
            header("Location: /car_assist_app/public/auth/login/login.php");
            exit;
        }

        $this->conn = Database::getInstance()->getConnection();
        $this->mechanic_id = $_SESSION['user_id'];
    }

    public function getOrder(int $order_id): ?array
    {
        $stmt = $this->conn->prepare("SELECT * FROM orders WHERE id = ? AND mechanic_id = ?");
        $stmt->bind_param("ii", $order_id, $this->mechanic_id);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->num_rows > 0 ? $result->fetch_assoc() : null;
    }

    public function updateStatus(int $order_id, string $new_status): bool
    {
        $allowed = ['accepted', 'on_the_way', 'completed'];
        if (!in_array($new_status, $allowed)) {
            return false;
        }

        $stmt = $this->conn->prepare("UPDATE orders SET status = ? WHERE id = ?");
        $stmt->bind_param("si", $new_status, $order_id);
        return $stmt->execute();
    }
}
