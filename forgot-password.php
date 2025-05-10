<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Forgot Password - Expo GeoPage</title>
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
    
    .btn-login {
      padding: 0.75rem;
      background: var(--primary);
      color: var(--dark);
      border: none;
      border-radius: 5px;
      font-weight: 500;
      cursor: pointer;
      transition: all 0.3s ease;
    }
    
    .btn-login:hover {
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
    <h1 class="glitch-text" data-text="FORGOT PASSWORD">FORGOT PASSWORD</h1>
    <form id="forgot-password-form" class="auth-form">
      <div id="step-1">
        <label for="email">Email</label>
        <input type="email" id="email" name="email" placeholder="Enter your email" required>
        <button type="button" id="submit-email" class="btn-login">Submit Email</button>
      </div>

      <div id="step-2" style="display: none;">
        <label for="otp">Enter OTP</label>
        <input type="text" id="otp" name="otp" placeholder="Enter the OTP sent to your email" required>
        <button type="button" id="submit-otp" class="btn-login">Submit OTP</button>
      </div>

      <div id="step-3" style="display: none;">
        <label for="new-password">New Password</label>
        <input type="password" id="new-password" name="new-password" placeholder="Enter your new password" required>

        <label for="confirm-new-password">Confirm New Password</label>
        <input type="password" id="confirm-new-password" name="confirm-new-password" placeholder="Confirm your new password" required>

        <button type="submit" class="btn-login">Reset Password</button>
      </div>

      <p class="auth-link">
        Remember your password? <a href="login.php">Login</a>
      </p>
    </form>
  </main>

  <script>
    // Your existing JavaScript code here
    document.getElementById('submit-email').addEventListener('click', function () {
        const email = document.getElementById('email').value;
        console.log('Email:', email);

        if (email) {
            fetch('send_otp.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ email: email })
            })
            .then(response => {
                console.log('Response received:', response);
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                console.log('Data:', data);
                if (data.status === 'success') {
                    alert(data.message);
                    document.getElementById('step-1').style.display = 'none';
                    document.getElementById('step-2').style.display = 'block';
                } else {
                    alert(data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred. Please try again.');
            });
        } else {
            alert('Please enter a valid email address.');
        }
    });
  
    document.getElementById('submit-otp').addEventListener('click', function () {
        const email = document.getElementById('email').value;
        const otp = document.getElementById('otp').value;
        console.log('OTP:', otp);

        if (otp) {
            fetch('verify_otp.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ email: email, otp: otp })
            })
            .then(response => {
                console.log('Response received:', response);
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                console.log('Data:', data);
                if (data.status === 'success') {
                    alert(data.message);
                    document.getElementById('step-2').style.display = 'none';
                    document.getElementById('step-3').style.display = 'block';
                } else {
                    alert(data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred. Please try again.');
            });
        } else {
            alert('Please enter the OTP.');
        }
    });
  
    document.getElementById('forgot-password-form').addEventListener('submit', function (e) {
        e.preventDefault();
        const email = document.getElementById('email').value;
        const newPassword = document.getElementById('new-password').value;
        const confirmNewPassword = document.getElementById('confirm-new-password').value;

        if (newPassword === confirmNewPassword) {
            fetch('reset_password.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ email: email, new_password: newPassword })
            })
            .then(response => {
                console.log('Response received:', response);
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                console.log('Data:', data);
                if (data.status === 'success') {
                    alert(data.message);
                    window.location.href = 'login.php';
                } else {
                    alert(data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred. Please try again.');
            });
        } else {
            alert('Passwords do not match. Please try again.');
        }
    });
  </script>

  <?php include 'footer.php'; ?>
</body>
</html>