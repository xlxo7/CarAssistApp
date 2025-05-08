<?php
session_start();
include('../../../../includes/layout/header.php');
require_once('../../../../controllers/customer/ReportController.php');

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'customer') {
    header("Location: ../../../auth/login/login.php");
    exit;
}

$customer_id = $_SESSION['user_id'];
$mechanic_id = $_GET['id'] ?? null;
$controller = new ReportController();
$success = $error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $reason = trim($_POST['reason'] ?? '');

    if (!$mechanic_id || empty($reason)) {
        $error = "Please provide a valid report reason.";
    } else {
        if ($controller->submitReport($customer_id, (int)$mechanic_id, $reason)) {
            $success = "Report submitted successfully.";
        } else {
            $error = "Failed to submit report. Please try again.";
        }
    }
}
?>

<div class="container my-5">
    <h2 class="text-center fw-bold mb-4">Report a Mechanic</h2>

    <?php if ($error): ?>
        <div class="alert alert-danger text-center"><?= $error ?></div>
    <?php elseif ($success): ?>
        <div class="alert alert-success text-center"><?= $success ?></div>
    <?php endif; ?>

    <form method="POST" class="mx-auto" style="max-width: 600px;">
        <div class="mb-3">
            <label for="reason" class="form-label">Report Reason</label>
            <textarea name="reason" id="reason" rows="5" class="form-control" required placeholder="Describe the issue..."></textarea>
        </div>

        <input type="hidden" name="mechanic_id" value="<?= htmlspecialchars($mechanic_id) ?>">

        <div class="d-grid">
            <button type="submit" class="btn btn-danger">Submit Report</button>
        </div>
    </form>
</div>

<?php include('../../../../includes/layout/footer.php'); ?>
