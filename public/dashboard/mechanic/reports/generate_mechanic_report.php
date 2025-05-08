<?php
require_once('../../../../controllers/mechanic/MechanicPDFReportController.php');

// التأكد من أن المستخدم ميكانيكي
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'mechanic') {
    header("Location: /car_assist_app/public/auth/login/login.php");
    exit;
}

$mechanic_id = $_SESSION['user_id'];

$report = new MechanicPDFReportController();
$report->generate($mechanic_id);
