<?php include('../../../includes/layout/header.php'); ?>

<?php

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin') {
  header("Location: /car_assist_app/public/auth/login.php");
    exit;
}

$admin_name = $_SESSION['user_name'];
?>

<div class="container my-5">
  <h2 class="mb-4 fw-bold text-dark">Welcome, <?= htmlspecialchars($admin_name) ?> 👋</h2>

  <div class="row g-4">
    <!-- Manage Users -->
    <div class="col-md-3">
      <a href="users/users.php" class="text-decoration-none text-dark">
        <div class="card text-center shadow-sm p-4" style="min-height: 200px;">
          <i class="bi bi-people fs-1 mb-2 text-primary"></i>
          <h5 class="fw-bold">Manage Users</h5>
          <p class="text-muted small">View and manage all system users</p>
        </div>
      </a>
    </div>

    <!-- Reports -->
    <div class="col-md-3">
      <a href="reports/reports.php" class="text-decoration-none text-dark">
        <div class="card text-center shadow-sm p-4" style="min-height: 200px;">
          <i class="bi bi-file-earmark-bar-graph fs-1 mb-2 text-primary"></i>
          <h5 class="fw-bold">Reports</h5>
          <p class="text-muted small">System usage and analytics</p>
        </div>
      </a>
    </div>

    <!-- Mechanic Approvals -->
    <div class="col-md-3">
      <a href="approvals/mechanic_approvals.php" class="text-decoration-none text-dark">
        <div class="card text-center shadow-sm p-4" style="min-height: 200px;">
          <i class="bi bi-person-check fs-1 mb-2 text-primary"></i>
          <h5 class="fw-bold">Mechanic Approvals</h5>
          <p class="text-muted small">Approve or reject mechanic registrations</p>
        </div>
      </a>
    </div>

    <!-- Feedback -->
    <div class="col-md-3">
      <a href="feedback/feedback.php" class="text-decoration-none text-dark">
        <div class="card text-center shadow-sm p-4" style="min-height: 200px;">
          <i class="bi bi-chat-square-text fs-1 mb-2 text-primary"></i>
          <h5 class="fw-bold">Feedback</h5>
          <p class="text-muted small">View feedback and complaints</p>
        </div>
      </a>
    </div>
  </div>
</div>

<?php include('../../../includes/layout/footer.php'); ?>
