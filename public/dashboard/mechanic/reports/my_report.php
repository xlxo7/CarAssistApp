<?php
require_once('../../../../controllers/mechanic/MechanicOrderReportController.php');
include('../../../../includes/layout/header.php');

// تحميل الطلبات من خلال الكلاس
$controller = new MechanicOrderReportController();
$result = $controller->getCompletedOrders();
$mechanic_id = $controller->getMechanicId();
?>

<div class="container my-5">
    <h2 class="text-center fw-bold mb-4">My Reports</h2>

    <?php if ($result->num_rows === 0): ?>
        <div class="alert alert-info text-center">No completed orders yet.</div>
    <?php else: ?>
        <table class="table table-bordered text-center">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Customer</th>
                    <th>Completed At</th>
                    <th>Rating</th>
                    <th>Feedback</th>
                </tr>
            </thead>
            <tbody>
                <?php $i = 1; while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $i++ ?></td>
                        <td><?= htmlspecialchars($row['customer_name']) ?></td>
                        <td><?= htmlspecialchars($row['completed_at']) ?></td>
                        <td><?= $row['rating'] ? $row['rating'] . " / 5" : 'N/A' ?></td>
                        <td><?= $row['feedback'] ?? '-' ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <div class="text-center mt-4">
            <form action="/car_assist_app/public/dashboard/mechanic/reports/generate_mechanic_report.php" method="POST" target="_blank">
                <input type="hidden" name="mechanic_id" value="<?= $mechanic_id ?>">
                <button type="submit" class="btn btn-outline-primary px-4">Generate PDF Report</button>
            </form>
        </div>
    <?php endif; ?>
</div>

<?php include('../../../../includes/layout/footer.php'); ?>
