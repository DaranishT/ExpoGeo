<?php
session_start();
require 'config.php';

// Check if the user is an admin or super admin
if (!isset($_SESSION['user_id']) || ($_SESSION['user_role'] !== 'admin' && $_SESSION['user_role'] !== 'super_admin')) {
    header('Location: login.php');
    exit;
}

if (!isset($_GET['id'])) {
    header('Location: admin.php');
    exit;
}

$expo_id = $_GET['id'];

// Fetch expo details
try {
    $stmt = $conn->prepare("SELECT * FROM expos WHERE id = :id");
    $stmt->execute(['id' => $expo_id]);
    $expo = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$expo) {
        header('Location: admin.php');
        exit;
    }

    // Check if the user is the creator or a super admin
    if ($_SESSION['user_role'] !== 'super_admin' && $expo['created_by'] !== $_SESSION['user_id']) {
        $_SESSION['message'] = "You do not have permission to delete this expo.";
        header('Location: admin.php');
        exit;
    }

    // Delete the expo
    $stmt = $conn->prepare("DELETE FROM expos WHERE id = :id");
    $stmt->execute(['id' => $expo_id]);

    $_SESSION['message'] = "Expo deleted successfully!";
    header('Location: admin.php');
    exit;
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
?>