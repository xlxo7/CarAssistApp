<?php
require_once('../../../../controllers/mechanic/AvailabilityController.php');
include('../../../../includes/layout/header.php');

$controller = new AvailabilityController();
$mechanic_id = $_SESSION['user_id'];

// Toggle
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['toggle'])) {
    $controller->toggleAvailability($mechanic_id);
    header("Location: availability.php");
    exit;
}

$current_status = $controller->getAvailabilityStatus($mechanic_id);
?>

<div class="container my-5">
    <h2 class="text-center fw-bold mb-4">My Availability</h2>

    <div class="card text-center shadow-sm p-4 mx-auto" style="max-width: 500px;">
        <p class="fs-4">
            Current Status:
            <span class="badge bg-<?= $current_status === 'available' ? 'success' : 'danger' ?>">
                <?= ucfirst($current_status) ?>
            </span>
        </p>

        <form method="POST">
            <button type="submit" name="toggle" class="btn btn-outline-primary">
                Toggle to <?= $current_status === 'available' ? 'Busy' : 'Available' ?>
            </button>
        </form>
    </div>
</div>

<?php include('../../../../includes/layout/footer.php'); ?>
