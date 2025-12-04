<?php
include 'ZooConnect.php';

$email = $_COOKIE['user_email'] ?? null;
$userName = $_COOKIE['user_name'] ?? '';
$points = 0;


if ($email) {
    // Get loyalty points
    $stmt = $conn->prepare("SELECT points FROM loyaltypoints WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->bind_result($points);
    $stmt->fetch();
    $stmt->close();
}
?>


<!DOCTYPE html>

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="zoo.css">
    <title> RZA Dashboard </title>
    <link rel = "icon" type = "x-icon" href = "zoofav.png">
    <script src="theme.js"></script>
</head>


<body>

  <!-- Navbar -->
  <div class="dash-navbar">
    <div class="dash-nav-left">
      <img src="zoofav.png" alt="Zoo Logo" class="nav-logo">
    </div>

    <ul class="dash-nav">
        <li><a href="index.html">Home</a></li>
        <li><a href="about.html">About</a></li>
        <li><a href="education.html">Educational Visits</a></li>
    </ul>

      <button type="button" onclick="changeTheme()">Change the Theme</button>


    <div class="dash-nav-right">
      <a href="BookTickets.php" class="signup-button">Book Tickets</a>
      <a href="BookStay.php" class="signup-button">Book Stay</a>
    </div>
  </div>



  <!-- Hero Section -->
  <div class="dash-hero-section">
    <div class="dash-hero-content-left">
      <h1 style="color:white">Welcome <?php echo htmlspecialchars($userName); ?>!</h1>
      <p style="color:white">~You are successfully logged in~</p>
    </div>
  </div>


  <!-- Bottom Banner -->
  <div class="dash-bottom-banner">
    <p class="note">The site is GDPR Compliant</p>
    <p>You have <?php echo $points ?> loyalty points.</p>
    <a href="CheckAvailability.php" class="button">Check Hotel Availability</a>
   
  </div>

</div>


</body>
</html>