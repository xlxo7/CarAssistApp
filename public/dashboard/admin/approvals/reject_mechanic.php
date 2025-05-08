<?php
require_once('../../../../controllers/admin/MechanicApprovalController.php');

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $controller = new MechanicApprovalController();
    $controller->reject((int)$_GET['id']);
} else {
    session_start();
    $_SESSION['error'] = "Invalid request.";
    header("Location: mechanic_approvals.php");
    exit;
}
?>