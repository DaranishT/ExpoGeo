<?php if (session_status() === PHP_SESSION_NONE) session_start(); ?>
<nav class="hypnotic-nav">
  <div class="nav-container">
    <a href="index.php" class="logo-glitch">
      <span class="logo-part">EXPO</span>
      <span class="logo-part glitch-layers">
        <span class="glitch-layer">GEO</span>
        <span class="glitch-layer">GEO</span>
        <span class="glitch-layer">GEO</span>
      </span>
    </a>
    
    <div class="nav-links">
      <a href="index.php" class="nav-link hover-underline">Home</a>
      <a href="expos.php" class="nav-link hover-underline">Expos</a>
      <?php if (isset($_SESSION['user_id'])): ?>
        <?php if ($_SESSION['user_role'] === 'super_admin'): ?>
          <a href="admin-requests.php" class="nav-link hover-underline">Admin Requests</a>
        <?php endif; ?>
        <?php if ($_SESSION['user_role'] === 'admin' || $_SESSION['user_role'] === 'super_admin'): ?>
          <a href="admin.php" class="nav-link hover-underline">Admin</a>
        <?php endif; ?>
        <a href="account.php" class="nav-link user-icon">
          <i class="fas fa-user"></i> <?php echo htmlspecialchars($_SESSION['user_name']); ?>
        </a>
      <?php else: ?>
        <a href="login.php" class="nav-link login-btn">Login</a>
      <?php endif; ?>
    </div>
  </div>
</nav>