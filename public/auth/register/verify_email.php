<?php
require_once('../../../controllers/auth/EmailVerificationController.php');

// التحقق من التوكن
if (!isset($_GET['token']) || empty(trim($_GET['token']))) {
    session_start();
    $_SESSION['error'] = "Invalid verification link.";
    header("Location: ../login/login.php");
    exit;
}

// تنفيذ عملية التحقق
$token = trim($_GET['token']);
$controller = new EmailVerificationController();
$controller->verify($token);
