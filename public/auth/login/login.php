<?php
session_start();

include('../../../includes/layout/header.php'); 
require_once __DIR__ . '/../../../controllers/auth/AuthController.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    $auth = new AuthController();
    $auth->login($email, $password);
}
?>

<div class="container my-5">
  <h2 class="fw-bold text-dark text-center mb-4">Login to RoadFix</h2>

  <?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-danger text-center mt-3">
      <?= $_SESSION['error']; unset($_SESSION['error']); ?>
    </div>
  <?php endif; ?>

  <!-- ✅ تغيير مسار الفورم إلى كلاس OOP جديد -->
  <form action="/car_assist_app/public/auth/login/login.php" method="POST">
  <div class="col-md-6 mx-auto">
      <div class="mb-3">
        <label for="email" class="form-label">Email address</label>
        <input type="email" class="form-control" id="email" name="email" required placeholder="you@example.com">
      </div>

      <div class="mb-3">
        <label for="password" class="form-label">Password</label>
        <input type="password" class="form-control" id="password" name="password" required placeholder="••••••••">
      </div>

      <div class="d-grid mb-3">
        <button type="submit" name="login" class="btn btn-primary">Login</button>
      </div>

      <div class="text-center small">
        <a href="forgot_password.php" class="text-decoration-none">Forgot Password?</a>
      </div>
    </div>
  </form>

  <hr>

  <div class="text-center small">
    New to RoadFix? 
    <a href="../register/register.php" class="text-decoration-none fw-semibold text-primary">Create Account</a>
  </div>
  
  <div class="text-center small mt-3">
    Are you a mechanic? 
    <a href="../register/register_mechanic.php" class="text-decoration-none fw-semibold text-primary">Register as Mechanic</a>
  </div>
</div>

<?php include('../../../includes/layout/footer.php'); ?>
