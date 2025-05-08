<?php

require_once __DIR__ . '/../../config/Database.php';

class UserController
{
    private mysqli $conn;

    public function __construct()
    {
        session_start();
        $this->conn = Database::getInstance()->getConnection();
    }

    public function deleteUser(int $user_id): void
    {
        $check = $this->conn->prepare("SELECT id FROM users WHERE id = ?");
        $check->bind_param("i", $user_id);
        $check->execute();
        $result = $check->get_result();

        if ($result && $result->num_rows > 0) {
            $stmt = $this->conn->prepare("DELETE FROM users WHERE id = ?");
            $stmt->bind_param("i", $user_id);
            if ($stmt->execute()) {
                $_SESSION['success'] = "User deleted successfully.";
            } else {
                $_SESSION['error'] = "Failed to delete user.";
            }
        } else {
            $_SESSION['error'] = "User not found.";
        }

        header("Location: ../../public/dashboard/admin/users/users.php");
        exit;
    }
}
