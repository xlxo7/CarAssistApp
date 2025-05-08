<?php
session_start();
include('../../../../includes/layout/header.php');

// التأكد من تسجيل الدخول
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'customer') {
    header("Location: ../../../auth/login/login.php");
    exit;
}

require_once('../../../../config/Database.php');
$conn = Database::getInstance()->getConnection();

$user_id = $_SESSION['user_id'];

// جلب جميع الطلبات مع الميكانيكي والخدمة
$query = "
    SELECT o.*, u.name AS mechanic_name, s.name AS service_name
    FROM orders o
    JOIN users u ON o.mechanic_id = u.id
    JOIN services s ON o.service_id = s.id
    WHERE o.customer_id = ?
    ORDER BY o.created_at DESC
";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$orders = $stmt->get_result();
?>

<div class="container my-5">
    <h2 class="text-center fw-bold mb-4">Order History</h2>

    <?php if ($orders->num_rows === 0): ?>
        <div class="alert alert-info text-center">You have no previous orders.</div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-bordered align-middle text-center">
                <thead class="table-light">
                    <tr>
                        <th>Mechanic</th>
                        <th>Service</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Rate</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $orders->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['mechanic_name']) ?></td>
                            <td><?= htmlspecialchars($row['service_name']) ?></td>
                            <td>
                                <span class="badge 
                                    <?= match($row['status']) {
                                        'completed'   => 'bg-success',
                                        'on_the_way'  => 'bg-warning text-dark',
                                        'accepted'    => 'bg-info text-dark',
                                        'pending'     => 'bg-secondary',
                                        'cancelled'   => 'bg-dark text-white',
                                        default       => 'bg-light'
                                    } ?>">
                                    <?= ucfirst($row['status']) ?>
                                </span>
                            </td>
                            <td><?= date('Y-m-d', strtotime($row['created_at'])) ?></td>
                            <td>
                                <?php
                                $rate_stmt = $conn->prepare("SELECT id FROM ratings WHERE order_id = ?");
                                $rate_stmt->bind_param("i", $row['id']);
                                $rate_stmt->execute();
                                $rate_stmt->store_result();

                                if ($row['status'] === 'completed') {
                                    if ($rate_stmt->num_rows > 0) {
                                        echo '<span class="text-muted">Already Rated</span>';
                                    } else {
                                        echo '<a href="rate_service.php?order_id=' . $row['id'] . '" class="btn btn-sm btn-outline-primary">Rate Service</a>';
                                    }
                                } else {
                                    echo '<span class="text-muted">N/A</span>';
                                }
                                ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<?php include('../../../../includes/layout/footer.php'); ?>
