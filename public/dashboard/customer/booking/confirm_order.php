<?php
session_start();
include('../../../../includes/layout/header.php');

require_once('../../../../controllers/customer/BookingController.php');

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'customer') {
    header("Location: ../../../auth/login/login.php");
    exit;
}

$controller = new BookingController();
$customer_id = $_SESSION['user_id'];

// استقبال البيانات
$mechanic_id = $_POST['mechanic_id'] ?? null;
$service_id = $_POST['service_id'] ?? null;
$latitude = $_POST['latitude'] ?? null;
$longitude = $_POST['longitude'] ?? null;

// تأكيد الحجز
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm_booking'])) {
    $result = $controller->createBooking($customer_id, $_POST);
    if ($result === true) {
        $_SESSION['success'] = "Service request sent successfully.";
        header("Location: ../orders/track_order.php");
        exit;
    } else {
        $_SESSION['error'] = $result;
    }
}

// التحقق من البيانات المرسلة من الصفحة السابقة
if (!$mechanic_id || !$service_id || !$latitude || !$longitude) {
    $_SESSION['error'] = "Incomplete booking data.";
    header("Location: book_service.php");
    exit;
}

// جلب بيانات العرض
$mechanic = $controller->getMechanic((int)$mechanic_id);
$service = $controller->getService((int)$service_id);

if (!$mechanic || !$service) {
    $_SESSION['error'] = "Invalid mechanic or service.";
    header("Location: book_service.php");
    exit;
}
?>

<div class="container my-5">
    <h2 class="fw-bold text-center mb-4">Confirm Your Booking</h2>

    <div class="card shadow-sm p-4 mx-auto" style="max-width: 500px;">
        <p><strong>Service:</strong> <?= htmlspecialchars($service['name']) ?></p>
        <p><strong>Mechanic:</strong> <?= htmlspecialchars($mechanic['name']) ?></p>
        <p><strong>Phone:</strong> <?= htmlspecialchars($mechanic['phone']) ?></p>
        <p><strong>Location:</strong> <?= round($latitude, 6) ?>, <?= round($longitude, 6) ?></p>

        <form method="POST">
            <input type="hidden" name="mechanic_id" value="<?= $mechanic_id ?>">
            <input type="hidden" name="service_id" value="<?= $service_id ?>">
            <input type="hidden" name="latitude" value="<?= $latitude ?>">
            <input type="hidden" name="longitude" value="<?= $longitude ?>">

            <div class="d-grid mt-4">
                <button type="submit" name="confirm_booking" class="btn btn-primary">Confirm Booking</button>
            </div>
        </form>
    </div>
</div>

<?php include('../../../../includes/layout/footer.php'); ?>
