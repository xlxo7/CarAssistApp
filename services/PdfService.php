<?php

require_once __DIR__ . '/../libraries/fpdf/fpdf.php';

class PdfService
{
    public function generateMechanicApprovalPDF(string $mechanicName, string $email, string $password, int $mechanicId): string
    {
        $pdf = new FPDF();
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 16);
        $pdf->Cell(0, 10, 'RoadFix - Mechanic Approval Details', 0, 1, 'C');
        $pdf->Ln(10);
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(0, 10, "Dear $mechanicName,", 0, 1);
        $pdf->Cell(0, 10, 'Your registration has been approved.', 0, 1);
        $pdf->Cell(0, 10, "Login Email: $email", 0, 1);
        $pdf->Cell(0, 10, "Password: $password", 0, 1);
        $pdf->Cell(0, 10, 'You can now log in to the system.', 0, 1);

        $filePath = __DIR__ . "/../uploads/cvc/mechanic_{$mechanicId}_approval.pdf";
        $pdf->Output('F', $filePath);

        return $filePath;
    }

    public function generateMechanicRejectionPDF(string $mechanicName, int $mechanicId): string
    {
        $pdf = new FPDF();
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 16);
        $pdf->Cell(0, 10, 'RoadFix - Application Rejection Notice', 0, 1, 'C');
        $pdf->Ln(10);
        $pdf->SetFont('Arial', '', 12);

        $message = "Dear $mechanicName,\n\nWe regret to inform you that your application to join RoadFix as a mechanic has been declined.\n\nWe appreciate your interest and encourage you to apply again in the future.\n\nBest regards,\nRoadFix Team";

        $pdf->MultiCell(0, 10, $message);

        $filePath = __DIR__ . "/../uploads/cvc/mechanic_{$mechanicId}_rejection.pdf";
        $pdf->Output('F', $filePath);

        return $filePath;
    }
}
