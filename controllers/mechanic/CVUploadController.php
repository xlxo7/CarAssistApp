<?php

require_once __DIR__ . '/../../config/Database.php';
require_once __DIR__ . '/../../services/ValidationService.php';

use Services\ValidationService;

class CVUploadController
{
    private mysqli $conn;

    public function __construct()
    {
        session_start();
        $this->conn = Database::getInstance()->getConnection();
    }

    public function upload(array $file, string $submitKey): void
    {
        if (!isset($_SESSION['user_id']) || !isset($file['cv']) || !isset($_POST[$submitKey])) {
            $this->fail('Invalid upload request.');
        }

        $user_id = $_SESSION['user_id'];
        $cv = $file['cv'];

        // ✅ التحقق من النوع باستخدام ValidationService
        if (!ValidationService::isPDF($cv)) {
            $this->fail('Only PDF files are allowed!');
        }

        // المسار
        $target_dir = "../../uploads/cvc/";
        $target_file = $target_dir . time() . '_' . basename($cv["name"]);

        // محاولة الرفع
        if (move_uploaded_file($cv["tmp_name"], $target_file)) {
            $stmt = $this->conn->prepare("UPDATE users SET cv = ? WHERE id = ?");
            $stmt->bind_param("si", $target_file, $user_id);
            $stmt->execute();

            $_SESSION['success'] = 'CV uploaded successfully!';
            header("Location: /car_assist_app/public/dashboard/mechanic/index.php");
            exit;
        } else {
            $this->fail('Sorry, there was an error uploading your file.');
        }
    }

    private function fail(string $msg): void
    {
        $_SESSION['error'] = $msg;
        header("Location: /car_assist_app/public/auth/register/upload_cv.php");
        exit;
    }
}
