<?php
require_once('../../controllers/mechanic/ReportCustomerController.php');

$controller = new ReportCustomerController();
$controller->submit($_POST);
