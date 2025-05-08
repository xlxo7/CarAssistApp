<?php
require_once('../../../../controllers/mechanic/MechanicOrderReportController.php');
include('../../../../includes/layout/header.php');

// استخدام الكلاس
$controller = new MechanicOrderReportController();
$result = $controller->getCompletedOrders();
?>

<div class="container my-5">
    <h2 class="fw-bold text-center mb-4">Previous Completed Orders</h2>

    <?php if ($result->num_rows === 0): ?>
        <div class="alert alert-info text-center">You have no completed orders yet.</div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-bordered text-center">
                <thead class="table-dark">
                    <tr>
                        <th>Order ID</th>
                        <th>Customer Name</th>
                        <th>Location</th>
                        <th>Scheduled Time</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= $row['id'] ?></td>
                            <td><?= htmlspecialchars($row['customer_name']) ?></td>
                            <td><?= htmlspecialchars($row['location']) ?></td>
                            <td><?= htmlspecialchars($row['scheduled_time']) ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<?php include('../../../../includes/layout/footer.php'); ?>
