<?php
session_start();
require_once('../../../../controllers/customer/OrderController.php');

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'customer') {
    header("Location: ../../../auth/login/login.php");
    exit;
}

$customer_id = $_SESSION['user_id'];
$order_id = $_POST['order_id'] ?? null;

if (!$order_id) {
    $_SESSION['flash_message'] = "Invalid request.";
    header("Location: track_order.php");
    exit;
}

$controller = new OrderController();
$message = $controller->cancelOrder((int)$order_id, (int)$customer_id);

$_SESSION['flash_message'] = $message;
header("Location: track_order.php");
exit;
