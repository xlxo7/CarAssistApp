<?php
require_once __DIR__ . '/../../config/Database.php';

class OrderController
{
    private mysqli $conn;

    public function __construct()
    {
        $this->conn = Database::getInstance()->getConnection();
    }

    public function getActiveOrders(int $customer_id): mysqli_result|false
    {
        $stmt = $this->conn->prepare("
            SELECT o.*, s.name AS service_name, u.name AS mechanic_name
            FROM orders o
            JOIN services s ON o.service_id = s.id
            JOIN users u ON o.mechanic_id = u.id
            WHERE o.customer_id = ? AND o.status IN ('pending', 'on_the_way')
            ORDER BY o.created_at DESC
        ");
        $stmt->bind_param("i", $customer_id);
        $stmt->execute();
        return $stmt->get_result();
    }

    public function getPastOrders(int $customer_id): mysqli_result|false
    {
        $stmt = $this->conn->prepare("
            SELECT o.*, s.name AS service_name, u.name AS mechanic_name,
                (SELECT COUNT(*) FROM ratings r WHERE r.order_id = o.id) AS rated
            FROM orders o
            JOIN services s ON o.service_id = s.id
            JOIN users u ON o.mechanic_id = u.id
            WHERE o.customer_id = ? AND o.status = 'completed'
            ORDER BY o.created_at DESC
        ");
        $stmt->bind_param("i", $customer_id);
        $stmt->execute();
        return $stmt->get_result();
    }

    public function getAllOrdersWithRating(int $customer_id): mysqli_result|false
    {
        $stmt = $this->conn->prepare("
            SELECT o.*, s.name AS service_name, u.name AS mechanic_name,
                   (SELECT rating FROM ratings r WHERE r.order_id = o.id LIMIT 1) AS rating,
                   (SELECT comment FROM ratings r WHERE r.order_id = o.id LIMIT 1) AS feedback
            FROM orders o
            JOIN services s ON o.service_id = s.id
            LEFT JOIN users u ON o.mechanic_id = u.id
            WHERE o.customer_id = ?
            ORDER BY o.created_at DESC
        ");
        $stmt->bind_param("i", $customer_id);
        $stmt->execute();
        return $stmt->get_result();
    }

    public function getActiveTrackingOrders(int $customer_id): mysqli_result|false
    {
    $stmt = $this->conn->prepare("
        SELECT o.id, o.status, o.created_at, s.name AS service_name, u.name AS mechanic_name
        FROM orders o
        JOIN services s ON o.service_id = s.id
        LEFT JOIN users u ON o.mechanic_id = u.id
        WHERE o.customer_id = ? AND o.status NOT IN ('completed', 'cancelled')
        ORDER BY 
            FIELD(o.status, 'on_the_way', 'accepted', 'pending'), 
            o.created_at DESC
    ");
    $stmt->bind_param("i", $customer_id);
    $stmt->execute();
    return $stmt->get_result();
    }

    public function cancelOrder(int $order_id, int $customer_id): string
    {
    $stmt = $this->conn->prepare("SELECT status FROM orders WHERE id = ? AND customer_id = ?");
    $stmt->bind_param("ii", $order_id, $customer_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        return "Unauthorized or invalid order.";
    }

    $order = $result->fetch_assoc();

    if ($order['status'] !== 'pending') {
        return "Cannot cancel this order. It is already being processed.";
    }

    $update_stmt = $this->conn->prepare("UPDATE orders SET status = 'cancelled' WHERE id = ?");
    $update_stmt->bind_param("i", $order_id);
    $update_stmt->execute();

    return "Order has been successfully cancelled.";
    }
}