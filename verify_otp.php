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
    $otp = $data['otp'];

    try {
        // Fetch the OTP and expiry time from the database
        $stmt = $conn->prepare("SELECT otp, otp_expiry FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            $storedOtp = $user['otp'];
            $otpExpiry = $user['otp_expiry'];

            // Debugging: Log the OTP and expiry time
            error_log("Stored OTP: $storedOtp, Entered OTP: $otp");
            error_log("OTP Expiry: $otpExpiry, Current Time: " . date('Y-m-d H:i:s'));

            // Check if the OTP matches and is not expired
            if ($storedOtp === $otp && strtotime($otpExpiry) > time()) {
                echo json_encode(['status' => 'success', 'message' => 'OTP verified.']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Invalid or expired OTP.']);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Email not found.']);
        }
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
}
?>

