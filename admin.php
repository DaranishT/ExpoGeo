<?php
session_start();
require 'config.php';

if (!isset($_SESSION['user_id']) || ($_SESSION['user_role'] !== 'admin' && $_SESSION['user_role'] !== 'super_admin')) {
    header('Location: login.php');
    exit;
}

// Pagination settings
$records_per_page = 10;
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($current_page - 1) * $records_per_page;

try {
    $stmt = $conn->query("SELECT COUNT(*) AS total FROM expos");
    $total_records = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    $total_pages = ceil($total_records / $records_per_page);

    $stmt = $conn->prepare("SELECT * FROM expos LIMIT :limit OFFSET :offset");
    $stmt->bindValue(':limit', $records_per_page, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
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
  <title>Admin Panel - Expo GeoPage</title>
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
        <h1 class="glitch-text" data-text="ADMIN PANEL">ADMIN PANEL</h1>
        <a href="add-expo.php" class="cta-btn">
          <span>Add New Expo</span>
          <i class="fas fa-plus"></i>
        </a>
      </div>

      <div class="admin-content">
        <h2 class="section-title">Manage Expos</h2>
        
        <?php if (count($expos) > 0) : ?>
          <div class="expos-grid">
            <?php foreach ($expos as $expo) : ?>
              <div class="expo-card">
                <div class="expo-card-header">
                  <h3><?php echo htmlspecialchars($expo['name']); ?></h3>
                  <div class="expo-meta">
                    <span><i class="fas fa-calendar-alt"></i> <?php echo date('M j, Y', strtotime($expo['start_date'])); ?> - <?php echo date('M j, Y', strtotime($expo['end_date'])); ?></span>
                    <span><i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($expo['location']); ?></span>
                  </div>
                </div>
                <div class="expo-actions">
                  <a href="edit-expo.php?id=<?php echo $expo['id']; ?>" class="action-btn edit">
                    <i class="fas fa-edit"></i> Edit
                  </a>
                  <a href="delete-expo.php?id=<?php echo $expo['id']; ?>" class="action-btn delete">
                    <i class="fas fa-trash-alt"></i> Delete
                  </a>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        <?php else : ?>
          <div class="no-expos">
            <i class="fas fa-exclamation-circle"></i>
            <p>No expos found</p>
          </div>
        <?php endif; ?>

        <!-- Pagination -->
        <?php if ($total_pages > 1) : ?>
          <div class="pagination">
            <?php if ($current_page > 1) : ?>
              <a href="admin.php?page=<?php echo $current_page - 1; ?>" class="page-btn">
                <i class="fas fa-chevron-left"></i> Previous
              </a>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $total_pages; $i++) : ?>
              <a href="admin.php?page=<?php echo $i; ?>" class="page-btn <?php echo $i === $current_page ? 'active' : ''; ?>">
                <?php echo $i; ?>
              </a>
            <?php endfor; ?>

            <?php if ($current_page < $total_pages) : ?>
              <a href="admin.php?page=<?php echo $current_page + 1; ?>" class="page-btn">
                Next <i class="fas fa-chevron-right"></i>
              </a>
            <?php endif; ?>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </main>

  <?php include 'footer.php'; ?>
  <script src="js/dropdown.js"></script>
</body>
</html>