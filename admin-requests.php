<?php
session_start();
require 'config.php';

// Check if the user is a super admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'super_admin') {
    header('Location: login.php');
    exit;
}

// Fetch users who requested admin access
try {
    $stmt = $conn->query("SELECT * FROM users WHERE request_admin = 1");
    $requests = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_POST['user_id'];
    $action = $_POST['action']; // 'approve' or 'reject'

    if ($action === 'approve') {
        // Update user role to admin
        try {
            $stmt = $conn->prepare("UPDATE users SET role = 'admin', request_admin = 0 WHERE id = :id");
            $stmt->execute(['id' => $user_id]);
            $_SESSION['message'] = "User approved as admin.";
        } catch (PDOException $e) {
            die("Database error: " . $e->getMessage());
        }
    } elseif ($action === 'reject') {
        // Reject the request
        try {
            $stmt = $conn->prepare("UPDATE users SET request_admin = 0 WHERE id = :id");
            $stmt->execute(['id' => $user_id]);
            $_SESSION['message'] = "Admin request rejected.";
        } catch (PDOException $e) {
            die("Database error: " . $e->getMessage());
        }
    }

    // Refresh the page to reflect changes
    header('Location: admin-requests.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Requests - Expo GeoPage</title>
  <link rel="stylesheet" href="css/hypnotic.css">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <style>
    .hypnotic-admin {
      padding: 2rem;
      max-width: 1200px;
      margin: 0 auto;
    }
    
    .requests-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
      gap: 1.5rem;
      margin: 2rem 0;
    }
    
    .request-card {
      background: rgba(5, 5, 10, 0.7);
      border: 1px solid rgba(0, 240, 255, 0.2);
      border-radius: 10px;
      padding: 1.5rem;
      transition: all 0.3s ease;
    }
    
    .request-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 10px 20px rgba(0, 240, 255, 0.2);
    }
    
    .request-card h3 {
      color: var(--primary);
      margin-bottom: 0.5rem;
    }
    
    .request-card p {
      color: var(--gray);
      margin-bottom: 1.5rem;
    }
    
    .request-actions {
      display: flex;
      gap: 1rem;
    }
    
    .request-btn {
      flex: 1;
      padding: 0.5rem;
      border-radius: 5px;
      text-align: center;
      text-decoration: none;
      font-size: 0.9rem;
      transition: all 0.3s ease;
      border: none;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 0.5rem;
    }
    
    .request-btn.approve {
      background: rgba(0, 255, 100, 0.1);
      color: #00ff64;
      border: 1px solid #00ff64;
    }
    
    .request-btn.approve:hover {
      background: #00ff64;
      color: var(--dark);
    }
    
    .request-btn.reject {
      background: rgba(255, 0, 100, 0.1);
      color: #ff0064;
      border: 1px solid #ff0064;
    }
    
    .request-btn.reject:hover {
      background: #ff0064;
      color: white;
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

  <main class="hypnotic-admin">
    <div class="admin-container">
      <div class="admin-header">
        <h1 class="glitch-text" data-text="ADMIN REQUESTS">ADMIN REQUESTS</h1>
        <a href="admin.php" class="cta-btn">
          <span>Back to Admin</span>
          <i class="fas fa-arrow-left"></i>
        </a>
      </div>

      <div class="admin-content">
        <?php if (isset($_SESSION['message'])) : ?>
          <div class="profile-alert">
            <?php echo htmlspecialchars($_SESSION['message']); ?>
            <?php unset($_SESSION['message']); ?>
          </div>
        <?php endif; ?>
        
        <?php if (count($requests) > 0) : ?>
          <div class="requests-grid">
            <?php foreach ($requests as $request) : ?>
              <div class="request-card">
                <h3><?php echo htmlspecialchars($request['name']); ?></h3>
                <p>Email: <?php echo htmlspecialchars($request['email']); ?></p>
                <form method="POST" class="request-actions">
                  <input type="hidden" name="user_id" value="<?php echo $request['id']; ?>">
                  <button type="submit" name="action" value="approve" class="request-btn approve">
                    <i class="fas fa-check"></i> Approve
                  </button>
                  <button type="submit" name="action" value="reject" class="request-btn reject">
                    <i class="fas fa-times"></i> Reject
                  </button>
                </form>
              </div>
            <?php endforeach; ?>
          </div>
        <?php else : ?>
          <div class="no-requests">
            <i class="fas fa-check-circle"></i>
            <p>No pending admin requests</p>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </main>

  <?php include 'footer.php'; ?>
  <script src="js/dropdown.js"></script>
</body>
</html>