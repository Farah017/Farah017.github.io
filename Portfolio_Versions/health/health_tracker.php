<?php
session_start();
?>


<!DOCTYPE html>
<html>
<head>
    <meta charset = "UTF-8">
    <title> Health Tracker </title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel = "icon" type = "x-icon" href = "images/Health logo.png">
    <link rel="stylesheet" href="health.css">
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





    <!-- Health Tracker content -->
    <div class="main4 health_content">
        <h1> Personal Health Tracker </h1>
        <p> Log your details to calculate BMI and monitor hydration. </p>



        <!-- BMI Form -->
        <div class="form-container">
            <div class="form-box">
                <h2> BMI Calculator </h2>
                <form method = "post" action = "health_tracker.php">

                    <label for="weight"> Weight (kg): </label>
                    <input type="integer" id="weight" name="weight" required>
                    <br>

                    <label for="height"> Height (cm): </label>
                    <input type="integer" id="height" name="height" required>
                    <br>
                    <br>

                    <button type="submit" name="bmi_submit">Calculate BMI</button>
                </form>
          


        <!-- BMI Result display -->
         <?php
            if (isset($_POST['bmi_submit'])) {

            include 'healthconnect.php';

            $weight = $_POST['weight'];
            $height = $_POST['height'] / 100;

            if ($height > 0) {
                $bmi = $weight / ($height * $height);
                $bmi = round($bmi, 2);


            // Determine category
            if ($bmi < 18.5) {
                $bmi_category = "Underweight";
            } elseif ($bmi < 24.9) {
                $bmi_category = "Healthy";
            } elseif ($bmi < 29.9) {
                $bmi_category = "Overweight";
            } else {
                $bmi_category = "Obese";
            }

            echo "<h2>Your BMI is: $bmi</h2>";
            echo "<p>You are $bmi_category.</p>";


            // Get user id from session
            if (isset($_SESSION['user_id'])) {
                $user_id = $_SESSION['user_id'];
            } else {
                echo "<p>Error: No user logged in.</p>";
            exit;
            }


            // Save to database
            $sql = "INSERT INTO bmi_results (user_id, bmi_value, category, created_at)
                    VALUES ('$user_id', '$bmi', '$bmi_category', NOW())";

            $conn->query($sql);
            } else {
                echo "<p>Please enter a valid height.</p>";
            }
        }
        
        ?>
      </div>



            <br><br>
            <!-- Hydration Form -->
           <div class="form-box">
                <h2> Hydration Tracker </h2>
                <form method="post" action="health_tracker.php">

                    <label for="water_intake">Glasses of water today:</label>
                    <input type="number" id="water_intake" name="water_intake" placeholder="Enter number of glasses..." required>
                    <br>

                    <button type="submit" name="hydration_submit">Log Water Intake</button>
                </form>



        <!-- Hydration Result display -->
        <?php
        include 'healthconnect.php';

        if (isset($_POST['hydration_submit'])) {

            $glasses = $_POST['water_intake'];


        // get user id from session   
        if (isset($_SESSION['user_id'])) {
            $user_id = $_SESSION['user_id'];
        } else {
            echo "<p>Error: No user logged in.</p>";
        exit;
        }


        // save to database
        $sql = "INSERT INTO hydration_results (user_id, glasses, created_at)
                VALUES ('$user_id', '$glasses', NOW())";
        echo "<br>";

        if ($conn->query($sql) === TRUE) {
            echo "<p>You logged $glasses glasses of water today.</p>";
        } else {
            echo "<p>Error logging water intake: " . $conn->error . "</p>";
        }

        $conn->close();
        }

        ?>
      </div>
        </div>
        </div>





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