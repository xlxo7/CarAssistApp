<?php 
session_start(); 
include('../../../includes/layout/header.php'); 
?>

<div class="container my-5">
  <h2 class="text-center mb-4">Forgot Password</h2>

  <?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-danger text-center">
      <?= $_SESSION['error']; unset($_SESSION['error']); ?>
    </div>
  <?php endif; ?>

  <?php if (isset($_SESSION['success'])): ?>
    <div class="alert alert-success text-center">
      <?= $_SESSION['success']; unset($_SESSION['success']); ?>
    </div>
  <?php endif; ?>

  <form method="POST" action="/car_assist_app/public/auth/login/forgot_verify.php" class="mx-auto" style="max-width: 500px;">
    <div class="mb-3">
      <label for="email" class="form-label">Enter your registered email</label>
      <input type="email" name="email" id="email" class="form-control" required placeholder="you@example.com">
    </div>

    <div class="d-grid">
      <button type="submit" name="forgot" class="btn btn-primary">Reset Password</button>
    </div>
  </form>
</div>

<?php include('../../../includes/layout/footer.php'); ?>
