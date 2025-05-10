<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

require 'config.php';

try {
    $stmt = $conn->prepare("SELECT * FROM users WHERE id = :id");
    $stmt->execute(['id' => $_SESSION['user_id']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>My Account - Expo GeoPage</title>
  <link rel="stylesheet" href="css/hypnotic.css">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="hypnotic-theme">
  <?php include 'navbar.php'; ?>
  <?php include 'chatbot.php'; ?>

  <main class="account-container">
    <div class="account-card">
      <div class="account-header">
        <div class="account-avatar">
          <i class="fas fa-user-circle"></i>
        </div>
        <h1><?php echo htmlspecialchars($user['name']); ?></h1>
        <p class="account-email"><?php echo htmlspecialchars($user['email']); ?></p>
      </div>
      
      <div class="account-options">
        <a href="profile.php" class="account-option">
          <i class="fas fa-user-cog"></i>
          <span>Profile Settings</span>
          <i class="fas fa-chevron-right"></i>
        </a>
        
        <a href="change-password.php" class="account-option">
          <i class="fas fa-key"></i>
          <span>Change Password</span>
          <i class="fas fa-chevron-right"></i>
        </a>
        
        <?php if ($user['role'] === 'user' && $user['request_admin'] == 0): ?>
          <a href="request-admin.php" class="account-option">
            <i class="fas fa-user-shield"></i>
            <span>Request Admin Access</span>
            <i class="fas fa-chevron-right"></i>
          </a>
        <?php elseif ($user['request_admin'] == 1): ?>
          <div class="account-notice">
            <i class="fas fa-clock"></i>
            <span>Admin request pending approval</span>
          </div>
        <?php endif; ?>
        
        <a href="logout.php" class="account-option logout">
          <i class="fas fa-sign-out-alt"></i>
          <span>Logout</span>
          <i class="fas fa-chevron-right"></i>
        </a>
      </div>
    </div>
  </main>

  <?php include 'footer.php'; ?>
</body>
</html>