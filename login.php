<?php
session_start();
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = file_get_contents('php://input');
    $data = json_decode($input, true);

    if ($data === null) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid JSON input.']);
        exit;
    }

    $email = $data['email'];
    $password = $data['password'];

    try {
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_role'] = $user['role'];

            echo json_encode(['status' => 'success', 'message' => 'Login successful. Redirecting...']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Invalid email or password.']);
        }
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
    }
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - Expo GeoPage</title>
  <link rel="stylesheet" href="css/hypnotic.css">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="hypnotic-theme">
  <?php include 'navbar.php'; ?>
  <?php include 'chatbot.php'; ?>

  <section class="hypnotic-auth">
    <div class="auth-container">
      <h1 class="glitch-text" data-text="LOGIN">LOGIN</h1>
      <form id="login-form" onsubmit="event.preventDefault(); login();">
        <div class="input-group">
          <label for="email">Email</label>
          <input type="email" id="email" name="email" required>
          <i class="fas fa-envelope input-icon"></i>
        </div>
        
        <div class="input-group">
          <label for="password">Password</label>
          <input type="password" id="password" name="password" required>
          <i class="fas fa-lock input-icon"></i>
        </div>
        
        <button type="submit" class="cta-btn">
          <span>Login</span>
          <i class="fas fa-sign-in-alt"></i>
        </button>
        
        <div class="auth-links">
          <a href="signup.php" class="text-link">
            <i class="fas fa-user-plus"></i> Create Account
          </a>
          <a href="forgot-password.php" class="text-link">
            <i class="fas fa-key"></i> Forgot Password?
          </a>
        </div>
      </form>
    </div>
  </section>

  <?php include 'footer.php'; ?>

  <script>
    function login() {
      const email = document.getElementById('email').value;
      const password = document.getElementById('password').value;
      const btn = document.querySelector('.cta-btn');

      // Show loading state
      btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Logging in...';
      btn.disabled = true;

      fetch('login.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ email, password }),
      })
      .then(response => response.json())
      .then(data => {
        if (data.status === 'success') {
          window.location.href = 'index.php';
        } else {
          alert(data.message);
          btn.innerHTML = '<span>Login</span><i class="fas fa-sign-in-alt"></i>';
          btn.disabled = false;
        }
      })
      .catch(error => {
        console.error('Error:', error);
        btn.innerHTML = '<span>Login</span><i class="fas fa-sign-in-alt"></i>';
        btn.disabled = false;
      });
    }
  </script>
</body>
</html>