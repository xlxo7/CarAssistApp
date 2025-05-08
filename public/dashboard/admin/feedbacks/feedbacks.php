<?php
require_once('../../../../controllers/admin/FeedbackController.php');
include('../../../../includes/layout/header.php');

$controller = new FeedbackController();
$feedbacks = $controller->getAllFeedbacks();
?>

<div class="container my-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Feedback and Complaints</h2>
        <button onclick="window.print()" class="btn btn-primary">Print Report</button>
    </div>

    <table class="table table-bordered text-center">
        <thead class="table-dark">
            <tr>
                <th>#</th>
                <th>User Name</th>
                <th>Message</th>
                <th>Submitted At</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($feedbacks as $index => $row): ?>
                <tr>
                    <td><?= $index + 1 ?></td>
                    <td><?= htmlspecialchars($row['user_name']) ?></td>
                    <td><?= htmlspecialchars($row['message']) ?></td>
                    <td><?= htmlspecialchars($row['created_at']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include('../../../../includes/layout/footer.php'); ?>
