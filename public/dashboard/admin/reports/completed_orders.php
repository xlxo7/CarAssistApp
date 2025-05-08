<?php
require_once('../../../../controllers/admin/OrderController.php');
include('../../../../includes/layout/header.php');

$controller = new OrderController();
$orders = $controller->getCompletedOrders();
?>

<div class="container my-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Completed Orders</h2>
        <button onclick="window.print()" class="btn btn-primary">Print Report</button>
    </div>

    <table class="table table-bordered text-center">
        <thead class="table-dark">
            <tr>
                <th>#</th>
                <th>Customer Name</th>
                <th>Mechanic Name</th>
                <th>Order Date</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($orders as $index => $row): ?>
                <tr>
                    <td><?= $index + 1 ?></td>
                    <td><?= htmlspecialchars($row['customer_name']) ?></td>
                    <td><?= htmlspecialchars($row['mechanic_name']) ?></td>
                    <td><?= htmlspecialchars($row['created_at']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include('../../../../includes/layout/footer.php'); ?>
