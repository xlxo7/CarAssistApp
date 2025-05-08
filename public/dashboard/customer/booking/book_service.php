<?php
session_start();
include('../../../../includes/layout/header.php');

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'customer') {
    header("Location: /car_assist_app/public/auth/login.php");
    exit;
}

require_once('../../../../controllers/customer/ServiceController.php');
$serviceController = new ServiceController();
$services_result = $serviceController->getAllServices();
?>

<div class="container my-5">
    <h2 class="text-center fw-bold mb-4">Book a New Service</h2>

    <form action="select_mechanic.php" method="POST">
        <!-- اختيار الموقع من الخريطة -->
        <div class="mb-3">
            <label class="form-label">Select Your Location</label>
            <div id="map" style="height: 300px;"></div>
            <button type="button" class="btn btn-sm btn-outline-secondary mt-2" onclick="resetMap()">Reset Location</button>
        </div>

        <!-- إحداثيات مخفية -->
        <input type="hidden" name="latitude" id="latitude">
        <input type="hidden" name="longitude" id="longitude">

        <!-- اختيار الخدمة -->
        <div class="mb-3">
            <label for="service" class="form-label">Select Service</label>
            <select name="service_id" id="service" class="form-select" required>
                <option value="" disabled selected>-- Choose a Service --</option>
                <?php if ($services_result && $services_result->num_rows > 0): ?>
                    <?php while ($row = $services_result->fetch_assoc()): ?>
                        <option value="<?= $row['id'] ?>"><?= htmlspecialchars($row['name']) ?></option>
                    <?php endwhile; ?>
                <?php else: ?>
                    <option disabled>No services available</option>
                <?php endif; ?>
            </select>
        </div>

        <div class="d-grid">
            <button type="submit" class="btn btn-primary">Find Mechanics</button>
        </div>
    </form>
</div>

<!-- Leaflet CSS & JS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

<script>
  const defaultLocation = [30.0444, 31.2357]; // Cairo
  const map = L.map('map').setView(defaultLocation, 10);
  const marker = L.marker(defaultLocation, { draggable: true }).addTo(map);

  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      attribution: '&copy; OpenStreetMap contributors'
  }).addTo(map);

  function updateLatLng(lat, lng) {
    document.getElementById('latitude').value = lat.toFixed(6);
    document.getElementById('longitude').value = lng.toFixed(6);
  }

  function resetMap() {
    map.setView(defaultLocation, 10);
    marker.setLatLng(defaultLocation);
    updateLatLng(...defaultLocation);
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

<?php include('../../../../includes/layout/footer.php'); ?>
