<?php
session_start();
include('../../../../includes/layout/header.php');

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'customer') {
    header("Location: ../../../auth/login/login.php");
    exit;
}

$mechanic_id = $_GET['id'] ?? null;

if (!$mechanic_id) {
    echo "<div class='container my-5 alert alert-danger text-center'>Mechanic ID is missing.</div>";
    include('../../../../includes/layout/footer.php');
    exit;
}

require_once('../../../../controllers/customer/MechanicProfileController.php');
$controller = new MechanicProfileController();

// 1. بيانات الميكانيكي
$mechanic = $controller->getMechanic((int)$mechanic_id);

if (!$mechanic) {
    echo "<div class='container my-5 alert alert-danger text-center'>Mechanic not found.</div>";
    include('../../../../includes/layout/footer.php');
    exit;
}

// 2. الخدمات
$services = $controller->getServices((int)$mechanic_id);

// 3. التقييمات
$rating = $controller->getRating((int)$mechanic_id);
$avg_rating = $rating['avg'] ?? "Not Rated";
$total_ratings = $rating['count'];
?>

<div class="container my-5">
    <div class="card shadow-sm p-4">
        <h2 class="fw-bold mb-3"><?= htmlspecialchars($mechanic['name']) ?></h2>

        <p><strong>Phone:</strong> <?= htmlspecialchars($mechanic['phone']) ?></p>
        <p><strong>Status:</strong> 
            <?= $mechanic['availability'] === 'available' 
                ? "<span class='text-success'>Available</span>" 
                : "<span class='text-danger'>Busy</span>" ?>
        </p>
        <p><strong>Services:</strong> <?= implode(', ', $services) ?: 'None listed' ?></p>
        <p><strong>Rating:</strong> 
            <?= is_numeric($avg_rating) ? "$avg_rating ★ ($total_ratings ratings)" : $avg_rating ?>
        </p>

        <a href="javascript:history.back()" class="btn btn-secondary mt-3">⬅ Back</a>
    </div>
</div>

<?php include('../../../../includes/layout/footer.php'); ?>
