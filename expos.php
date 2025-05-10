<?php
session_start();
require 'config.php';

try {
    $stmt = $conn->query("SELECT * FROM expos");
    $expos = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Expo Listings - Expo GeoPage</title>
  <link rel="stylesheet" href="css/hypnotic.css">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="hypnotic-theme">
  <?php include 'navbar.php'; ?>
  <?php include 'chatbot.php'; ?>

  <section class="hypnotic-expos">
    <h2 class="section-title glitch-text" data-text="UPCOMING EXPOS">UPCOMING EXPOS</h2>
    <div class="expos-grid">
      <?php foreach ($expos as $expo) : ?>
        <div class="expo-card">
          <div class="expo-card-content">
            <h3 class="expo-title"><?php echo htmlspecialchars($expo['name']); ?></h3>
            <div class="expo-meta">
              <p class="expo-date">
                <i class="fas fa-calendar-alt"></i>
                <?php echo date('M j, Y', strtotime($expo['start_date'])); ?> - <?php echo date('M j, Y', strtotime($expo['end_date'])); ?>
              </p>
              <p class="expo-location">
                <i class="fas fa-map-marker-alt"></i>
                <?php echo htmlspecialchars($expo['location']); ?>
              </p>
            </div>
            <p class="expo-description"><?php echo htmlspecialchars($expo['description']); ?></p>
            <a href="expo-details.php?id=<?php echo $expo['id']; ?>" class="cta-btn">
              <span>View Details</span>
              <i class="fas fa-arrow-right"></i>
            </a>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </section>

  <?php include 'footer.php'; ?>

  <script src="js/hypnotic.js"></script>
</body>
</html>