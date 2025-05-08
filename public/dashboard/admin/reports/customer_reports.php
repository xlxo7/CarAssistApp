<?php
require_once('../../../../controllers/admin/CustomerReportController.php');
include('../../../../includes/layout/header.php');

$controller = new CustomerReportController();
$reports = $controller->getAllReports();

// السماح فقط للأدمن
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin') {
    header("Location: /car_assist_app/public/auth/login/login.php");
    exit;
}
?>

<div class="container my-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Customer Reports</h2>
        <button onclick="window.print()" class="btn btn-primary">Print Report</button>
    </div>

    <table class="table table-bordered text-center">
        <thead class="table-dark">
            <tr>
                <th>#</th>
                <th>Mechanic</th>
                <th>Customer</th>
                <th>Reason</th>
                <th>Status</th>
                <th>Reported At</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($reports as $i => $row): ?>
                <tr>
                    <td><?= $i + 1 ?></td>
                    <td><?= htmlspecialchars($row['mechanic_name']) ?></td>
                    <td><?= htmlspecialchars($row['customer_name']) ?></td>
                    <td><?= htmlspecialchars($row['reason']) ?></td>
                    <td>
                        <span class="badge 
                            <?= $row['status'] === 'approved' ? 'bg-success' : ($row['status'] === 'rejected' ? 'bg-danger' : 'bg-warning') ?>">
                            <?= ucfirst($row['status']) ?>
                        </span>
                    </td>
                    <td><?= htmlspecialchars($row['created_at']) ?></td>
                    <td>
                        <a href="view_report.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-outline-info">Review</a>
                        <form action="../../../../controllers/admin/delete_report_controller.php" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure?')">
                            <input type="hidden" name="report_id" value="<?= $row['id'] ?>">
                            <button type="submit" name="delete_report" class="btn btn-sm btn-outline-danger">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include('../../../../includes/layout/footer.php'); ?>
