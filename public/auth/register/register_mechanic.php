<?php 
session_start(); 
include('../../../includes/layout/header.php'); 
require_once('../../../config/Database.php');
require_once('../../../controllers/mechanic/MechanicRegisterController.php');

$conn = Database::getInstance()->getConnection();

// ✅ تنفيذ المعالجة من نفس الصفحة
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register_mechanic'])) {
    $controller = new MechanicRegisterController();
    $controller->register($_POST, $_FILES);
}

// ✅ جلب الخدمات من قاعدة البيانات
$services_result = $conn->query("SELECT id, name FROM services");
?>

<div class="container my-5">
    <h2 class="text-center mb-4">Register as Mechanic</h2>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger text-center"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
    <?php endif; ?>

    <form action="" method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="name" class="form-label">Full Name</label>
            <input type="text" name="name" id="name" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email Address</label>
            <input type="email" name="email" id="email" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="phone" class="form-label">Phone Number</label>
            <input type="text" name="phone" id="phone" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" name="password" id="password" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="cv" class="form-label">Upload CV</label>
            <input type="file" name="cv" id="cv" class="form-control" accept=".pdf" required>
        </div>

        <!-- ✅ اختيار الخدمات -->
        <div class="mb-3">
            <label class="form-label">Select Services You Provide</label>
            <div class="form-check">
                <?php if ($services_result->num_rows > 0): ?>
                    <?php while ($service = $services_result->fetch_assoc()): ?>
                        <div>
                            <input class="form-check-input" type="checkbox" name="services[]" value="<?= $service['id'] ?>" id="service<?= $service['id'] ?>">
                            <label class="form-check-label" for="service<?= $service['id'] ?>">
                                <?= htmlspecialchars($service['name']) ?>
                            </label>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p class="text-muted">No services available. Please contact admin.</p>
                <?php endif; ?>
            </div>
        </div>

        <!-- ✅ خريطة تحديد الموقع -->
        <div class="mb-3">
            <label class="form-label">Select Your Location on Map</label>
            <div id="map" style="height: 300px;"></div>
        </div>

        <input type="hidden" name="latitude" id="latitude">
        <input type="hidden" name="longitude" id="longitude">

        <div class="text-center">
            <button type="submit" name="register_mechanic" class="btn btn-primary">Register</button>
        </div>
    </form>
</div>

<!-- Leaflet -->
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

<script>
  const defaultLocation = [30.0444, 31.2357];
  const map = L.map('map').setView(defaultLocation, 10);
  const marker = L.marker(defaultLocation, { draggable: true }).addTo(map);

  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      attribution: '&copy; OpenStreetMap contributors'
  }).addTo(map);

  function updateLatLng(lat, lng) {
    document.getElementById('latitude').value = lat.toFixed(6);
    document.getElementById('longitude').value = lng.toFixed(6);
  }

  updateLatLng(...defaultLocation);

  map.on('click', function(e) {
    marker.setLatLng(e.latlng);
    updateLatLng(e.latlng.lat, e.latlng.lng);
  });

  marker.on('dragend', function(e) {
    const position = marker.getLatLng();
    updateLatLng(position.lat, position.lng);
  });
</script>

<?php include('../../../includes/layout/footer.php'); ?>
