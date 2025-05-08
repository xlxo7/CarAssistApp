<?php
require_once __DIR__ . '/../../config/Database.php';

class MechanicSelectionController
{
    private mysqli $conn;

    public function __construct()
    {
        $this->conn = Database::getInstance()->getConnection();
    }

    public function getAvailableMechanics(array $filters): mysqli_result|false
    {
        $lat = $filters['latitude'];
        $lng = $filters['longitude'];
        $service_id = $filters['service_id'];
        $search_name = $filters['search_name'] ?? null;
        $sort_by = $filters['sort_by'] ?? 'distance';

        $select = "
            SELECT u.id, u.name, u.phone, u.latitude, u.longitude, u.availability,
                (6371 * acos(
                    cos(radians(?)) * cos(radians(u.latitude)) *
                    cos(radians(u.longitude) - radians(?)) +
                    sin(radians(?)) * sin(radians(u.latitude))
                )) AS distance,
                AVG(r.rating) AS avg_rating
        ";

        $from = "
            FROM users u
            INNER JOIN mechanic_services ms ON u.id = ms.mechanic_id
            LEFT JOIN orders o ON u.id = o.mechanic_id
            LEFT JOIN ratings r ON o.id = r.order_id
        ";

        $where = "
            WHERE u.user_type = 'mechanic'
              AND u.status = 'approved'
              AND ms.service_id = ?
        ";

        $params = [$lat, $lng, $lat, $service_id];
        $types = "ddds";

        if ($search_name) {
            $where .= " AND u.name LIKE ?";
            $params[] = '%' . $search_name . '%';
            $types .= "s";
        } else {
            $where .= " AND u.availability = 'available'";
        }

        $group_order = "
            GROUP BY u.id
            HAVING distance <= 5
            ORDER BY " . ($sort_by === 'rating' ? 'avg_rating DESC' : 'distance ASC');

        $query = $select . $from . $where . $group_order;

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param($types, ...$params);
        $stmt->execute();
        return $stmt->get_result();
    }
}
