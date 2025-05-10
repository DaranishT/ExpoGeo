<?php
session_start();
require 'config.php';

// Check if the user is an admin or super admin
if (!isset($_SESSION['user_id']) || ($_SESSION['user_role'] !== 'admin' && $_SESSION['user_role'] !== 'super_admin')) {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $location = $_POST['location'];
    $created_by = $_SESSION['user_id']; // Store the creator's user ID

    try {
        $stmt = $conn->prepare("INSERT INTO expos (name, description, start_date, end_date, location, created_by) VALUES (:name, :description, :start_date, :end_date, :location, :created_by)");
        $stmt->execute([
            'name' => $name,
            'description' => $description,
            'start_date' => $start_date,
            'end_date' => $end_date,
            'location' => $location,
            'created_by' => $created_by
        ]);

        $_SESSION['message'] = "Expo added successfully!";
        header('Location: admin.php');
        exit;
    } catch (PDOException $e) {
        die("Database error: " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Add Expo - Expo GeoPage</title>
  <link rel="stylesheet" href="css/hypnotic.css">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="hypnotic-theme">
  <?php include 'navbar.php'; ?>
  <?php include 'chatbot.php'; ?>

  <main class="hypnotic-admin">
    <div class="admin-container">
      <div class="admin-header">
        <h1 class="glitch-text" data-text="ADD NEW EXPO">ADD NEW EXPO</h1>
        <a href="admin.php" class="cta-btn">
          <span>Back to Admin</span>
          <i class="fas fa-arrow-left"></i>
        </a>
      </div>

      <div class="admin-content">
        <form method="POST" class="expo-form">
          <div class="form-group">
            <label for="name">Expo Name</label>
            <input type="text" id="name" name="name" required>
          </div>

          <div class="form-group">
            <label for="description">Description</label>
            <textarea id="description" name="description" rows="5" required></textarea>
          </div>

          <div class="form-row">
            <div class="form-group">
              <label for="start_date">Start Date</label>
              <input type="date" id="start_date" name="start_date" required>
            </div>

            <div class="form-group">
              <label for="end_date">End Date</label>
              <input type="date" id="end_date" name="end_date" required>
            </div>
          </div>

          <div class="form-group">
            <label for="location">Location</label>
            <input type="text" id="location" name="location" required>
          </div>

          <div class="form-actions">
            <button type="submit" class="submit-btn">
              <i class="fas fa-plus"></i> Add Expo
            </button>
          </div>
        </form>
      </div>
    </div>
  </main>

  <?php include 'footer.php'; ?>
  <script src="js/dropdown.js"></script>
</body>
</html>