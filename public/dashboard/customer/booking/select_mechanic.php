<?php
session_start();
include('../../../../includes/layout/header.php');

require_once('../../../../controllers/customer/MechanicSelectionController.php');

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'customer') {
    header("Location: ../../../auth/login/login.php");
    exit;
}

$lat = $_POST['latitude'] ?? null;
$lng = $_POST['longitude'] ?? null;
$service_id = $_POST['service_id'] ?? null;
$search_name = $_POST['search_name'] ?? null;
$sort_by = $_POST['sort_by'] ?? 'distance';

if (!$lat || !$lng || !$service_id) {
    $_SESSION['error'] = "Please provide location and service.";
    header("Location: book_service.php");
    exit;
}

$controller = new MechanicSelectionController();
$mechanics = $controller->getAvailableMechanics([
    'latitude' => $lat,
    'longitude' => $lng,
    'service_id' => $service_id,
    'search_name' => $search_name,
    'sort_by' => $sort_by
]);
?>

<div class="container my-5">
    <h2 class="text-center fw-bold mb-4">Available Mechanics Nearby</h2>

    <!-- نموذج البحث والفرز -->
    <form method="POST" class="row g-3 mb-4">
        <input type="hidden" name="latitude" value="<?= $lat ?>">
        <input type="hidden" name="longitude" value="<?= $lng ?>">
        <input type="hidden" name="service_id" value="<?= $service_id ?>">

        <div class="col-md-6">
            <input type="text" name="search_name" class="form-control" placeholder="Search mechanic by name..." value="<?= htmlspecialchars($search_name ?? '') ?>">
        </div>

        <div class="col-md-4">
            <select name="sort_by" class="form-select">
                <option value="distance" <?= $sort_by === 'distance' ? 'selected' : '' ?>>Sort by Distance</option>
                <option value="rating" <?= $sort_by === 'rating' ? 'selected' : '' ?>>Sort by Rating</option>
            </select>
        </div>

        <div class="col-md-2">
            <button class="btn btn-outline-primary w-100" type="submit">Search</button>
        </div>
    </form>

    <?php if ($mechanics->num_rows === 0): ?>
        <div class="alert alert-info text-center">
            No mechanics available near you.
        </div>
    <?php else: ?>
        <div class="row g-4">
            <?php while ($row = $mechanics->fetch_assoc()): ?>
                <div class="col-md-4">
                    <div class="card shadow-sm p-4 h-100 d-flex flex-column justify-content-between">
                        <div>
                            <h5 class="fw-bold"><?= htmlspecialchars($row['name']) ?></h5>
                            <p><strong>Phone:</strong> <?= htmlspecialchars($row['phone']) ?></p>
                            <p><strong>Distance:</strong> <?= round($row['distance'], 2) ?> km</p>
                            <p><strong>Rating:</strong> <?= $row['avg_rating'] ? round($row['avg_rating'], 2) . " ★" : "Not Rated" ?></p>
                            <p>
                                <strong>Status:</strong>
                                <?php if ($row['availability'] === 'available'): ?>
                                    <span class="text-success">Available</span>
                                <?php else: ?>
                                    <span class="text-danger">Busy</span>
                                <?php endif; ?>
                            </p>
                        </div>

                        <div class="d-grid gap-2 mt-3">
                            <?php if ($row['availability'] === 'available'): ?>
                                <form action="confirm_order.php" method="POST">
                                    <input type="hidden" name="mechanic_id" value="<?= $row['id'] ?>">
                                    <input type="hidden" name="service_id" value="<?= $service_id ?>">
                                    <input type="hidden" name="latitude" value="<?= $lat ?>">
                                    <input type="hidden" name="longitude" value="<?= $lng ?>">
                                    <button type="submit" class="btn btn-primary">Select Mechanic</button>
                                </form>
                            <?php else: ?>
                                <button class="btn btn-secondary" disabled>Currently Busy</button>
                            <?php endif; ?>

                            <a href="mechanic_profile.php?id=<?= $row['id'] ?>" class="btn btn-outline-secondary">View Profile</a>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    <?php endif; ?>
</div>

<?php include('../../../../includes/layout/footer.php'); ?>
