<?php
session_start();
if (!isset($_SESSION['user_email'])) {
  header('Location: login.php');
  exit;
}

require 'config.php';

try {
  $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email");
  $stmt->execute(['email' => $_SESSION['user_email']]);
  $user = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
  die("Database error: " . $e->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['request_admin'])) {
    try {
        $stmt = $conn->prepare("UPDATE users SET request_admin = 1 WHERE id = :id");
        $stmt->execute(['id' => $user['id']]);
        $_SESSION['message'] = "Admin access requested successfully! A super admin will review your request.";
    } catch (PDOException $e) {
        $_SESSION['message'] = "Error: " . $e->getMessage();
    }
    header('Location: profile.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Account Settings - Expo GeoPage</title>
  <link rel="stylesheet" href="css/hypnotic.css">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <style>
    .hypnotic-profile {
      padding: 2rem;
      max-width: 1200px;
      margin: 0 auto;
    }
    
    .profile-container {
      background: rgba(10, 10, 20, 0.8);
      border: 1px solid rgba(0, 240, 255, 0.3);
      border-radius: 15px;
      overflow: hidden;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    }
    
    .profile-card {
      padding: 2rem;
    }
    
    .profile-header {
      text-align: center;
      margin-bottom: 2rem;
    }
    
    .profile-avatar {
      font-size: 5rem;
      color: var(--primary);
      margin-bottom: 1rem;
    }
    
    .profile-role {
      display: inline-block;
      padding: 0.25rem 0.75rem;
      border-radius: 20px;
      font-size: 0.8rem;
      font-weight: 500;
      text-transform: uppercase;
    }
    
    .profile-role.admin {
      background: rgba(0, 240, 255, 0.2);
      color: var(--primary);
    }
    
    .profile-role.super_admin {
      background: rgba(255, 215, 0, 0.2);
      color: gold;
    }
    
    .profile-role.user {
      background: rgba(100, 100, 100, 0.2);
      color: var(--gray);
    }
    
    .detail-item {
      display: flex;
      align-items: center;
      gap: 1rem;
      margin-bottom: 1rem;
      color: var(--gray);
    }
    
    .profile-actions {
      margin-top: 2rem;
      display: flex;
      flex-direction: column;
      gap: 1rem;
    }
    
    .profile-btn {
      padding: 0.75rem;
      background: rgba(0, 240, 255, 0.1);
      color: var(--primary);
      border: 1px solid var(--primary);
      border-radius: 5px;
      text-decoration: none;
      text-align: center;
      transition: all 0.3s ease;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 0.5rem;
    }
    
    .profile-btn:hover {
      background: var(--primary);
      color: var(--dark);
    }
    
    .admin-request-form {
      margin: 0;
    }
    
    .request-pending {
      padding: 0.75rem;
      background: rgba(255, 215, 0, 0.1);
      color: gold;
      border: 1px solid gold;
      border-radius: 5px;
      text-align: center;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 0.5rem;
    }
    
    .profile-alert {
      padding: 1rem;
      background: rgba(0, 240, 255, 0.1);
      border: 1px solid var(--primary);
      border-radius: 5px;
      color: var(--primary);
      margin-bottom: 2rem;
    }
  </style>
</head>
<body class="hypnotic-theme">
  <?php include 'navbar.php'; ?>
  <?php include 'chatbot.php'; ?>

  <main class="hypnotic-profile">
    <div class="profile-container">
      <h1 class="glitch-text" data-text="ACCOUNT SETTINGS">ACCOUNT SETTINGS</h1>
      
      <?php if (isset($_SESSION['message'])) : ?>
        <div class="profile-alert">
          <?php echo htmlspecialchars($_SESSION['message']); ?>
          <?php unset($_SESSION['message']); ?>
        </div>
      <?php endif; ?>
      
      <div class="profile-card">
        <div class="profile-header">
          <div class="profile-avatar">
            <i class="fas fa-user-circle"></i>
          </div>
          <h2><?php echo htmlspecialchars($user['name']); ?></h2>
          <span class="profile-role <?php echo htmlspecialchars($user['role']); ?>">
            <?php echo htmlspecialchars($user['role']); ?>
          </span>
        </div>
        
        <div class="profile-details">
          <div class="detail-item">
            <i class="fas fa-envelope"></i>
            <span><?php echo htmlspecialchars($user['email']); ?></span>
          </div>
          
          <div class="profile-actions">
            <a href="change-password.php" class="profile-btn">
              <i class="fas fa-key"></i> Change Password
            </a>
            <a href="update-email.php" class="profile-btn">
              <i class="fas fa-envelope"></i> Update Email
            </a>
            
            <?php if ($user['role'] === 'user' && $user['request_admin'] == 0) : ?>
              <form method="POST" class="admin-request-form">
                <button type="submit" name="request_admin" class="profile-btn admin-request">
                  <i class="fas fa-user-shield"></i> Request Admin Access
                </button>
              </form>
            <?php elseif ($user['request_admin'] == 1) : ?>
              <div class="request-pending">
                <i class="fas fa-clock"></i> Admin request pending approval
              </div>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>
  </main>

  <?php include 'footer.php'; ?>

  <script src="js/dropdown.js"></script>
</body>
</html>