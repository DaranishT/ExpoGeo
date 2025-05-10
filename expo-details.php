<?php
session_start();
require 'config.php';

if (!isset($_GET['id'])) {
    header('Location: expos.php');
    exit;
}

$expo_id = $_GET['id'];

try {
    $stmt = $conn->prepare("SELECT * FROM expos WHERE id = :id");
    $stmt->execute(['id' => $expo_id]);
    $expo = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}

if (!$expo) {
    header('Location: expos.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo htmlspecialchars($expo['name']); ?> - Expo GeoPage</title>
  <link rel="stylesheet" href="css/hypnotic.css">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="hypnotic-theme">
  <?php include 'navbar.php'; ?>
  <?php include 'chatbot.php'; ?>

  <main class="hypnotic-expo-details">
    <div class="expo-header">
      <h1 class="expo-title glitch-text" data-text="<?php echo htmlspecialchars($expo['name']); ?>">
        <?php echo htmlspecialchars($expo['name']); ?>
      </h1>
      <div class="expo-meta">
        <div class="meta-item">
          <i class="fas fa-calendar-alt"></i>
          <span><?php echo date('M j, Y', strtotime($expo['start_date'])); ?> - <?php echo date('M j, Y', strtotime($expo['end_date'])); ?></span>
        </div>
        <div class="meta-item">
          <i class="fas fa-map-marker-alt"></i>
          <span><?php echo htmlspecialchars($expo['location']); ?></span>
        </div>
      </div>
    </div>

    <div class="expo-content">
      <div class="expo-description">
        <h2 class="section-title">About This Exhibition</h2>
        <p><?php echo htmlspecialchars($expo['description']); ?></p>
      </div>

      <div class="expo-actions">
        <?php if (isset($_SESSION['user_id'])): ?>
          <a href="register-expo.php?id=<?php echo $expo['id']; ?>" class="cta-btn">
            <span>Register Now</span>
            <i class="fas fa-arrow-right"></i>
          </a>
        <?php else: ?>
          <div class="login-prompt">
            <p>Please <a href="login.php" class="text-link">log in</a> to register for this expo</p>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </main>

  <?php include 'footer.php'; ?>

  <script src="js/hypnotic.js"></script>
</body>
</html>