<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require 'config.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
require 'PHPMailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get raw JSON input
    $input = file_get_contents('php://input');
    $data = json_decode($input, true);

    if ($data === null) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid JSON input.']);
        exit;
    }

    $email = $data['email'];

    try {
        // Check if the email exists in the database
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            // Generate a 6-digit OTP
            $otp = rand(100000, 999999);

            // Set OTP expiry to 10 minutes from now
            $otpExpiry = date('Y-m-d H:i:s', strtotime('+10 minutes'));

            // Save OTP and expiry in the database
            $stmt = $conn->prepare("UPDATE users SET otp = :otp, otp_expiry = :otp_expiry WHERE email = :email");
            $stmt->execute([
                'otp' => $otp,
                'otp_expiry' => $otpExpiry,
                'email' => $email
            ]);

            // Send OTP via email using PHPMailer
            $mail = new PHPMailer(true);

            try {
                // Server settings
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com'; // SMTP server
                $mail->SMTPAuth = true;
                $mail->Username = 'daranish.t50@gmail.com'; // Your Gmail address
                $mail->Password = 'jsgw lsxx lavm rrwo'; // Your Gmail password or App Password
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Enable TLS encryption
                $mail->Port = 587; // TCP port to connect to

                // Recipients
                $mail->setFrom('your-email@gmail.com', 'Expo GeoPage');
                $mail->addAddress($email); // Add a recipient

                // Content
                $mail->isHTML(true);
                $mail->Subject = 'Your OTP for Password Reset';
                $mail->Body = "Your OTP is: <b>$otp</b>. It will expire in 10 minutes.";

                $mail->send();
                echo json_encode(['status' => 'success', 'message' => 'OTP sent to your email.']);
            } catch (Exception $e) {
                // Log the error for debugging
                error_log('PHPMailer Error: ' . $e->getMessage());
                echo json_encode(['status' => 'error', 'message' => 'Failed to send OTP. Error: ' . $e->getMessage()]);
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