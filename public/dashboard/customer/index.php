<?php
session_start();
include('../../../includes/layout/header.php');

// تأكيد تسجيل الدخول
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'customer') {
  header("Location: /car_assist_app/public/auth/login.php");
    exit;
}

$customer_name = $_SESSION['user_name'];
?>

<div class="container my-5">
    <h2 class="fw-bold mb-4 text-center">Welcome, <?= htmlspecialchars($customer_name) ?> 👋</h2>

    <?php if (isset($_SESSION['flash_message'])): ?>
        <div class="alert alert-success text-center">
            <?= $_SESSION['flash_message']; unset($_SESSION['flash_message']); ?>
        </div>
    <?php endif; ?>


    <div class="row g-4">
        <!-- Book New Service -->
        <div class="col-md-3">
            <a href="booking/book_service.php" class="text-decoration-none text-dark">
                <div class="card text-center shadow-sm p-4 hover-card">
                    <i class="bi bi-geo-alt fs-1 text-primary"></i>
                    <h5 class="fw-bold mt-2">Book Service</h5>
                    <p class="text-muted">Request new car assistance</p>
                </div>
            </a>
        </div>

        <!-- Track Order -->
        <div class="col-md-3">
            <a href="orders/track_order.php" class="text-decoration-none text-dark">
                <div class="card text-center shadow-sm p-4 hover-card">
                    <i class="bi bi-map fs-1 text-info"></i>
                    <h5 class="fw-bold mt-2">Track Order</h5>
                    <p class="text-muted">Follow your active service status</p>
                </div>
            </a>
        </div>



        <!-- Order History -->
        <div class="col-md-3">
            <a href="orders/order_history.php" class="text-decoration-none text-dark">
                <div class="card text-center shadow-sm p-4 hover-card">
                    <i class="bi bi-clock-history fs-1 text-success"></i>
                    <h5 class="fw-bold mt-2">Order History</h5>
                    <p class="text-muted">Track all your previous orders</p>
                </div>
            </a>
        </div>

        <!-- Profile -->
        <div class="col-md-3">
            <a href="profile/profile.php" class="text-decoration-none text-dark">
                <div class="card text-center shadow-sm p-4 hover-card">
                    <i class="bi bi-person-lines-fill fs-1 text-warning"></i>
                    <h5 class="fw-bold mt-2">My Profile</h5>
                    <p class="text-muted">View or update your information</p>
                </div>
            </a>
        </div>

        <!-- Report Mechanic -->
        <div class="col-md-3">
            <a href="report_mechanic.php" class="text-decoration-none text-dark">
                <div class="card text-center shadow-sm p-4 hover-card">
                    <i class="bi bi-flag fs-1 text-danger"></i>
                    <h5 class="fw-bold mt-2">Report Mechanic</h5>
                    <p class="text-muted">Behavioral issue report</p>
                </div>
            </a>
        </div>
    </div>
</div>

<?php include('../../../includes/layout/footer.php'); ?>
