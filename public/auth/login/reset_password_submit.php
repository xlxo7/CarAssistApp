<?php

require_once('../../../config/Database.php');
session_start();

$conn = Database::getInstance()->getConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reset'])) {
    $token = $_POST['token'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    if (!$token || !$password || !$confirm_password) {
        $_SESSION['error'] = "All fields are required.";
        header("Location: reset_password.php?token=" . urlencode($token));
        exit;
    }

    if ($password !== $confirm_password) {
        $_SESSION['error'] = "Passwords do not match.";
        header("Location: reset_password.php?token=" . urlencode($token));
        exit;
    }

    // التحقق من صلاحية التوكن
    $stmt = $conn->prepare("SELECT id, reset_token_expires_at FROM users WHERE reset_token = ? LIMIT 1");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows === 1) {
        $user = $result->fetch_assoc();

        if (strtotime($user['reset_token_expires_at']) < time()) {
            $_SESSION['error'] = "Reset link has expired.";
            header("Location: forgot_password.php");
            exit;
        }        

        // تحديث كلمة السر
        $hashed = password_hash($password, PASSWORD_BCRYPT);
        $update = $conn->prepare("UPDATE users SET password = ?, reset_token = NULL, reset_token_expires_at = NULL WHERE id = ?");
        $update->bind_param("si", $hashed, $user['id']);

        if ($update->execute()) {
            $_SESSION['success'] = "Password updated successfully. You can now log in.";
            header("Location: password_reset_success.php");
            exit;
        } else {
            $_SESSION['error'] = "Something went wrong. Please try again.";
            header("Location: reset_password.php?token=" . urlencode($token));
            exit;
        }

    } else {
        $_SESSION['error'] = "Invalid or expired reset link.";
        header("Location: forgot_password.php");
        exit;
    }

} else {
    $_SESSION['error'] = "Invalid request.";
    header("Location: forgot_password.php");
    exit;
}
