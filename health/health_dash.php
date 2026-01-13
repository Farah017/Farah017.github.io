<?php
include 'healthconnect.php';
session_start();
$user_id = $_SESSION['user_id']; # needed for db queries

// Fetch latest BMI
$bmi_sql="SELECT * FROM bmi_results where user_id = ? ORDER BY created_at DESC LIMIT 1";
$bmi_stmt = $conn-> prepare($bmi_sql);
$bmi_stmt-> bind_param("i", $user_id);
$bmi_stmt-> execute();
$bmi_result = $bmi_stmt-> get_result();
$latest_bmi = $bmi_result-> fetch_assoc();


// Fetch latest hydration
$hyd_sql="SELECT * FROM hydration_results where user_id = ? ORDER BY created_at DESC LIMIT 1";
$hyd_stmt = $conn-> prepare($hyd_sql);
$hyd_stmt-> bind_param("i", $user_id);
$hyd_stmt-> execute();
$hyd_result = $hyd_stmt-> get_result();
$latest_hydration = $hyd_result-> fetch_assoc();


$name = $_COOKIE['user_name'] ?? ''; # welcome name
?>



<!DOCTYPE html>
<html>
<head>
    <meta charset = "UTF-8">
    <title> Dashboard </title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="health.css">
    <link rel="icon" type="x-icon" href="images/Health logo.png">
    <script src="healththeme.js"></script>
</head>

<body>
    <div class="navbar">

        <!-- Favicon on left side of nav bar-->
        <div class="nav-left">
            <a href="healthindex.html">
                <img src="images/Health logo.png" alt="Health logo" class="nav-logo">        
             </a>
        </div>


        <ul class="nav">
            <li><a href="health_dash.php">Dashboard Home</a></li>
            <li><a href="health_tracker.php">Personal Health Tracker</a></li>
            <li><a href="personal_advice.php">Personalised Health Advice</a></li>
        </ul>


        <!-- Dark/Light mode button-->
        <button type= "button" onclick="changeTheme()">Change Theme</button>


        <!-- Sign-up/Login buttons on right side of nav bar-->
        <div class="nav-right">
            <a href="#logout" class="button">Log-out</a>
        </div>

    </div>



    <!-- Dashboard content -->
    <div class="dash-content">
        <h1> Welcome <?php echo htmlspecialchars($name); ?>!</h1>
        <a href="health_tracker.php" class="dash-button-left">Health Tracker</a>
        <a href="personal_advice.php" class="dash-button-right">Health Advice</a>
    </div>
    
    <div class="dash-banner">
        <h1> Your Health Overview </h1>
    </div>


    <div class="card-container">

        <!-- BMI Card -->
         <div class="health-card bmi-card">
            <div class="card-inner">
            <h3> Most Recent BMI Results: </h3>

            <?php if (!$latest_bmi): ?>
                <p>No BMI data yet.<br>Use the Health Tracker to calculate your BMI.</p>
                <a href="health_tracker.php" class="card-button">BMI Calculator</a>
            
            <?php else: ?>
                <p><strong>BMI:</strong> <?= $latest_bmi['bmi_value'] ?> </p>
                <p><strong>Category:</strong> <?= $latest_bmi['category'] ?> </p>
                <p><small>Last updated: <?= $latest_bmi['created_at'] ?> </small> </p>
            <?php endif; ?>
            </div>
        </div>


       <!-- Hydration Card -->
        <div class="health-card hydration-card">
            <div class="card-inner">
            <h3> Hydration Levels Today: </h3>

        <?php if (!$latest_hydration): ?>
            <p>No hydration data yet.<br>Log your water intake to get started.</p>
            <a href="health_tracker.php" class="card-button">Hydration Level Logger</a>

        <?php else: ?>

            <?php
                $glasses = $latest_hydration['glasses'];
                $goal = 8;

                if ($glasses < $goal) {
                    $hydration_message = "Keep going!";
                } elseif ($glasses == $goal) {
                    $hydration_message = "Great job — you've hit your target!";
                } else {
                    $hydration_message = "You've reached your goal for today.";
                }
            ?>

            <p><strong>Hydration:</strong> <?= $glasses ?> /<?= $goal ?></p>
            <p>- <?= $hydration_message ?></p>
            <p><small>Last updated: <?= $latest_hydration['created_at'] ?> </small></p>

        <?php endif; ?>
    </div>
</div>

</div>

    <br><br>
     <!-- Footer -->
     <footer class="site-footer">

        <div class="footer-links">

            <div class="footer-column">
                <h4>Explore</h4>
                <ul>
                    <li><a href="health_advice.html">Health Advice</a></li>
                    <li><a href="weather_forecast.php">Weather Forecast</a></li>
                    <li><a href="air_quality.php">Air Quality Dashboard</a></li>
                </ul>
            </div>

            <div class="footer-column">
                <h4>Resources</h4>
                <ul>
                    <li><a href="risk_assessment.php">Home Risk Assessment</a></li>
                    <li><a href="#contact">Contact Us</a></li>
                    <li><a href="#alerts">Alerts</a></li>
                </ul>
            </div>


            <div class="footer-column-social">
                <h4>Connect</h4>
                <div class="social-icons"></div>
                    <a href="#"><i class="fab fa-facebook-f"></i></a>
                    <a href="#"><i class="fab fa-instagram"></i></a>
                    <a href="#"><i class="fab fa-linkedin-in"></i></a>
                    <a href="#"><i class="fab fa-youtube"></i></a>
            </div>
            
        </div>

        <div class="footer-bottom">
            <p>&copy; 2025 Health Advice Group. All rights reserved.</p>
        </div>
     </footer>


    </body>
</html>
      


