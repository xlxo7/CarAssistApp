<?php

require_once __DIR__ . '/../../config/Database.php';
require_once __DIR__ . '/../../services/EmailService.php';

class MechanicOrderController
{
    private mysqli $conn;

    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'mechanic') {
            header("Location: /car_assist_app/public/auth/login/login.php");
            exit;
        }

        $this->conn = Database::getInstance()->getConnection();
    }

    public function getNewRequests(): mysqli_result|false
    {
        $mechanic_id = $_SESSION['user_id'];

        $stmt = $this->conn->prepare("
            SELECT o.id, o.service_id, o.latitude, o.longitude, o.scheduled_time, u.name AS customer_name
            FROM orders o
            JOIN users u ON o.customer_id = u.id
            WHERE o.status = 'pending' AND o.mechanic_id = ?
        ");

        $stmt->bind_param("i", $mechanic_id);
        $stmt->execute();
        return $stmt->get_result();
    }

    public function getPreviousOrders(): mysqli_result|false
    {
        $mechanic_id = $_SESSION['user_id'];

        $stmt = $this->conn->prepare("
            SELECT o.id AS order_id, o.completed_at, o.rating, o.feedback, u.name AS customer_name 
            FROM orders o 
            JOIN users u ON o.customer_id = u.id 
            WHERE o.mechanic_id = ? AND o.status = 'completed'
            ORDER BY o.completed_at DESC
        ");

        $stmt->bind_param("i", $mechanic_id);
        $stmt->execute();
        return $stmt->get_result();
    }

    public function acceptRequest(int $orderId): void
    {
        $mechanicId = $_SESSION['user_id'];
    
        // تحديث الحالة
        $stmt = $this->conn->prepare("
            UPDATE orders 
            SET status = 'accepted'
            WHERE id = ? AND mechanic_id = ?
        ");
        $stmt->bind_param("ii", $orderId, $mechanicId);
        $stmt->execute();
    
        // إرسال إيميل للعميل
        $stmt2 = $this->conn->prepare("
            SELECT u.email, u.name, o.id 
            FROM orders o 
            JOIN users u ON o.customer_id = u.id 
            WHERE o.id = ? AND o.mechanic_id = ?
        ");
        $stmt2->bind_param("ii", $orderId, $mechanicId);
        $stmt2->execute();
        $result = $stmt2->get_result();
        $order = $result->fetch_assoc();
    
        if ($order) {
            require_once __DIR__ . '/../../services/EmailService.php';
            $emailService = new \Services\EmailService();
    
            $subject = "Your RoadFix Request Has Been Accepted";
            $body = "
                <h3>Hello {$order['name']},</h3>
                <p>Your service request (Order #{$order['id']}) has been accepted by a mechanic.</p>
                <p>You can now track the status of your request from your dashboard.</p>
                <p>Thank you for using RoaFix!</p>
            ";
            $emailService->sendWithAttachment($order['email'], $order['name'], $subject, $body, '');
        }
    
        // توجيه لصفحة تحديث الحالة
        header("Location: /car_assist_app/public/dashboard/mechanic/requests/update_order_status.php?id=$orderId");
        exit;
    }
    
    

    public function rejectRequest(int $orderId): void
    {
        $mechanicId = $_SESSION['user_id'];

        $stmt = $this->conn->prepare("
            UPDATE orders 
            SET status = 'rejected'
            WHERE id = ? AND mechanic_id = ? AND status = 'pending'
        ");
        $stmt->bind_param("ii", $orderId, $mechanicId);
        $stmt->execute();

        $_SESSION['flash_message'] = "Order rejected.";
        header("Location: /car_assist_app/public/dashboard/mechanic/requests/new_requests.php");
        exit;
    }

    public function getActiveRequests(): mysqli_result|false
    {
        $mechanic_id = $_SESSION['user_id'];

        $stmt = $this->conn->prepare("
            SELECT o.id, o.service_id, o.latitude, o.longitude, o.scheduled_time, u.name AS customer_name
            FROM orders o
            JOIN users u ON o.customer_id = u.id
            WHERE o.status = 'accepted' AND o.mechanic_id = ?
        ");
        $stmt->bind_param("i", $mechanic_id);
        $stmt->execute();
        return $stmt->get_result();
    }
}
