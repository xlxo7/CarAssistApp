<?php
session_start();
$token = $_GET['token'] ?? null;

if (!$token) {
    $_SESSION['error'] = "Invalid or missing token.";
    header("Location: login.php");
    exit;
}
?>

<?php include('../../../includes/layout/header.php'); ?>

<div class="container my-5">
    <div class="card mx-auto p-4 shadow-sm" style="max-width: 500px;">
        <h3 class="text-center mb-4">Reset Your Password</h3>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger text-center">
                <?= $_SESSION['error']; unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <form action="reset_password_submit.php" method="POST">
            <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">

            <div class="mb-3">
                <label for="password" class="form-label">New Password</label>
                <input type="password" name="password" id="password" class="form-control" required placeholder="••••••••">
            </div>

            <div class="mb-3">
                <label for="confirm_password" class="form-label">Confirm New Password</label>
                <input type="password" name="confirm_password" id="confirm_password" class="form-control" required placeholder="••••••••">
            </div>

            <div class="d-grid">
                <button type="submit" name="reset" class="btn btn-primary">Reset Password</button>
            </div>
        </form>
    </div>
</div>

<?php include('../../../includes/layout/footer.php'); ?>
