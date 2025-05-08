<?php
session_start();
include('../../../../includes/layout/header.php');
require_once('../../../../controllers/customer/OrderController.php');

// تحقق من دخول العميل
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'customer') {
    header("Location: ../../../auth/login/login.php");
    exit;
}

$controller = new OrderController();
$orders = $controller->getActiveTrackingOrders((int)$_SESSION['user_id']);
?>

<div class="container my-5">
    <h2 class="fw-bold text-center mb-4">Track Your Active Orders</h2>

    <?php if (isset($_SESSION['flash_message'])): ?>
        <div class="alert alert-info text-center">
            <?= $_SESSION['flash_message']; unset($_SESSION['flash_message']); ?>
        </div>
    <?php endif; ?>

    <?php if ($orders->num_rows === 0): ?>
        <div class="alert alert-info text-center">No active orders to track.</div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-bordered text-center align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Service</th>
                        <th>Mechanic</th>
                        <th>Status</th>
                        <th>Requested At</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 1; while ($row = $orders->fetch_assoc()): ?>
                        <tr>
                            <td><?= $i++ ?></td>
                            <td><?= htmlspecialchars($row['service_name']) ?></td>
                            <td><?= htmlspecialchars($row['mechanic_name'] ?? 'Pending Assignment') ?></td>
                            <td>
                                <span class="badge 
                                    <?= match($row['status']) {
                                        'pending'     => 'bg-secondary',
                                        'accepted'    => 'bg-warning text-dark',
                                        'on_the_way'  => 'bg-info text-dark',
                                        default       => 'bg-light text-dark'
                                    } ?>">
                                    <?php
                                        $icon = match($row['status']) {
                                            'pending'     => '<i class="bi bi-hourglass-split me-1"></i>',
                                            'accepted'    => '<i class="bi bi-check2-square me-1"></i>',
                                            'on_the_way'  => '<i class="bi bi-truck me-1"></i>',
                                            default       => ''
                                        };
                                        echo $icon . ucfirst($row['status']);
                                    ?>
                                </span>
                            </td>
                            <td><?= date('Y-m-d H:i', strtotime($row['created_at'])) ?></td>
                            <td>
                                <?php if ($row['status'] === 'pending'): ?>
                                    <form method="POST" action="cancel_order.php" class="d-inline">
                                        <input type="hidden" name="order_id" value="<?= $row['id'] ?>">
                                        <button class="btn btn-sm btn-danger">Cancel</button>
                                    </form>
                                <?php else: ?>
                                    <span class="text-muted">—</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<?php include('../../../../includes/layout/footer.php'); ?>
