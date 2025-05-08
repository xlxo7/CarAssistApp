<?php
session_start();
include('../../../../includes/layout/header.php');

// السماح فقط للميكانيكي
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'mechanic') {
    header("Location: ../../../auth/login/login.php");
    exit;
}

require_once('../../../../config/Database.php');
$conn = Database::getInstance()->getConnection();
$mechanic_id = $_SESSION['user_id'];

// جلب العملاء اللي اتعامل معاهم الميكانيكي
$stmt = $conn->prepare("
    SELECT DISTINCT u.id, u.name 
    FROM orders o 
    JOIN users u ON o.customer_id = u.id 
    WHERE o.mechanic_id = ?
");
$stmt->bind_param("i", $mechanic_id);
$stmt->execute();
$customers = $stmt->get_result();
?>

<div class="container my-5">
    <h2 class="text-center fw-bold mb-4">Report a Customer</h2>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success text-center">
            <?= $_SESSION['success']; unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger text-center">
            <?= $_SESSION['error']; unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="../../../../controllers/mechanic/report_customer_controller.php">
    <div class="mb-3">
        <label for="customer_id" class="form-label">Select Customer</label>
        <select class="form-select" id="customer_id" name="customer_id" required>
            <?php while ($row = $customers->fetch_assoc()): ?>
                <option value="<?= $row['id'] ?>"><?= htmlspecialchars($row['name']) ?></option>
            <?php endwhile; ?>
        </select>
    </div>

    <div class="mb-3">
        <label for="reason" class="form-label">Report Message</label>
        <textarea class="form-control" id="reason" name="reason" rows="5" required></textarea>
    </div>

    <div class="text-center">
        <button type="submit" name="submit_report" class="btn btn-danger px-4">Submit Report</button>
    </div>
</form>

</div>

<?php include('../../../../includes/layout/footer.php'); ?>
