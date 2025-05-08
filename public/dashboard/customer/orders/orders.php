<?php
session_start();
include('../../../../includes/layout/header.php');
require_once('../../../../controllers/customer/OrderController.php');

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'customer') {
    header("Location: ../../../auth/login/login.php");
    exit;
}

$controller = new OrderController();
$orders = $controller->getAllOrdersWithRating((int)$_SESSION['user_id']);
?>

<div class="container my-5">
    <h2 class="text-center fw-bold mb-4">My Orders</h2>

    <?php if ($orders->num_rows === 0): ?>
        <div class="alert alert-info text-center">You have no service orders yet.</div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-bordered text-center align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>Order ID</th>
                        <th>Service</th>
                        <th>Mechanic</th>
                        <th>Status</th>
                        <th>Scheduled Time</th>
                        <th>Rating</th>
                        <th>Feedback</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $orders->fetch_assoc()): ?>
                        <tr>
                            <td><?= $row['id'] ?></td>
                            <td><?= htmlspecialchars($row['service_name']) ?></td>
                            <td><?= $row['mechanic_name'] ?? 'Not Assigned Yet' ?></td>
                            <td>
                                <span class="badge 
                                    <?php
                                        if ($row['status'] === 'pending') echo 'bg-warning';
                                        elseif ($row['status'] === 'accepted') echo 'bg-primary';
                                        elseif ($row['status'] === 'on_the_way') echo 'bg-info';
                                        elseif ($row['status'] === 'completed') echo 'bg-success';
                                        else echo 'bg-secondary';
                                    ?>">
                                    <?= ucfirst($row['status']) ?>
                                </span>
                            </td>
                            <td><?= htmlspecialchars($row['scheduled_time']) ?></td>
                            <td><?= $row['rating'] ? $row['rating'] . ' / 5' : '-' ?></td>
                            <td><?= $row['feedback'] ?? '-' ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<?php include('../../../../includes/layout/footer.php'); ?>