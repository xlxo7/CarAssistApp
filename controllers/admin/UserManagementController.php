<?php

require_once __DIR__ . '/../../config/Database.php';

class UserManagementController
{
    private mysqli $conn;

    public function __construct()
    {
        $this->conn = Database::getInstance()->getConnection();
    }

    public function getFilteredUsers(?string $type_filter, ?string $search_by, ?string $search): mysqli_result|false
    {
        $whereClause = "WHERE 1";

        if ($type_filter && in_array($type_filter, ['customer', 'mechanic'])) {
            $type_filter = $this->conn->real_escape_string($type_filter);
            $whereClause .= " AND user_type = '$type_filter'";
        }

        if ($search && in_array($search_by, ['name', 'email', 'phone', 'id'])) {
            $column = $this->conn->real_escape_string($search_by);
            $keyword = $this->conn->real_escape_string($search);

            if ($column === 'id') {
                $whereClause .= " AND $column = '$keyword'";
            } else {
                $whereClause .= " AND $column LIKE '%$keyword%'";
            }
        }

        $query = "SELECT * FROM users $whereClause ORDER BY user_type, name";
        return $this->conn->query($query);
    }
}
