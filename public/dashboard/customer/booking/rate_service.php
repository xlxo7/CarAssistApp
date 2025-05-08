<?php
session_start();
include('../../../../includes/layout/header.php');
require_once('../../../../controllers/customer/RatingController.php');

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'customer') {
    header("Location: ../../../auth/login/login.php");
    exit;
}

$controller = new RatingController();

$customer_id = $_SESSION['user_id'];
$order_id = $_GET['order_id'] ?? null;

if (!$order_id) {
    echo "<div class='container my-5 alert alert-danger text-center'>Order ID is missing.</div>";
    include('../../../../includes/layout/footer.php');
    exit;
}

// التحقق من الطلب
$order = $controller->getOrderForRating((int)$order_id, (int)$customer_id);

if (!$order) {
    echo "<div class='container my-5 alert alert-danger text-center'>You are not authorized to rate this order.</div>";
    include('../../../../includes/layout/footer.php');
    exit;
}

$error = null;

// إرسال التقييم
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $rating = $_POST['rating'] ?? null;
    $comment = trim($_POST['comment'] ?? '');

    if (!$rating || $rating < 1 || $rating > 5) {
        $error = "Please provide a valid rating between 1 and 5.";
    } else {
        $result = $controller->submitRating([
            'customer_id' => $customer_id,
            'mechanic_id' => $order['mechanic_id'],
            'order_id' => $order_id,
            'rating' => (int)$rating,
            'comment' => $comment
        ]);

        if ($result === true) {
            $_SESSION['flash_message'] = "Thank you for your feedback!";
            header("Location: order_history.php");
            exit;
        } else {
            $error = $result;
        }
    }
}
?>

<div class="container my-5">
    <h2 class="text-center fw-bold mb-4">Rate Your Mechanic: <?= htmlspecialchars($order['mechanic_name']) ?></h2>

    <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="mb-3">
            <label for="rating" class="form-label">Rating (1 to 5)</label>
            <select name="rating" id="rating" class="form-select" required>
                <option value="">-- Select Rating --</option>
                <?php for ($i = 1; $i <= 5; $i++): ?>
                    <option value="<?= $i ?>"><?= $i ?> ★</option>
                <?php endfor; ?>
            </select>
        </div>

        <div class="mb-3">
            <label for="comment" class="form-label">Your Comment (optional)</label>
            <textarea name="comment" id="comment" class="form-control" rows="4" placeholder="Write your feedback..."></textarea>
        </div>

        <div class="d-grid">
            <button type="submit" class="btn btn-primary">Submit Rating</button>
        </div>
    </form>
</div>

<?php include('../../../../includes/layout/footer.php'); ?>
