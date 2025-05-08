<?php

require_once __DIR__ . '/../../config/Database.php';
require_once __DIR__ . '/../../services/EmailService.php';
require_once __DIR__ . '/../../services/PdfService.php';

use Services\EmailService;

class MechanicApprovalController
{
    private mysqli $conn;
    private EmailService $emailService;
    private PdfService $pdfService;

    public function __construct()
    {
        session_start();
        $this->conn = Database::getInstance()->getConnection();
        $this->emailService = new EmailService();
        $this->pdfService = new PdfService();
    }

    public function approve(int $mechanicId): void
    {
        $mechanic = $this->getMechanic($mechanicId);
        if (!$mechanic) return;
    
        $real_email = $mechanic['email']; // الإيميل اللي دخله الميكانيكي
        $generated_email = "mechanic{$mechanic['id']}@roadfix.com"; // الإيميل الاصطناعي
        $generated_password = substr(str_shuffle('abcdefghjkmnpqrstuvwxyzABCDEFGHJKMNPQRSTUVWXYZ23456789'), 0, 6);
        $hashed_password = password_hash($generated_password, PASSWORD_DEFAULT);
    
        // 🧾 توليد PDF بمعلومات تسجيل الدخول (الإيميل الاصطناعي)
        $pdfPath = $this->pdfService->generateMechanicApprovalPDF(
            $mechanic['name'], $generated_email, $generated_password, $mechanicId
        );
    
        // ✉️ إرسال الإيميل على الإيميل الحقيقي وليس الاصطناعي
        $emailSent = $this->emailService->sendWithAttachment(
            $real_email,
            $mechanic['name'],
            "Your RoadFix Mechanic Account is Approved",
            "Dear {$mechanic['name']},<br><br>Your account has been approved. Please find attached your login details.",
            $pdfPath
        );
    
        // ✅ بعد الإرسال: نحدث الإيميل إلى الاصطناعي
        $update = $this->conn->prepare("UPDATE users SET email = ?, password = ?, status = 'approved' WHERE id = ?");
        $update->bind_param("ssi", $generated_email, $hashed_password, $mechanicId);
    
        if (!$update->execute()) {
            $_SESSION['error'] = "❌ فشل التحديث: " . $update->error;
            $this->redirectBack();
        }
    
        $_SESSION[$emailSent ? 'success' : 'error'] = $emailSent
            ? "Mechanic approved and email sent successfully."
            : "Mechanic approved, but email failed to send.";
    
        $this->redirectBack();
    }
    

    public function reject(int $mechanicId): void
    {
        $mechanic = $this->getMechanic($mechanicId);
        if (!$mechanic) return;

        $update = $this->conn->prepare("UPDATE users SET status = 'rejected' WHERE id = ?");
        $update->bind_param("i", $mechanicId);
        if (!$update->execute()) {
            $_SESSION['error'] = "Failed to reject mechanic: " . $update->error;
            $this->redirectBack();
        }

        $pdfPath = $this->pdfService->generateMechanicRejectionPDF(
            $mechanic['name'], $mechanicId
        );

        $emailSent = $this->emailService->sendWithAttachment(
            $mechanic['email'],
            $mechanic['name'],
            "RoadFix Application Rejected",
            "Dear {$mechanic['name']},<br><br>We regret to inform you that your application has been rejected. Please find the attached document for more information.",
            $pdfPath
        );

        $_SESSION[$emailSent ? 'success' : 'error'] = $emailSent
            ? "Mechanic rejected and email sent."
            : "Mechanic rejected, but email failed to send.";

        $this->redirectBack();
    }

    private function getMechanic(int $id): ?array
    {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE id = ? AND user_type = 'mechanic'");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $mechanic = $result->fetch_assoc();

        if (!$mechanic) {
            $_SESSION['error'] = "Mechanic not found.";
            $this->redirectBack();
            return null;
        }

        return $mechanic;
    }

    private function redirectBack(): void
    {
        header("Location: /car_assist_app/public/dashboard/admin/approvals/mechanic_approvals.php");
        exit;
    }
}
