<?php
require_once __DIR__ . '/../../config/Database.php';

class RatingController
{
    private mysqli $conn;

    public function __construct()
    {
        $this->conn = Database::getInstance()->getConnection();
    }

    public function getOrderForRating(int $order_id, int $customer_id): array|null
    {
        $stmt = $this->conn->prepare("SELECT o.id, o.mechanic_id, u.name AS mechanic_name 
                                      FROM orders o
                                      JOIN users u ON o.mechanic_id = u.id
                                      WHERE o.id = ? AND o.customer_id = ?");
        $stmt->bind_param("ii", $order_id, $customer_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->num_rows ? $result->fetch_assoc() : null;
    }

    public function submitRating(array $data): bool|string
    {
        $stmt = $this->conn->prepare("INSERT INTO ratings 
            (customer_id, mechanic_id, order_id, rating, comment, created_at)
            VALUES (?, ?, ?, ?, ?, NOW())");

        $stmt->bind_param(
            "iiiis",
            $data['customer_id'],
            $data['mechanic_id'],
            $data['order_id'],
            $data['rating'],
            $data['comment']
        );

        return $stmt->execute() ? true : "Failed to submit rating.";
    }
}
