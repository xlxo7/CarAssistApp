<?php
require_once('../../../../controllers/mechanic/MechanicOrderStatusController.php');
include('../../../../includes/layout/header.php');

$controller = new MechanicOrderStatusController();

if (!isset($_GET['id'])) {
    $_SESSION['error'] = "Invalid request.";
    header("Location: new_requests.php");
    exit;
}

$order_id = intval($_GET['id']);
$order = $controller->getOrder($order_id);

if (!$order) {
    $_SESSION['error'] = "Order not found.";
    header("Location: new_requests.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['status'])) {
    $new_status = $_POST['status'];
    if ($controller->updateStatus($order_id, $new_status)) {
        $_SESSION['success'] = "Order status updated successfully.";
        header("Location: active_requests.php");
    } else {
        $_SESSION['error'] = "Invalid status selected.";
    }
    header("Location: active_requests.php");
    exit;
}
?>

<div class="container my-5">
    <h2 class="text-center mb-4">Update Order Status</h2>
    <p><strong>Order ID:</strong> <?= $order['id'] ?></p>
    <p><strong>Scheduled Time:</strong> <?= htmlspecialchars($order['scheduled_time']) ?></p>
    <p><strong>Current Status:</strong> <?= htmlspecialchars($order['status']) ?></p>

    <form method="POST">
        <div class="mb-3">
            <label for="status" class="form-label">Change Status</label>
            <select name="status" id="status" class="form-select" required>
                <option value="">Select Status</option>
                <option value="on_the_way">On the Way</option>
                <option value="completed">Completed</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Update Status</button>
    </form>
</div>

<?php include('../../../../includes/layout/footer.php'); ?>
