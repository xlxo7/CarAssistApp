<?php
require_once('../../../../controllers/admin/CustomerReportController.php');
include('../../../../includes/layout/header.php');

$controller = new CustomerReportController();

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin') {
    header("Location: /car_assist_app/public/auth/login/login.php");
    exit;
}

$report_id = $_GET['id'] ?? null;
if (!$report_id) {
    $_SESSION['error'] = "No report ID provided.";
    header("Location: customer_reports.php");
    exit;
}

// عند الموافقة أو الرفض
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['status'])) {
    $status = $_POST['status'];
    $admin_id = $_SESSION['user_id'];
    if ($controller->updateReportStatus((int)$report_id, $status, $admin_id)) {
        $_SESSION['success'] = "Report has been $status.";
    } else {
        $_SESSION['error'] = "Failed to update report.";
    }
    header("Location: customer_reports.php");
    exit;
}

// جلب تفاصيل التقرير
$report = $controller->getReportById((int)$report_id);
if (!$report) {
    $_SESSION['error'] = "Report not found.";
    header("Location: customer_reports.php");
    exit;
}
?>

<div class="container my-5">
    <h2 class="fw-bold text-center mb-4">Review Report #<?= $report['id'] ?></h2>

    <div class="card shadow-sm p-4 mb-4">
        <p><strong>Mechanic:</strong> <?= htmlspecialchars($report['mechanic_name']) ?></p>
        <p><strong>Customer:</strong> <?= htmlspecialchars($report['customer_name']) ?></p>
        <p><strong>Reason:</strong> <?= htmlspecialchars($report['reason']) ?></p>
        <p><strong>Reported At:</strong> <?= htmlspecialchars($report['created_at']) ?></p>
        <p><strong>Status:</strong> 
            <span class="badge 
                <?= $report['status'] === 'approved' ? 'bg-success' : ($report['status'] === 'rejected' ? 'bg-danger' : 'bg-secondary') ?>">
                <?= ucfirst($report['status']) ?>
            </span>
        </p>
    </div>

    <?php if ($report['status'] === 'pending'): ?>
        <form method="POST" class="d-flex gap-3 justify-content-center">
            <input type="hidden" name="status" value="approved">
            <button type="submit" class="btn btn-success px-4">Approve</button>
        </form>

        <form method="POST" class="d-flex gap-3 justify-content-center mt-2">
            <input type="hidden" name="status" value="rejected">
            <button type="submit" class="btn btn-danger px-4">Reject</button>
        </form>
    <?php else: ?>
        <div class="text-center">
            <a href="customer_reports.php" class="btn btn-outline-primary mt-3">Back to Reports</a>
        </div>
    <?php endif; ?>
</div>

<?php include('../../../../includes/layout/footer.php'); ?>
