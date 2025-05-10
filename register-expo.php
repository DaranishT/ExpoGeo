<?php
session_start();
require 'config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

if (!isset($_GET['id'])) {
    header('Location: expos.php');
    exit;
}

$expo_id = $_GET['id'];
$user_id = $_SESSION['user_id'];

// Check if the user is already registered for this expo
try {
    $stmt = $conn->prepare("SELECT * FROM registrations WHERE user_id = :user_id AND expo_id = :expo_id");
    $stmt->execute(['user_id' => $user_id, 'expo_id' => $expo_id]);
    $registration = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}

// Get expo details
try {
    $stmt = $conn->prepare("SELECT * FROM expos WHERE id = :expo_id");
    $stmt->execute(['expo_id' => $expo_id]);
    $expo = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Register the user for the expo
    try {
        $stmt = $conn->prepare("INSERT INTO registrations (user_id, expo_id, registered_at) VALUES (:user_id, :expo_id, NOW())");
        $stmt->execute(['user_id' => $user_id, 'expo_id' => $expo_id]);
        $success = true;
    } catch (PDOException $e) {
        $error = "Database error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Register for Expo - Expo GeoPage</title>
  <link rel="stylesheet" href="css/hypnotic.css">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <style>
    .registration-container {
      max-width: 600px;
      margin: 3rem auto;
      padding: 2rem;
      background: rgba(10, 10, 20, 0.8);
      border: 1px solid rgba(0, 240, 255, 0.3);
      border-radius: 15px;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    }
    
    .registration-header {
      text-align: center;
      margin-bottom: 2rem;
    }
    
    .expo-details {
      background: rgba(5, 5, 10, 0.7);
      border: 1px solid rgba(0, 240, 255, 0.2);
      border-radius: 10px;
      padding: 1.5rem;
      margin-bottom: 2rem;
    }
    
    .expo-details h3 {
      color: var(--primary);
      margin-bottom: 0.5rem;
    }
    
    .expo-meta {
      display: flex;
      flex-direction: column;
      gap: 0.5rem;
      color: var(--gray);
      font-size: 0.9rem;
      margin-bottom: 1.5rem;
    }
    
    .expo-meta i {
      width: 20px;
      color: var(--primary);
    }
    
    .registration-status {
      padding: 1.5rem;
      text-align: center;
      border-radius: 10px;
      margin-bottom: 2rem;
    }
    
    .registration-success {
      background: rgba(0, 255, 100, 0.1);
      border: 1px solid #00ff64;
      color: #00ff64;
    }
    
    .registration-error {
      background: rgba(255, 0, 100, 0.1);
      border: 1px solid #ff0064;
      color: #ff0064;
    }
    
    .registration-info {
      background: rgba(0, 240, 255, 0.1);
      border: 1px solid var(--primary);
      color: var(--primary);
    }
    
    .btn-register {
      width: 100%;
      padding: 0.75rem;
      background: var(--primary);
      color: var(--dark);
      border: none;
      border-radius: 5px;
      font-weight: 500;
      cursor: pointer;
      transition: all 0.3s ease;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 0.5rem;
    }
    
    .btn-register:hover {
      background: var(--primary-light);
      transform: translateY(-2px);
    }
    
    .btn-back {
      display: inline-block;
      margin-top: 1.5rem;
      padding: 0.5rem 1rem;
      background: rgba(255, 255, 255, 0.1);
      color: var(--gray);
      border-radius: 5px;
      text-decoration: none;
      transition: all 0.3s ease;
    }
    
    .btn-back:hover {
      background: var(--primary);
      color: var(--dark);
    }
  </style>
</head>
<body class="hypnotic-theme">
  <?php include 'navbar.php'; ?>
  <?php include 'chatbot.php'; ?>

  <main class="registration-container">
    <div class="registration-header">
      <h1 class="glitch-text" data-text="EXPO REGISTRATION">EXPO REGISTRATION</h1>
    </div>
    
    <?php if ($expo): ?>
      <div class="expo-details">
        <h3><?php echo htmlspecialchars($expo['name']); ?></h3>
        <div class="expo-meta">
          <span><i class="fas fa-calendar-alt"></i> <?php echo date('M j, Y', strtotime($expo['start_date'])); ?> - <?php echo date('M j, Y', strtotime($expo['end_date'])); ?></span>
          <span><i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($expo['location']); ?></span>
        </div>
        <p><?php echo htmlspecialchars($expo['description']); ?></p>
      </div>
    <?php endif; ?>
    
    <?php if (isset($success) && $success): ?>
      <div class="registration-status registration-success">
        <i class="fas fa-check-circle"></i>
        <p>Registration successful! Thank you for registering.</p>
        <a href="expos.php" class="btn-back">
          <i class="fas fa-arrow-left"></i> Back to Expos
        </a>
      </div>
    <?php elseif (isset($error)): ?>
      <div class="registration-status registration-error">
        <i class="fas fa-exclamation-circle"></i>
        <p><?php echo htmlspecialchars($error); ?></p>
        <a href="expos.php" class="btn-back">
          <i class="fas fa-arrow-left"></i> Back to Expos
        </a>
      </div>
    <?php elseif ($registration): ?>
      <div class="registration-status registration-info">
        <i class="fas fa-info-circle"></i>
        <p>You are already registered for this expo.</p>
        <a href="expos.php" class="btn-back">
          <i class="fas fa-arrow-left"></i> Back to Expos
        </a>
      </div>
    <?php else: ?>
      <form method="POST">
        <button type="submit" class="btn-register">
          <i class="fas fa-ticket-alt"></i> Confirm Registration
        </button>
      </form>
      <a href="expos.php" class="btn-back">
        <i class="fas fa-arrow-left"></i> Back to Expos
      </a>
    <?php endif; ?>
  </main>

  <?php include 'footer.php'; ?>
  <script src="js/dropdown.js"></script>
</body>
</html>