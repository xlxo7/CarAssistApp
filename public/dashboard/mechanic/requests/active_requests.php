<?php
session_start();
require_once('../../../../controllers/mechanic/MechanicOrderController.php');
include('../../../../includes/layout/header.php');

$controller = new MechanicOrderController();
$requests = $controller->getActiveRequests();
?>

<div class="container my-5">
    <h2 class="fw-bold text-center mb-4">Accepted Requests</h2>
    <?php if (isset($_SESSION['success'])): ?>
    <div class="alert alert-success text-center"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
<?php elseif (isset($_SESSION['error'])): ?>
    <div class="alert alert-danger text-center"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
<?php endif; ?>



    <?php if ($requests->num_rows === 0): ?>
        <div class="alert alert-info text-center">No accepted requests at the moment.</div>
    <?php else: ?>
        <div class="row g-4">
            <?php while ($row = $requests->fetch_assoc()): ?>
                <div class="col-md-6">
                    <div class="card shadow-sm p-4">
                        <h5 class="fw-bold">Request #<?= $row['id'] ?></h5>
                        <p><strong>Customer:</strong> <?= htmlspecialchars($row['customer_name']) ?></p>
                        <p>
                            <strong>Location:</strong>
                            <a href="https://www.google.com/maps?q=<?= $row['latitude'] ?>,<?= $row['longitude'] ?>" target="_blank">
                                View on Map
                            </a>
                        </p>
                        <p><strong>Scheduled Time:</strong> <?= htmlspecialchars($row['scheduled_time']) ?></p>
                        <a href="update_order_status.php?id=<?= $row['id'] ?>" class="btn btn-outline-primary w-100">Update Status</a>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    <?php endif; ?>
</div>

<?php include('../../../../includes/layout/footer.php'); ?>
