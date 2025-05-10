<?php
session_start();
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get raw JSON input
    $input = file_get_contents('php://input');
    $data = json_decode($input, true);

    if ($data === null) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid JSON input.']);
        exit;
    }

    $email = $data['email'];
    $newPassword = password_hash($data['new_password'], PASSWORD_BCRYPT);

    try {
        // Update the user's password and clear OTP fields
        $stmt = $conn->prepare("UPDATE users SET password = :password, otp = NULL, otp_expiry = NULL WHERE email = :email");
        $stmt->execute(['password' => $newPassword, 'email' => $email]);

        if ($stmt->rowCount() > 0) {
            echo json_encode(['status' => 'success', 'message' => 'Password reset successfully.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to reset password.']);
        }
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
}
?>