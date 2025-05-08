<?php

require_once __DIR__ . '/../../config/Database.php';
require_once __DIR__ . '/../../services/ValidationService.php';

use Services\ValidationService;

class MechanicRegisterController
{
    private mysqli $conn;

    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $this->conn = Database::getInstance()->getConnection();
    }

    public function register(array $data, array $file): void
    {
        $name = trim($data['name']);
        $email = trim($data['email']);
        $phone = trim($data['phone']);
        $password = trim($data['password']);
        $latitude = $data['latitude'] ?? null;
        $longitude = $data['longitude'] ?? null;
        $services = $data['services'] ?? [];

        // ✅ التحقق من الحقول المطلوبة
        $required = ValidationService::validateRequiredFields($data, ['name', 'email', 'phone', 'password']);
        if (!empty($required)) {
            $this->fail(implode(', ', $required));
        }

        if (!ValidationService::validateEmail($email)) {
            $this->fail("Invalid email format.");
        }

        if (!isset($file['cv']) || $file['cv']['error'] !== 0) {
            $this->fail("Please upload your CV.");
        }

        if (!ValidationService::isPDF($file['cv'])) {
            $this->fail("Only PDF files are allowed.");
        }

        // التحقق من تكرار البريد
        $check = $this->conn->prepare("SELECT id FROM users WHERE email = ?");
        $check->bind_param("s", $email);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
            $this->fail("Email is already registered. Please use another.");
        }

        // رفع الملف
        $cv_name = time() . '_' . basename($file['cv']['name']);
        $cv_path = '../../../uploads/cvc/' . $cv_name;

        if (!move_uploaded_file($file['cv']['tmp_name'], $cv_path)) {
            $this->fail("Failed to upload CV.");
        }

        // حفظ المستخدم
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $user_type = 'mechanic';
        $status = 'pending';

        $stmt = $this->conn->prepare("INSERT INTO users (name, email, phone, password, user_type, status, cv_path, latitude, longitude) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssssdd", $name, $email, $phone, $hashed_password, $user_type, $status, $cv_name, $latitude, $longitude);

        if ($stmt->execute()) {
            $mechanic_id = $stmt->insert_id;

            if (!empty($services)) {
                $ms_stmt = $this->conn->prepare("INSERT INTO mechanic_services (mechanic_id, service_id) VALUES (?, ?)");
                foreach ($services as $service_id) {
                    if (is_numeric($service_id) && $service_id > 0) {
                        $ms_stmt->bind_param("ii", $mechanic_id, $service_id);
                        $ms_stmt->execute();
                    }
                }
                $ms_stmt->close();
            }

            $_SESSION['success'] = "Registration successful. Awaiting admin approval.";
            header("Location: ../../../public/auth/register/registration_pending.php");
            exit;
        } else {
            $this->fail("Something went wrong. Please try again.");
        }
    }

    private function fail(string $msg): void
    {
        $_SESSION['error'] = $msg;
        header("Location: ../../../public/auth/register/register_mechanic.php");
        exit;
    }
}
