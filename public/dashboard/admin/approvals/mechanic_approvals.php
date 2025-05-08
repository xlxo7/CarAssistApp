<?php
session_start();
require_once('../../../../config/Database.php');
include('../../../../includes/layout/header.php');

$conn = Database::getInstance()->getConnection();

// التأكد من أن المستخدم أدمن
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin') {
    header("Location: /car_assist_app/public/auth/login.php");
    exit;
}

// جلب جميع الميكانيكيين الذين في حالة "pending"
$mechanics = $conn->query("SELECT * FROM users WHERE user_type = 'mechanic' AND status = 'pending'");
?>

<div class="container my-5">
    <h2 class="fw-bold text-dark text-center mb-4">Mechanic Approvals</h2>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success text-center">
            <?= $_SESSION['success']; unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger text-center">
            <?= $_SESSION['error']; unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>

    <table class="table table-bordered table-hover text-center align-middle">
        <thead class="table-dark">
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>CV</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $mechanics->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= htmlspecialchars($row['name']) ?></td>
                    <td><?= htmlspecialchars($row['email']) ?></td>
                    <td><?= htmlspecialchars($row['phone']) ?></td>
                    <td>
                        <?php if (!empty($row['cv_path'])): ?>
                            <a href="/car_assist_app/uploads/cvc/<?= htmlspecialchars($row['cv_path']) ?>" target="_blank" class="btn btn-sm btn-outline-info">View CV</a>
                        <?php else: ?>
                            <span class="text-muted">No CV</span>
                        <?php endif; ?>
                    </td>
                    <td>
                    <a href="/car_assist_app/public/dashboard/admin/approvals/approve_mechanic.php?id=<?= $row['id'] ?>" class="btn btn-success btn-sm">Approve</a>
                    <a href="/car_assist_app/public/dashboard/admin/approvals/reject_mechanic.php?id=<?= $row['id'] ?>" class="btn btn-danger btn-sm">Reject</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php include('../../../../includes/layout/footer.php'); ?>
