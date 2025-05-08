<?php
require_once('../../../../controllers/admin/MechanicController.php');
include('../../../../includes/layout/header.php');

$controller = new MechanicController();
$mechanics = $controller->getApprovedMechanics();
?>

<div class="container my-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Approved Mechanics</h2>
        <button onclick="window.print()" class="btn btn-primary">Print Report</button>
    </div>

    <table class="table table-bordered text-center">
        <thead class="table-dark">
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($mechanics as $index => $row): ?>
                <tr>
                    <td><?= $index + 1 ?></td>
                    <td><?= htmlspecialchars($row['name']) ?></td>
                    <td><?= htmlspecialchars($row['email']) ?></td>
                    <td><?= htmlspecialchars($row['phone']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include('../../../../includes/layout/footer.php'); ?>
