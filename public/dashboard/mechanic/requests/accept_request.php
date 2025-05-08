<?php
session_start();
require_once('../../../../controllers/mechanic/MechanicOrderController.php');

$controller = new MechanicOrderController();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $orderId = intval($_POST['order_id']);

    if (isset($_POST['accept'])) {
        $controller->acceptRequest($orderId);
    } elseif (isset($_POST['reject'])) {
        $controller->rejectRequest($orderId);
    } else {
        $_SESSION['error'] = "No action specified.";
        header("Location: new_requests.php");
        exit;
    }
} else {
    $_SESSION['error'] = "Invalid request method.";
    header("Location: new_requests.php");
    exit;
}
