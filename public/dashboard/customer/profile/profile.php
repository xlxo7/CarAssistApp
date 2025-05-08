<?php
session_start();
include('../../../../includes/layout/header.php');
require_once('../../../../controllers/customer/ProfileController.php');

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'customer') {
    header("Location: ../../../auth/login/login.php");
    exit;
}

$controller = new ProfileController();
$user_id = $_SESSION['user_id'];
$success_message = '';
$error_message = '';

// تعديل البيانات
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save'])) {
    $name = trim($_POST['name']);
    $phone = trim($_POST['phone']);

    if ($name === '' || $phone === '') {
        $error_message = "All fields are required.";
    } else {
        if ($controller->updateProfile($user_id, $name, $phone)) {
            $_SESSION['user_name'] = $name;
            $success_message = "Profile updated successfully.";
        } else {
            $error_message = "Failed to update profile.";
        }
    }
}

// جلب بيانات العميل
$user = $controller->getUserById($user_id);
$is_edit_mode = isset($_GET['edit']) && $_GET['edit'] === 'true';
?>

<div class="container my-5" style="max-width: 600px;">
    <h2 class="fw-bold mb-4 text-center">My Profile</h2>

    <?php if ($success_message): ?>
        <div class="alert alert-success text-center"><?= $success_message ?></div>
    <?php elseif ($error_message): ?>
        <div class="alert alert-danger text-center"><?= $error_message ?></div>
    <?php endif; ?>

    <div class="card shadow-sm p-4">
        <?php if ($is_edit_mode): ?>
            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">Full Name</label>
                    <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($user['name']) ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Phone Number</label>
                    <input type="text" name="phone" class="form-control" value="<?= htmlspecialchars($user['phone']) ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Email (read-only)</label>
                    <input type="email" class="form-control" value="<?= htmlspecialchars($user['email']) ?>" readonly>
                </div>

                <div class="d-flex justify-content-between">
                    <button type="submit" name="save" class="btn btn-primary">Save Changes</button>
                    <a href="profile.php" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        <?php else: ?>
            <p><strong>Full Name:</strong> <?= htmlspecialchars($user['name']) ?></p>
            <p><strong>Phone:</strong> <?= htmlspecialchars($user['phone']) ?></p>
            <p><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></p>
            <p><strong>Status:</strong>
                <?= $user['availability'] === 'available'
                    ? '<span class="text-success">Available</span>'
                    : '<span class="text-danger">Busy</span>' ?>
            </p>

            <div class="d-grid mt-3">
                <a href="profile.php?edit=true" class="btn btn-outline-primary">Edit Profile</a>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include('../../../../includes/layout/footer.php'); ?>
