<?php
if (!isset($_SESSION)) {
  session_start();
}

$user_type = isset($_SESSION['user_type']) ? $_SESSION['user_type'] : 'guest';
$user_name = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'Guest';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>RoadFix</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="/car_assist_app/public/assets/css/style.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</head>
<body class="d-flex flex-column min-vh-100 bg-light" style="font-family: 'Segoe UI', sans-serif;">

<!-- Navbar -->
<nav class="navbar navbar-expand-lg bg-dark navbar-dark px-4 py-3" style="background-color: var(--primary) !important;">
  <div class="container-fluid">
    <a class="navbar-brand d-flex align-items-center gap-2" href="/car_assist_app/public/dashboard/<?= $user_type ?>/index.php" style="font-size: 1.7rem;">
      <i class="bi bi-tools fs-3 text-warning"></i>
      <span class="fw-bold" style="font-family: 'Segoe UI', sans-serif;">
        Road<span style="color: var(--secondary)">Fix</span> <?= ucfirst($user_type) ?>
      </span>
    </a>

    <div class="ms-auto d-flex align-items-center gap-3">
      <?php if ($user_type !== 'guest'): ?>
        <span class="text-white"><i class="bi bi-person-circle me-1"></i> <?= htmlspecialchars($user_name) ?></span>
        <a href="/car_assist_app/public/auth/login/logout.php" class="btn btn-outline-light btn-sm px-3 fw-semibold">Logout</a>
        <?php else: ?>
        <a href="/car_assist_app/public/auth/login/login.php" class="btn btn-outline-light btn-sm px-3 fw-semibold">Login</a>
      <?php endif; ?>
    </div>
  </div>
</nav>
