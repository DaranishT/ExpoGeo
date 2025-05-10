<?php
session_start();
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm-password'];
    $requestAdmin = isset($_POST['request_admin']) ? 1 : 0;

    if ($password !== $confirmPassword) {
        echo json_encode(['status' => 'error', 'message' => 'Passwords do not match.']);
        exit;
    }

    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    try {
        $stmt = $conn->prepare("INSERT INTO users (name, email, password, role, request_admin) VALUES (:name, :email, :password, 'user', :request_admin)");
        $stmt->execute([
            'name' => $name,
            'email' => $email,
            'password' => $hashedPassword,
            'request_admin' => $requestAdmin
        ]);

        $_SESSION['user_id'] = $conn->lastInsertId();
        $_SESSION['user_email'] = $email;
        $_SESSION['user_role'] = 'user';

        echo json_encode(['status' => 'success', 'message' => 'Signup successful. Redirecting...']);
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
  <title>Sign Up - Expo GeoPage</title>
  <link rel="stylesheet" href="css/hypnotic.css">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <style>
    .auth-container {
      max-width: 500px;
      margin: 3rem auto;
      padding: 2rem;
      background: rgba(10, 10, 20, 0.8);
      border: 1px solid rgba(0, 240, 255, 0.3);
      border-radius: 15px;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    }
    
    .auth-container h1 {
      text-align: center;
      color: var(--primary);
      margin-bottom: 2rem;
    }
    
    .auth-form {
      display: flex;
      flex-direction: column;
      gap: 1.5rem;
    }
    
    .auth-form label {
      color: var(--primary);
      font-weight: 500;
    }
    
    .auth-form input {
      width: 100%;
      padding: 0.75rem;
      background: rgba(255, 255, 255, 0.1);
      border: 1px solid rgba(0, 240, 255, 0.3);
      border-radius: 5px;
      color: white;
      font-family: 'Inter', sans-serif;
    }
    
    .auth-form input:focus {
      outline: none;
      border-color: var(--primary);
      box-shadow: 0 0 0 2px rgba(0, 240, 255, 0.2);
    }
    
    .request-admin-label {
      display: flex;
      align-items: center;
      gap: 0.5rem;
      color: var(--gray);
      cursor: pointer;
    }
    
    .btn-signup {
      padding: 0.75rem;
      background: var(--primary);
      color: var(--dark);
      border: none;
      border-radius: 5px;
      font-weight: 500;
      cursor: pointer;
      transition: all 0.3s ease;
    }
    
    .btn-signup:hover {
      background: var(--primary-light);
      transform: translateY(-2px);
    }
    
    .auth-link {
      text-align: center;
      color: var(--gray);
    }
    
    .auth-link a {
      color: var(--primary);
      text-decoration: none;
    }
  </style>
</head>
<body class="hypnotic-theme">
  <?php include 'navbar.php'; ?>
  <?php include 'chatbot.php'; ?>

  <main class="auth-container">
    <h1 class="glitch-text" data-text="SIGN UP">SIGN UP</h1>
    <form id="signup-form" method="POST">
      <label for="name">Full Name</label>
      <input type="text" id="name" name="name" required>

      <label for="email">Email</label>
      <input type="email" id="email" name="email" required>

      <label for="password">Password</label>
      <input type="password" id="password" name="password" required>

      <label for="confirm-password">Confirm Password</label>
      <input type="password" id="confirm-password" name="confirm-password" required>

      <label class="request-admin-label">
        <input type="checkbox" id="request_admin" name="request_admin"> Request Admin Access
      </label>

      <button type="submit" class="btn-signup">Sign Up</button>
      <p class="auth-link">
        Already have an account? <a href="login.php">Login</a>
      </p>
    </form>
  </main>

  <?php include 'footer.php'; ?>
</body>
</html>