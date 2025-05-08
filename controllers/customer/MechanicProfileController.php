<?php
require_once __DIR__ . '/../../config/Database.php';

class MechanicProfileController
{
    private mysqli $conn;

    public function __construct()
    {
        $this->conn = Database::getInstance()->getConnection();
    }

    public function getMechanic(int $id): array|null
    {
        $stmt = $this->conn->prepare("SELECT id, name, phone, availability FROM users WHERE id = ? AND user_type = 'mechanic'");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->num_rows > 0 ? $result->fetch_assoc() : null;
    }

    public function getServices(int $mechanic_id): array
    {
        $stmt = $this->conn->prepare("
            SELECT s.name FROM services s
            INNER JOIN mechanic_services ms ON s.id = ms.service_id
            WHERE ms.mechanic_id = ?
        ");
        $stmt->bind_param("i", $mechanic_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $services = [];
        while ($row = $result->fetch_assoc()) {
            $services[] = $row['name'];
        }
        return $services;
    }

    public function getRating(int $mechanic_id): array
    {
        $stmt = $this->conn->prepare("SELECT AVG(rating) AS avg_rating, COUNT(*) AS total_ratings FROM ratings WHERE mechanic_id = ?");
        $stmt->bind_param("i", $mechanic_id);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        return [
            'avg' => $result['avg_rating'] ? round($result['avg_rating'], 2) : null,
            'count' => $result['total_ratings']
        ];
    }
}
