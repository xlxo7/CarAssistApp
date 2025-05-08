<?php
require_once('../../../controllers/mechanic/MechanicDashboardController.php');
include('../../../includes/layout/header.php');

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'mechanic') {
    header("Location: ../../../auth/login/login.php");
    exit;
}

$controller = new MechanicDashboardController();
$user = $controller->getMechanicData((int)$_SESSION['user_id']);

if (!$user) {
    $_SESSION['error'] = "User not found.";
    header("Location: ../../../auth/login/login.php");
    exit;
}
?>

<div class="container my-5">
    <h2 class="fw-bold mb-4 text-center">Welcome, <?= htmlspecialchars($user['name']) ?> 👨‍🔧</h2>

    <div class="row g-4">
        <!-- باقي الكروت كما هي بدون تغيير -->
        <div class="col-md-3">
            <a href="requests/new_requests.php" class="text-decoration-none text-dark">
                <div class="card text-center shadow-sm p-4 hover-card">
                    <i class="bi bi-wrench-adjustable fs-1 text-primary"></i>
                    <h5 class="fw-bold">New Requests</h5>
                    <p class="text-muted">View and manage incoming orders</p>
                </div>
            </a>
        </div>

        <!-- باقي الكروت ... -->
    </div>
</div>

<?php include('../../../includes/layout/footer.php'); ?>
