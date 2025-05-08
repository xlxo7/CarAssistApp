<?php

require_once __DIR__ . '/../../config/Database.php';

// controllers/mechanic/MechanicOrderReportController.php
class MechanicOrderReportController
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

    public function getCompletedOrders(): mysqli_result|false
    {
        $stmt = $this->conn->prepare("
            SELECT o.id AS order_id, o.completed_at, o.rating, o.feedback, u.name AS customer_name 
            FROM orders o 
            JOIN users u ON o.customer_id = u.id 
            WHERE o.mechanic_id = ? AND o.status = 'completed'
            ORDER BY o.completed_at DESC
        ");
        $stmt->bind_param("i", $this->mechanic_id);
        $stmt->execute();
        return $stmt->get_result();
    }

    public function getMechanicId(): int
    {
        return $this->mechanic_id;
    }
}
