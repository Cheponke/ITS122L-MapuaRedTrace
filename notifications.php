<?php
session_start();
include 'config.php';


// count urgent
$urgent_count = $conn->query("SELECT COUNT(*) as total FROM notifications WHERE type='urgent'")
->fetch_assoc()['total'];

// count events
$event_count = $conn->query("SELECT COUNT(*) as total FROM notifications WHERE type='event'")
->fetch_assoc()['total'];

// count reminders
$reminder_count = $conn->query("SELECT COUNT(*) as total FROM notifications WHERE type='reminder'")
->fetch_assoc()['total'];

// total notifications
$total_count = $conn->query("SELECT COUNT(*) as total FROM notifications")
->fetch_assoc()['total'];

?>


<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Alerts & Notifications | Red Trace</title>
    <link rel="stylesheet" href="style.css" />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"
    />
  </head>
  <body class="notifications-page help-page">
    <header id="main-header">
      <nav class="navbar">
        <div class="logo">
          <a
            href="index.php"
            style="
              text-decoration: none;
              display: flex;
              align-items: center;
              gap: 12px;
              color: inherit; 
            "
          >
            <img src="mapua-logo.png" alt="Mapúa Logo" />
            <span>Mapúa RedTrace</span>
          </a>
        </div>
        <ul class="nav-links">
          <li><a href="index.php">Home</a></li>
          <li><a href="help.php">Help/Policies</a></li>
          <li><a href="notifications.php">Notifications</a></li>
        </ul>
        <div class="nav-actions">
              <?php if(isset($_SESSION['user_id'])) { ?>

              <span style="color:white;font-weight:600;">
              Welcome, <?php echo $_SESSION['name']; ?>
              </span>

              <?php } else { ?>

              <a href="login.html" class="btn btn-red" style="text-decoration:none;">
              Sign-In/Register
              </a>

              <?php } ?>

              </div>
          <div class="user-icon"><i class="fa-regular fa-user"></i></div>
        </div>
      </nav>
    </header>

    <main class="notifications-container">
      <section class="notif-header">
        <h1>Alerts & Notifications</h1>
        <p>
          Manage and send notifications to donors about drives, emergencies, and
          updates
        </p>
      </section>

      <div class="notif-stats-grid">
        <div class="notif-stat-card">
          <div class="icon-box red-light">
            <i class="fa-solid fa-triangle-exclamation"></i>
          </div>
          <div class="stat-info">
            <span class="count"><?php echo $urgent_count; ?></span><span class="label">Urgent Alerts</span>
          </div>
        </div>
        <div class="notif-stat-card">
          <div class="icon-box orange-light">
            <i class="fa-solid fa-calendar-day"></i>
          </div>
          <div class="stat-info">
            <span class="count"><?php echo $event_count; ?></span><span class="label">Event Notices</span>
          </div>
        </div>
        <div class="notif-stat-card">
          <div class="icon-box green-light">
            <i class="fa-solid fa-bell"></i>
          </div>
          <div class="stat-info">
           <span class="count"><?php echo $reminder_count; ?></span><span class="label">Reminders</span>
          </div>
        </div>
        <div class="notif-stat-card">
          <div class="icon-box blue-light">
            <i class="fa-solid fa-paper-plane"></i>
          </div>
          <div class="stat-info">
            <span class="count"><?php echo $total_count; ?></span><span class="label">Total Sent</span>
          </div>
        </div>
      </div>

    

    <div class="notif-list" id="notif-list">

          <?php
          include 'config.php';

          $sql = "SELECT * FROM notifications ORDER BY created_at DESC";
          $result = $conn->query($sql);

          while($row = $result->fetch_assoc()){

          $type = $row['type'];

          $icon = "fa-circle-info";
          $color = "blue-light";

          if($type == "urgent"){
          $icon = "fa-triangle-exclamation";
          $color = "red-light";
          }

          if($type == "event"){
          $icon = "fa-calendar-day";
          $color = "orange-light";
          }

          if($type == "reminder"){
          $icon = "fa-bell";
          $color = "green-light";
          }
          ?>

          <div class="notif-card <?php echo $row['type']; ?>">

          <div class="icon-box <?php echo $color; ?>">
          <i class="fa-solid <?php echo $icon; ?>"></i>
          </div>

          <div class="card-content">

          <div class="card-title-row">
          <h3><?php echo $row['title']; ?></h3>

          <div class="badges">
          <span class="badge <?php echo $row['priority']; ?>">
          <?php echo strtoupper($row['priority']); ?>
          </span>
          <span class="badge status">Sent</span>
          </div>

          </div>

          <p><?php echo $row['message']; ?></p>

          <div class="card-meta">
          <span><i class="fa-regular fa-calendar"></i> <?php echo $row['created_at']; ?></span>
          <span><i class="fa-solid fa-users"></i> <?php echo $row['recipients']; ?></span>
          </div>

          </div>

          </div>

          <?php } ?>


</div>

    <footer>
      <div class="footer-container">
        <div class="footer-brand">
          <div class="logo">
            <img src="mapua-logo.png" alt="Mapúa Logo" />
            <span>RedTrace</span>
          </div>
          <p>
            Empowering our university community to save lives through efficient
            blood donation management and transparent tracking systems.
          </p>
          <div class="social-icons">
            <a href="#"><i class="fa-brands fa-facebook-f"></i></a>
            <a href="#"><i class="fa-brands fa-twitter"></i></a>
            <a href="#"><i class="fa-brands fa-instagram"></i></a>
            <a href="#"><i class="fa-brands fa-linkedin-in"></i></a>
          </div>
        </div>
        <div class="footer-links">
          <h4>Quick Links</h4>
          <a href="index.php">Home</a>
          <a href="#">Donate Now</a>
          <a href="#">Dashboard</a>
          <a href="notifications.php">Notifications</a>
          <a href="help.php">Help/Policies</a>
        </div>
        <div class="footer-links">
          <h4>Resources</h4>
          <a href="#">Donation Guidelines</a>
          <a href="#">Eligibility Criteria</a>
          <a href="#">FAQs</a>
          <a href="#">Privacy Policy</a>
          <a href="#">Terms of Service</a>
        </div>
        <div class="footer-contact">
          <h4>Contact Us</h4>
          <p>
            <i class="fa-solid fa-location-dot"></i> University Medical Center
            Room 201, Main Campus
          </p>
          <p><i class="fa-solid fa-phone"></i> +1 (555) 123-4567</p>
          <p><i class="fa-solid fa-envelope"></i> info@redtrace.edu</p>
          <p><i class="fa-solid fa-clock"></i> Mon-Fri: 9AM - 5PM</p>
        </div>
      </div>
      <div class="footer-bottom">
        <p>&copy; 2026 RedTrace. All rights reserved.</p>
        <p>Powered by VSCode</p>
      </div>
    </footer>

    <script src="script.js"></script>
  </body>
</html>
