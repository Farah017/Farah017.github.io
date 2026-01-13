<?php
$advice = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $heat = isset($_POST["heat"]);
    $damp = isset($_POST["damp"]);
    $vent = isset($_POST["ventilation"]);
    $aqi = isset($_POST["aqi"]);
    $notes = $_POST["notes"] ?? "";

    $advice .= "<h2>Your Home Safety Advice </h2>";

    if(!$heat) {
        $advice .="<p>• Your heating or alarms may need checking. Consider testing alarms and ensuring all rooms heat properly.</p>";
    }

    if (!$damp) { $advice .= "<p>• Damp or mould can affect breathing. Try improving ventilation or contacting your landlord.</p>"; } 
    
    if (!$vent) { $advice .= "<p>• Regular ventilation helps reduce moisture and improve air quality.</p>"; } 
    
    if (!$aqi) { $advice .= "<p>• During pollution alerts, keep windows closed and consider using an air purifier.</p>"; } 
    
    if ($notes !== "") { $advice .= "<p><strong>Your notes:</strong> $notes</p>"; } 
    
    if (
        $heat &&
        $damp &&
        $vent &&
        $aqi &&
        $notes === ""
    ) {
        $advice .= "<p>Your home looks safe and well‑maintained. Great job!</p>";
    }

}



?>




<!DOCTYPE html>

<head>
    <meta charset = "UTF-8">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel = "stylesheet" href = "health.css">
    <title> Home Risk Assessment </title>
    <link rel = "icon" type = "x-icon" href = "images/Health logo.png">
    <script src="healththeme.js"></script>
   

</head>

<!-- Navigation Bar -->
 <body style="background-color: #5C6C7D;">
    <div class = "navbar">

        <!-- Favicon on left side of nav bar-->
        <div class = "nav-left">
            <a href="healthindex.html">
            <img src = "images/Health logo.png" alt = "Health Logo" class = "nav-logo"> 
            </a>
        </div>


        <ul class = "nav">
            <li><a href="healthindex.html">Home</a></li>
            <li><a href="health_advice.html">Health Advice</a></li>
            <li><a href="weather_forecast.php">Weather Forecast</a></li>
            <li><a href="air_quality.php">Air Quality Dashboard</a></li>
            <li><a href="risk_assessment.php">Home Risk Assessment</a></li>
        </ul>


        <!-- Dark/Light mode button-->
        <button type = "button" onclick="changeTheme()">Change Theme</button>


         <!-- Sign-up/Login buttons on right side of nav bar-->
        <div class = "nav-right">
            <a href="health_login.php" class ="button"> Login</a>
            <a href="health_sign-up.php" class ="sign-up-button">Sign-Up</a>
        </div>
    
    </div>


     <!-- Risk Assessment Form -->
    <div class="assessment">
        <h1>Home Risk Assessment</h1>
        <p> Complete this checklist to identify risks in your home environment.</p>

        <form action ="risk_assessment.php #ResultsBox" method="post">

        <h3> Heating & Safety</h3>

        <label>
        <input type="checkbox" name="heat">
        Heating works consistently in all rooms
        </label>

        <label>
        <input type="checkbox" name="heat">
        Smoke / Carbon monoxide alarms installed
        </label>

        <br><br>

        <h3> Damp & Ventilation </h3>

        <label>
        <input type="checkbox" name="damp">
        No visible damp or mould
        </label>

        <label>
        <input type="checkbox" name="ventilation">
        Rooms ventilated regularly
        </label>

        <br><br>

        <h3> Air Quality </h3>

        <label>
        <input type="checkbox" name="aqi">
        Windows closed during pollution alerts
        </label>

        <label>
        <input type="checkbox" name="aqi">
        Air filter or purifier in use
        </label>

        <br>

        <label style="font-weight: bold;">Notes</label>
        <input type="text" name="notes" placeholder="Add any concerns or observations here..">

        <br><br>
        
        <button type="submit">Submit</button>

        </form>


        <br> <br>

        <!-- Results Box -->

    <?php if (!empty($advice)): ?>
        <div id="ResultsBox" style="
            background:#FFD966;
            color:#333;
            padding:20px;
            border-radius:8px;
            margin:20px auto;
            width:80%;
            max-width:650px;
            border:2px solid #C49A00;
            font-size:18px;
            line-height:1.6;
        ">
        <strong>⚠ Home Safety Advice</strong><br><br>
        <?php echo $advice; ?>
        </div>
    <?php endif; ?>




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
