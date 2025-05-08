<?php

require_once('../../../controllers/auth/ForgotPasswordController.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['forgot'])) {
    $email = $_POST['email'] ?? '';
    $controller = new ForgotPasswordController();
    $controller->handleResetRequest($email);
} else {
    session_start();
    $_SESSION['error'] = "Invalid request.";
    header("Location: forgot_password.php");
    exit;
}
