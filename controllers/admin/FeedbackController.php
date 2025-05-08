<?php

require_once __DIR__ . '/../../config/Database.php';

class FeedbackController
{
    private mysqli $conn;

    public function __construct()
    {
        session_start();
        $this->conn = Database::getInstance()->getConnection();
    }

    public function getAllFeedbacks(): array
    {
        $query = "SELECT f.id, u.name AS user_name, f.message, f.created_at
                    FROM feedbacks f
                    JOIN users u ON f.user_id = u.id
                    ORDER BY f.created_at DESC";

        $result = $this->conn->query($query);
        $feedbacks = [];

        while ($row = $result->fetch_assoc()) {
            $feedbacks[] = $row;
        }

        return $feedbacks;
    }
}
