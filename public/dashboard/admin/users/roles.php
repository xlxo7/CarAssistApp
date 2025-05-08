<?php
include('../../../../includes/header.php');
require_once('../../../../controllers/admin/RoleController.php');

session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin') {
  header("Location: /car_assist_app/public/auth/login/login.php");
  exit;
}

$controller = new RoleController();

// إضافة دور
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_role'])) {
  $roleName = trim($_POST['role_name']);
  if (!empty($roleName)) {
    $controller->addRole($roleName);
  }
}

// حذف دور
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_role'])) {
  $roleId = (int)$_POST['role_id'];
  $controller->deleteRole($roleId);
}

$roles = $controller->getAllRoles();
?>

<div class="container my-5">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold text-dark">Role Management</h2>
    <form class="d-flex gap-2" method="POST">
      <input type="text" name="role_name" class="form-control" placeholder="New Role" required>
      <button type="submit" name="add_role" class="btn btn-primary px-4">Add</button>
    </form>
  </div>

  <div class="row g-4">
    <?php while ($row = $roles->fetch_assoc()): ?>
      <div class="col-md-4">
        <div class="card shadow-sm border-0 text-center p-4" style="background-color: #fff; border-radius: 16px;">
          <i class="bi bi-person-badge fs-1 mb-3 text-primary"></i>
          <h5 class="fw-bold text-dark"><?= htmlspecialchars($row['name']) ?></h5>
          <form method="POST" onsubmit="return confirm('Are you sure you want to delete this role?')">
            <input type="hidden" name="role_id" value="<?= $row['id'] ?>">
            <button type="submit" name="delete_role" class="btn btn-outline-danger btn-sm mt-2">Delete</button>
          </form>
        </div>
      </div>
    <?php endwhile; ?>
  </div>
</div>

<?php include('../../../../includes/footer.php'); ?>
