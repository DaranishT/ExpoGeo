<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Expo GeoPage</title>
  <link rel="stylesheet" href="css/hypnotic.css">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <style>
    /* Temporary inline styles until we fix the CSS file */
    .hypnotic-about {
      padding: 4rem 2rem;
      background: rgba(10, 10, 20, 0.9);
    }
    
    .about-container {
      max-width: 1200px;
      margin: 0 auto;
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 3rem;
      align-items: center;
    }
    
    .about-content {
      padding: 2rem;
    }
    
    .about-text {
      color: #b0b0b0;
      line-height: 1.6;
      margin-top: 1.5rem;
    }
    
    .features-list {
      margin: 1.5rem 0;
      padding-left: 1rem;
      list-style: none;
    }
    
    .features-list li {
      margin-bottom: 0.8rem;
      display: flex;
      align-items: center;
      gap: 0.8rem;
    }
    
    .features-list i {
      color: #00f0ff;
      width: 20px;
    }
    
    .video-container {
      position: relative;
      border-radius: 10px;
      overflow: hidden;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
      border: 1px solid rgba(0, 240, 255, 0.3);
    }
    
    .video-placeholder {
      position: relative;
      padding-bottom: 56.25%;
      background: linear-gradient(135deg, #001a33, #003366);
    }
    
    .video-overlay {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      color: white;
      cursor: pointer;
    }
    
    .video-overlay i {
      font-size: 3rem;
      margin-bottom: 1rem;
      transition: transform 0.3s ease;
      color: #00f0ff;
    }
    
    .video-overlay:hover i {
      transform: scale(1.1);
    }
    
    @media (max-width: 768px) {
      .about-container {
        grid-template-columns: 1fr;
      }
      
      .video-container {
        margin-top: 2rem;
      }
    }
  </style>
</head>
<body class="hypnotic-theme">
  <?php include 'navbar.php'; ?>
  <?php include 'chatbot.php'; ?>
  
  <!-- Hero Section -->
  <section class="hypnotic-hero">
    <div class="hero-content">
      <h1 class="hero-title glitch-text" data-text="EXPO GEO">EXPO GEO</h1>
      <p class="hero-subtitle">Your premier platform for exhibition management</p>
      <div class="hero-cta">
        <a href="#about" class="cta-btn">
          <span>Learn More</span>
          <i class="fas fa-arrow-down"></i>
        </a>
      </div>
    </div>
  </section>


<!-- About Section -->
<section id="about" class="hypnotic-about">
  <div class="about-container">
    <div class="about-content">
      <h2 class="section-title">About Expo Geo</h2>
      <div class="about-text">
        <p>Expo Geo is a revolutionary platform connecting exhibition enthusiasts with immersive events worldwide.</p>
        <ul class="features-list">
          <li><i class="fas fa-calendar-alt"></i> Exhibition scheduling</li>
          <li><i class="fas fa-tasks"></i> Admin dashboard</li>
          <li><i class="fas fa-user-shield"></i> Role-based access</li>
        </ul>
      </div>
    </div>
  </div>
  
  <!-- Full-width Video Section Below About -->
  <div class="full-width-video">
    <div class="video-container">
      <!-- Choose either local video or embedded video -->
      
      <!-- Option 1: Local Video -->
      <video autoplay muted loop playsinline>
        <source src="videos/intro-video.mp4" type="video/mp4">
        Your browser does not support HTML5 video.
      </video>
      
      <!-- Option 2: YouTube Embed -->
      <!--
      <iframe src="https://www.youtube.com/embed/YOUR_VIDEO_ID?autoplay=1&mute=1&loop=1&playlist=YOUR_VIDEO_ID&controls=0" 
              frameborder="0" 
              allow="autoplay; encrypted-media" 
              allowfullscreen></iframe>
      -->
    </div>
  </div>
</section>
  </section>

  <?php include 'footer.php'; ?>

</body>
</html>