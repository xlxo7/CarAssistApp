<?php
include('../../../../includes/layout/header.php');
require_once('../../../../controllers/admin/MechanicEditController.php');

// التأكد من الصلاحيات
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin') {
    header("Location: /car_assist_app/public/auth/login/login.php");
    exit;
}

$controller = new MechanicEditController();

// جلب ID
$mechanic_id = $_GET['id'] ?? null;
if (!$mechanic_id) {
    header("Location: users.php");
    exit;
}

// جلب البيانات
$mechanic = $controller->getMechanicById((int)$mechanic_id);
if (!$mechanic) {
    header("Location: users.php");
    exit;
}

// عند التعديل
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_mechanic'])) {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $controller->updateMechanic((int)$mechanic_id, $name, $email, $phone);
}
?>

<div class="container my-5">
  <h2 class="fw-bold mb-4 text-dark">Edit Mechanic</h2>

  <form method="POST">
    <div class="mb-3">
      <label for="name" class="form-label">Name</label>
      <input type="text" name="name" id="name" class="form-control" value="<?= htmlspecialchars($mechanic['name']) ?>" required>
    </div>

    <div class="mb-3">
      <label for="email" class="form-label">Email</label>
      <input type="email" name="email" id="email" class="form-control" value="<?= htmlspecialchars($mechanic['email']) ?>" required>
    </div>

    <div class="mb-3">
      <label for="phone" class="form-label">Phone</label>
      <input type="text" name="phone" id="phone" class="form-control" value="<?= htmlspecialchars($mechanic['phone']) ?>" required>
    </div>

    <button type="submit" name="update_mechanic" class="btn btn-primary">Update</button>
  </form>
</div>

<?php include('../../../../includes/layout/footer.php'); ?>
