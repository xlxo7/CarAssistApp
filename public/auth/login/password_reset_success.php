<?php 
session_start(); 
include('../../../includes/layout/header.php'); 
?>

<div class="container my-5">
  <div class="card shadow-sm p-4 mx-auto text-center" style="max-width: 500px;">
    <h3 class="text-success fw-bold mb-3">Password Reset Successful</h3>
    <p class="text-muted">You can now log in with your new password.</p>

    <a href="login.php" class="btn btn-primary mt-3">Go to Login</a>
  </div>
</div>

<?php include('../../../includes/layout/footer.php'); ?>
