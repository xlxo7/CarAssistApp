<?php
session_start();
require_once('../../../../controllers/mechanic/MechanicOrderController.php');
include('../../../../includes/layout/header.php');

// استخدام الكلاس
$controller = new MechanicOrderController();
$requests = $controller->getNewRequests();
?>

<div class="container my-5">
    <h2 class="fw-bold text-center mb-4">New Service Requests</h2>

    <?php if ($requests->num_rows === 0): ?>
        <div class="alert alert-info text-center">No new requests at the moment.</div>
    <?php else: ?>
        <div class="row g-4">
            <?php while ($row = $requests->fetch_assoc()):
                ?>
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
                        <form method="POST" action="accept_request.php" class="d-flex gap-2">
                            <input type="hidden" name="order_id" value="<?= $row['id'] ?>">
                            <button type="submit" name="accept" class="btn btn-success btn-sm w-50">Accept</button>
                            <button type="submit" name="reject" class="btn btn-danger btn-sm w-50">Reject</button>
                        </form>

                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    <?php endif; ?>
</div>

<?php include('../../../../includes/layout/footer.php'); ?>
