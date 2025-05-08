<?php

require_once __DIR__ . '/../../config/Database.php';
require_once __DIR__ . '/../../libraries/fpdf/fpdf.php';

class MechanicPDFReportController
{
    private mysqli $conn;

    public function __construct()
    {
        // لاحاجة لبدء session هنا، تم في الملف التنفيذي
        $this->conn = Database::getInstance()->getConnection();
    }

    public function generate(int $mechanic_id): void
    {
        // التحقق من وجود الميكانيكي
        $stmt = $this->conn->prepare("SELECT name FROM users WHERE id = ? AND user_type = 'mechanic'");
        $stmt->bind_param("i", $mechanic_id);
        $stmt->execute();
        $stmt->bind_result($mechanic_name);
        if (!$stmt->fetch()) {
            die("Mechanic not found.");
        }
        $stmt->close();

        // جلب التقييمات
        $query = $this->conn->prepare("
            SELECT rating 
            FROM orders 
            WHERE mechanic_id = ? AND status = 'completed' AND rating IS NOT NULL
        ");
        $query->bind_param("i", $mechanic_id);
        $query->execute();
        $result = $query->get_result();

        $ratings = [];
        while ($row = $result->fetch_assoc()) {
            $ratings[] = $row['rating'];
        }

        $order_count = count($ratings);
        $average_rating = $order_count > 0 ? round(array_sum($ratings) / $order_count, 2) : 'N/A';
        $total_salary = $order_count * 100;

        // إنشاء PDF
        $pdf = new FPDF();
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 16);
        $pdf->Cell(0, 10, 'Mechanic Performance Report', 0, 1, 'C');
        $pdf->Ln(10);
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(0, 10, 'Mechanic Name: ' . $mechanic_name, 0, 1);
        $pdf->Cell(0, 10, 'Completed Orders: ' . $order_count, 0, 1);
        $pdf->Cell(0, 10, 'Average Rating: ' . $average_rating, 0, 1);
        $pdf->Cell(0, 10, 'Total Salary (EGP): ' . $total_salary, 0, 1);

        $pdf->Output('I', 'mechanic_report.pdf');
        exit;
    }
}
