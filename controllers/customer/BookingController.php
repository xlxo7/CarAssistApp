<?php
require_once __DIR__ . '/../../config/Database.php';

class BookingController
{
    private mysqli $conn;

    public function __construct()
    {
        $this->conn = Database::getInstance()->getConnection();
    }

    public function createBooking(int $customer_id, array $data): bool|string
    {
        $mechanic_id = $data['mechanic_id'] ?? null;
        $service_id = $data['service_id'] ?? null;
        $latitude = $data['latitude'] ?? null;
        $longitude = $data['longitude'] ?? null;

        if (!$mechanic_id || !$service_id || !$latitude || !$longitude) {
            return "Incomplete booking data.";
        }

        $stmt = $this->conn->prepare("INSERT INTO orders (customer_id, mechanic_id, service_id, latitude, longitude, status, created_at) VALUES (?, ?, ?, ?, ?, 'pending', NOW())");
        $stmt->bind_param("iiidd", $customer_id, $mechanic_id, $service_id, $latitude, $longitude);

        return $stmt->execute() ? true : "Failed to submit request.";
    }

    public function getMechanic(int $id): array|null
    {
        $stmt = $this->conn->prepare("SELECT name, phone FROM users WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function getService(int $id): array|null
    {
        $stmt = $this->conn->prepare("SELECT name FROM services WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }
}
