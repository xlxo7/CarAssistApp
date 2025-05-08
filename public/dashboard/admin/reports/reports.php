<?php 
include('../../../../includes/layout/header.php');

// التأكد من أن المستخدم أدمن
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin') {
    header("Location: /car_assist_app/public/auth/login/login.php");
    exit;
}

// استدعاء الكنترولر الجديد
require_once('../../../../controllers/admin/AdminDashboardController.php');
$controller = new AdminDashboardController();
$stats = $controller->getStatistics();
?>

<div class="container my-5">
    <h2 class="fw-bold text-dark text-center mb-4">System Reports</h2>

    <div class="row g-4">
        <div class="col-md-4">
            <a href="approved_mechanics.php" class="text-decoration-none">
                <div class="card text-center shadow-sm p-4 hover-card">
                    <i class="bi bi-person-badge fs-1 mb-2 text-primary"></i>
                    <h5 class="fw-bold text-dark">Approved Mechanics</h5>
                    <p class="text-muted"><?= $stats['mechanics'] ?> Mechanics</p>
                </div>
            </a>
        </div>

        <div class="col-md-4">
            <a href="customers_list.php" class="text-decoration-none">
                <div class="card text-center shadow-sm p-4 hover-card">
                    <i class="bi bi-person fs-1 mb-2 text-primary"></i>
                    <h5 class="fw-bold text-dark">Total Customers</h5>
                    <p class="text-muted"><?= $stats['customers'] ?> Customers</p>
                </div>
            </a>
        </div>

        <div class="col-md-4">
            <a href="completed_orders.php" class="text-decoration-none">
                <div class="card text-center shadow-sm p-4 hover-card">
                    <i class="bi bi-file-earmark-bar-graph fs-1 mb-2 text-primary"></i>
                    <h5 class="fw-bold text-dark">Completed Orders</h5>
                    <p class="text-muted"><?= $stats['orders'] ?> Orders</p>
                </div>
            </a>
        </div>
    </div>

    <hr>

    <!-- تقارير إضافية -->
    <div class="row g-4 mt-3">
        <div class="col-md-4">
            <a href="customer_reports.php" class="text-decoration-none">
                <div class="card text-center shadow-sm p-4 hover-card">
                    <i class="bi bi-flag fs-1 mb-2 text-danger"></i>
                    <h5 class="fw-bold text-dark">Customer Reports</h5>
                    <p class="text-muted">Reports submitted by mechanics</p>
                </div>
            </a>
        </div>
    </div>
</div>

<?php include('../../../../includes/layout/footer.php'); ?>
