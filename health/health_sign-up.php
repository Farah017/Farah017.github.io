<?php
    include 'healthconnect.php';

    // gets registration details from users table in database
    if ($_SERVER["REQUEST_METHOD"]=="POST") {
        $email = trim($_POST['email']);
        $name = trim($_POST['name']);
        $username = trim($_POST['username']);
        $password = $_POST['password'];

        // Hashed password for security
        $hashed_password = password_hash($password, PASSWORD_DEFAULT); 


        // Check if username already exists
        $check_sql = "SELECT * FROM users WHERE username = ?";

        $stmt = $conn->prepare($check_sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();


        // Error message if username already exists
        if($result -> num_rows > 0) {
            echo"<div class='message error'> Username already exists. Try again </div>";

        } else {
            // Create new user
            $sql = "INSERT INTO users (email, name, username, password_hash) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssss", $email, $name, $username, $hashed_password);


            // If successful
            if($stmt -> execute()) {
                echo "<div class='message success'> Account created successfully! Redirecting to Login..</div>";
                // Redirect to login
                header("refresh: 2; url= health_login.php");

            } else {
                echo "<div class='message error'> Error creating account.</div>";
            }
        }

        $stmt -> close();
        $conn -> close();
    }

    ?>



<DOCTYPE html>
<html>
    <head>
        <meta charset = "UTF-8">
        <title> Sign-Up </title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
        <link rel = "stylesheet" href = "health.css">
        <script src="healththeme.js"></script>
    </head>


    <body>

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

  


     <!-- Register Form -->
    <div class="register-page">
    <div class="sign-up">
        <h1> Create a New Account </h1>
        <form action ="health_sign-up.php" method="post">

            <label>Email</label>
            <input type="email" name="email" placeholder="john.doe@email.com" required>

            <label>Name</label>
            <input type="text" name="name" placeholder="John Doe" required>

            <label>Username</label>
            <input type="text" name="username" placeholder="John_D123" required>

            <label>Password</label>
            <input type="password" name="password" placeholder="Enter Password.." required>

            <br><br>
            <label>
            <input type="checkbox" name="terms" required>
            I accept the Terms and Conditions
            </label>

            <br><br>
            <button type="submit"> Sign-Up</button>

            <br><br>

            <!-- Redirect to login if account already exists -->
            <p style="font-weight: bold; color: black;">
                Already have an account? <br><br>
                <a href="health_login.php" class="login-button">Login here</a>
            </p>

    </form>
        

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