<?php
session_start();
include 'ZooConnect.php';

// Check if cookie consent already given
$cookieConsent = $_COOKIE['cookie_consent'] ?? null;


// Handle consent form
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['accept_cookies'])) { # Checks if the form was submitted (POST) and if the user clicked “accept cookies”
    setcookie("cookie_consent", "yes", time() + (86400 * 30), "/");
    $cookieConsent = "yes";
}



// Handle login form
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['username'])) {
    
    $username = $_POST["username"];
    $pass = $_POST["password"];


    $sql = "SELECT * FROM users WHERE username = ?"; # The ? is a placeholder for a parameter (to prevent SQL injection)

    $stmt = $conn->prepare($sql); # prepares the query safely
    $stmt->bind_param("s", $username); # binds the username to the ?. "s" means string
    $stmt->execute(); # runs the query
    $result = $stmt->get_result(); # fetches the result set




    if ($result->num_rows == 1) { # Checks if exactly one user was found

        $row = $result->fetch_assoc(); # Fetches the user’s row as an associative array ($row['userid'], $row['password'], etc.)

        if (password_verify($pass, $row["password"])) { # Compares the entered password ($pass) with the hashed password stored in the database ($row["password"])
            // Store in session
            $_SESSION["userid"] = $row["userid"];
            $_SESSION["username"] = $row["fname"] . " " . $row["lname"];
            $_SESSION["email"] = $row["email"];


            // Only set cookies if consent given
            if ($cookieConsent === "yes") {
                setcookie("user_email", $row["email"], time() + (86400 * 30), "/");
                setcookie("user_name", $row["fname"] . " " . $row["lname"], time() + (86400 * 30), "/");
            }

            header("Location: ZooDash.php"); // Redirects to dashboard after user has logged in
            exit;
        
          } else {
            $error = "Incorrect password";
        }
    
      } else {
        $error = "User not found";
    }

    $stmt->close(); # Closes the prepared statement 
    $conn->close(); # Closes the database connection
}
?>



<!DOCTYPE html>
<html>

<head>
    <title>Login</title>
    <link rel="stylesheet" href="zoo.css">
    <script src="theme.js"></script>
    
  <style>
  .cookie-popup {
  position: fixed;
  top: 0; /* starts at the very top */
  left: 0; /* starts at the very left */
  width: 100%; /* covers the full width of the screen */
  height: 100%; /* covers the full height of the screen */
  background: rgba(0,0,0,0.7); /* dark transparent overlay */
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 9999; /* ensures it’s above the login form */
}

.cookie-popup .popup-content {
  background: #3D513D;
  color: white;
  font-weight: normal;
  font-size: 15px;
  padding: 25px;
  border-radius: 10px;
  text-align: center;
  max-width: 400px;
  box-shadow: 0 0 20px rgba(0,255,136,0.5);
}

.cookie-popup button {
  background: black;
  color: white;
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
    <button type="button" onclick="changeTheme()">Change the Theme</button>


    <form action="ZooLogin.php" method="post">
        
      <h1>Login</h1>

        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Login</button>

    </form>


   <p class="register-login-link">
        Don't have an account?
        <a href="ZooRegister.php" class="btn-link">Register here</a>
  </p>
 
    <?php if (!empty($error)) echo "<p style='color:red;'>$error</p>"; ?>
      

    <!-- Cookie Consent Banner -->
    <?php if (!$cookieConsent): ?> <!-- overlay blocks interaction with login form until the user accepts. -->
      <div class="cookie-popup">
        <div class="popup-content">
          
          <p>We use cookies to improve your experience. By clicking accept, you consent to cookies.</p>
          <form method="post">
          <button type="submit" name="accept_cookies">Accept</button>
          
        </form>
        </div>
    </div>
  <?php endif; ?>



</body>
</html>
