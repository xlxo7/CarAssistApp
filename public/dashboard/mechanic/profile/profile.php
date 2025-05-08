<?php
require_once('../../../../controllers/mechanic/ProfileController.php');
include('../../../../includes/layout/header.php');

$controller = new ProfileController();
$mechanic_id = $_SESSION['user_id'];
$mechanic = $controller->getMechanicProfile($mechanic_id);
?>

<div class="container my-5">
    <h2 class="fw-bold text-center mb-4">My Profile</h2>

    <div class="card p-4 shadow-sm mx-auto" style="max-width: 500px;">
        <p><strong>Name:</strong> <?= htmlspecialchars($mechanic['name']) ?></p>
        <p><strong>Phone Number:</strong> <?= htmlspecialchars($mechanic['phone']) ?></p>
        <p>
            <strong>Availability:</strong>
            <span class="badge <?= $mechanic['availability'] === 'available' ? 'bg-success' : 'bg-secondary' ?>">
                <?= ucfirst($mechanic['availability']) ?>
            </span>
        </p>
    </div>
</div>

<?php include('../../../../includes/layout/footer.php'); ?>
