<?php
include('../../../../includes/layout/header.php');
require_once('../../../../controllers/admin/UserManagementController.php');
require_once('../../../../controllers/admin/UserController.php');

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin') {
    header("Location: /car_assist_app/public/auth/login/login.php");
    exit;
}

// حذف مستخدم (OOP)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_user'])) {
    $userController = new UserController();
    $userController->deleteUser((int)$_POST['user_id']);
}

$controller = new UserManagementController();

$type_filter = $_GET['filter'] ?? '';
$search = trim($_GET['search'] ?? '');
$search_by = $_GET['search_by'] ?? '';

$users = $controller->getFilteredUsers($type_filter, $search_by, $search);
?>

<div class="container my-5">
  <h2 class="fw-bold mb-4 text-dark">Manage Users</h2>

  <!-- Search + Filter -->
  <form class="row g-3 mb-4" method="GET">
    <div class="col-md-3">
      <select name="search_by" class="form-select">
        <option value="name" <?= $search_by === 'name' ? 'selected' : '' ?>>Search by Name</option>
        <option value="email" <?= $search_by === 'email' ? 'selected' : '' ?>>Search by Email</option>
        <option value="phone" <?= $search_by === 'phone' ? 'selected' : '' ?>>Search by Phone</option>
        <option value="id" <?= $search_by === 'id' ? 'selected' : '' ?>>Search by ID</option>
      </select>
    </div>

    <div class="col-md-4">
      <input type="text" name="search" class="form-control" placeholder="Enter keyword..." value="<?= htmlspecialchars($search) ?>">
    </div>

    <div class="col-md-3">
      <select name="filter" class="form-select">
        <option value="">All Types</option>
        <option value="customer" <?= $type_filter === 'customer' ? 'selected' : '' ?>>Customer</option>
        <option value="mechanic" <?= $type_filter === 'mechanic' ? 'selected' : '' ?>>Mechanic</option>
      </select>
    </div>

    <div class="col-md-2">
      <button type="submit" class="btn btn-primary w-100">Filter</button>
    </div>
    <div class="col-md-12">
      <a href="users.php" class="btn btn-outline-secondary">Reset</a>
    </div>
  </form>

  <!-- Users Table -->
  <div class="table-responsive">
    <table class="table table-bordered bg-white shadow-sm align-middle">
      <thead class="table-light">
        <tr>
          <th>#</th>
          <th>Name</th>
          <th>Email</th>
          <th>Phone</th>
          <th>Type</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php if ($users && $users->num_rows > 0): $i = 1; ?>
          <?php while ($row = $users->fetch_assoc()): ?>
            <tr>
              <td><?= $i++ ?></td>
              <td><?= htmlspecialchars($row['name']) ?></td>
              <td><?= htmlspecialchars($row['email']) ?></td>
              <td><?= htmlspecialchars($row['phone']) ?></td>
              <td><span class="badge bg-secondary"><?= ucfirst($row['user_type']) ?></span></td>
              <td class="d-flex gap-2">
                <?php if ($row['user_type'] === 'mechanic'): ?>
                  <a href="edit_mechanic.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-outline-primary">Edit</a>
                <?php endif; ?>
                <form method="POST" action="" onsubmit="return confirm('Delete this user?')">
                  <input type="hidden" name="user_id" value="<?= $row['id'] ?>">
                  <button type="submit" name="delete_user" class="btn btn-sm btn-outline-danger">Delete</button>
                </form>
              </td>
            </tr>
          <?php endwhile; ?>
        <?php else: ?>
          <tr><td colspan="6" class="text-center">No users found.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<?php include('../../../../includes/layout/footer.php'); ?>
