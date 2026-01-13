<?php
session_start();
include 'healthconnect.php';


// Check if cookie consent already given
$CookieConsent = $_COOKIE['cookie_consent'] ?? null;


// Cookie consent form
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['accept_cookies'])) { # Checking if user accepted cookies
    setcookie("cookie_consent", "yes", time() + (86400 * 30), "/"); # sets cookie for 30 days (86400 seconds in a day)
    $CookieConsent = "yes";
}




// Login form
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['username'])) {

    $username = $_POST["username"];
    $password = $_POST["password"];

    // Prevent sql injection
    $sql = "SELECT * FROM users WHERE username = ?"; # ? is placeholder for a parameter to prevent sql injection
    $stmt = $conn-> prepare($sql);
    $stmt-> bind_param("s", $username);
    $stmt-> execute();
    $result = $stmt-> get_result();


    if ($result-> num_rows == 1) { # Checks if one matching user was found in db
        $row = $result-> fetch_assoc(); # Fetches users row ($row['userid'], $row['password'], etc.)

        if (password_verify($password, $row["password_hash"])) { # compares entered password to hashed password in db
            // store user in session so they stayed logged in
            $_SESSION["user_id"] = $row["user_id"];
            $_SESSION["username"] = $row["username"];
            $_SESSION["name"] = $row["name"];
            $_SESSION["email"] = $row["email"];


            // Set cookie only if consent given
            if ($CookieConsent === "yes"){
                setcookie("user_email", $row["email"], time() + (86400 * 30), "/");
                setcookie("user_name", $row["name"], time() + (86400 * 30), "/");
            }

            header("Location: health_dash.php"); // Redirects to dashboard
            exit;

        } else {
            $error = "Incorrect password";
        }
    } else {
        $error = "User not found";
    }

    $stmt-> close();
    $conn-> close();
}
?><!DOCTYPE html>
<html>
    <head>
        <meta charset = "UTF-8">
        <title> Login </title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
        <link rel = "stylesheet" href = "health.css">
        <script src="healththeme.js"></script>

        <style>
            .cookie-popup {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0,0,0,0.7);
                display: flex;
                align-items: center;
                justify-content: center;
                z-index: 9999; /* Ensures its ontop of login form */
            }

            .cookie-popup .popup-content {
                background: var(--primary-bg);
                color: black;
                font-size: 15px;
                padding: 25px;
                border-radius: 10px;
                text-align: center;
                max-width: 400px;
                box-shadow: 0 0 20px rgba(15, 95, 170, 0.5);
            }

            .cookie-popup button {
                background: var(--button-bg);
                color: var(--button-text);
                padding: 10px 20px;
                border-radius: 6px;
                margin-top: 15px;
                cursor: pointer;
            }

            .cookie-popup .popup-content p {
                background: transparent;
                margin: 0;
                padding: 0;
            }
        </style>

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

            

    <!-- Login form -->
     <div class="main2 login-page">
        <div class="login">
            <h1>Login to your account</h1>
            <br>
            <form action = "health_login.php" method="post">

                <label>Username</label>
                <input type="text" name="username" placeholder="Enter your username.." required>

                <label>Password</label>
                <input type="password" name="password" placeholder="Enter your password.." required>
                
                <br><br><br><br>

                <button type="submit">Login</button>

                <br><br><br>

                <p style="font-weight:bold; color:black;">
                    Don't have an account? <br><br>
                    <a href="health_sign-up.php" class="login-button">Register here</a>
                </p>

                 <?php if (!empty($error)) echo "<p style='color:red;'>$error</p>"; ?>
            </form>

        </div>
    </div>

           


            <!-- Cookie consent banner -->
             <?php if (!$CookieConsent): ?>
                <div class="cookie-popup">
                    <div class="popup-content">

                        <p>We use cookies to improve your experience. By clicking accept, you consent to cookies.</p>
                        <form method="post">
                            <button type="submit" name="accept_cookies">Accept</button>

                        </form>
                    </div>
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


    </body>
</html>



