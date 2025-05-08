<?php

require_once __DIR__ . '/../../config/Database.php';
require_once __DIR__ . '/../../factories/UserFactory.php';

class RolesController
{
    private mysqli $conn;

    public function __construct()
    {
        session_start();
        $this->conn = Database::getInstance()->getConnection();
    }

    public function loginWithRoles(array $data): void
    {
        $email = trim($data['email']);
        $password = $data['password'];
        $redirect = $data['redirect'] ?? null;

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->fail("Invalid email address.");
        }

        $stmt = $this->conn->prepare("SELECT * FROM users WHERE email = ? LIMIT 1");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows === 1) {
            $userData = $result->fetch_assoc();

            if (password_verify($password, $userData['password'])) {
                $userObject = UserFactory::createUser($userData);

                $_SESSION['user_id'] = $userObject->getId();
                $_SESSION['user_name'] = $userObject->getName();
                $_SESSION['user_type'] = $userObject->getUserType();
                $_SESSION['role_id'] = $userData['role_id']; // لو role_id مش داخل الـ Factory

                if ($redirect) {
                    header("Location: ../../public/" . $redirect);
                    exit;
                }

                $this->redirectByUserType($userObject->getUserType());
            } else {
                $this->fail("Incorrect password.");
            }
        } else {
            $this->fail("No account found with that email.");
        }
    }

    private function redirectByUserType(string $type): void
    {
        $base = "../../public/dashboard/";
        switch ($type) {
            case 'customer':
                header("Location: {$base}customer/index.php");
                break;
            case 'mechanic':
                header("Location: {$base}mechanic/index.php");
                break;
            case 'admin':
                header("Location: {$base}admin/index.php");
                break;
            default:
                $this->fail("Unknown user type.");
        }
        exit;
    }

    private function fail(string $msg): void
    {
        $_SESSION['error'] = $msg;
        header("Location: ../../public/auth/login/login.php");
        exit;
    }
}
