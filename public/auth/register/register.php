<?php
require_once('../../../controllers/customer/CustomerRegisterController.php');
include('../../../includes/layout/header.php');

// التحقق من البيانات المرسلة
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register'])) {
    $controller = new CustomerRegisterController();
    $controller->register($_POST);
}
?>

<div class="container my-5">
  <div class="card p-4 shadow-sm mx-auto" style="max-width: 600px;">
    <h2 class="text-center fw-bold mb-4">Create Account</h2>

    <?php if (isset($_SESSION['error'])): ?>
      <div class="alert alert-danger text-center">
        <?= $_SESSION['error']; unset($_SESSION['error']); ?>
      </div>
    <?php endif; ?>

    <form action="" method="POST" novalidate>
      <input type="hidden" name="user_type" value="customer">

      <div class="mb-3">
        <label for="name" class="form-label">Full Name</label>
        <input type="text" name="name" id="name" class="form-control" required placeholder="Your name">
      </div>

      <div class="mb-3">
        <label for="email" class="form-label">Email Address</label>
        <input type="email" name="email" id="email" class="form-control" required
               pattern="^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$"
               placeholder="you@example.com"
               title="Please enter a valid email address.">
      </div>

      <div class="mb-3">
        <label for="phone" class="form-label">Phone Number</label>
        <input type="tel" name="phone" id="phone" class="form-control" required
               pattern="01[0-9]{9}"
               inputmode="numeric"
               placeholder="01xxxxxxxxx"
               title="Phone number must start with 01 and be 11 digits long.">
      </div>

      <div class="mb-3">
        <label for="password" class="form-label">Password</label>
        <input type="password" name="password" id="password" class="form-control" required placeholder="••••••••">
      </div>

      <div class="mb-3">
        <label for="confirm_password" class="form-label">Confirm Password</label>
        <input type="password" name="confirm_password" id="confirm_password" class="form-control" required placeholder="••••••••">
      </div>

      <div class="d-grid">
        <button type="submit" name="register" class="btn btn-primary">Register</button>
      </div>
    </form>

    <hr class="my-4">

    <div class="text-center">
      Already have an account?
      <a href="/car_assist_app/public/auth/login/login.php" class="text-decoration-none fw-semibold text-primary">Login</a>
    </div>
  </div>
</div>

<script>
  document.getElementById('phone').addEventListener('input', function () {
    this.value = this.value.replace(/[^0-9]/g, '');
  });
</script>

<?php
include('../../../includes/layout/footer.php');
?>
