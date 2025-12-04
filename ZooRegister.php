<!DOCTYPE html>
<html lang = "en">
    <head>
        <meta charset = "UTF-8">
        <title> Register </title>
        <link rel = "stylesheet" href = "zoo.css">
        <script src="theme.js"></script>
    </head>

<body>

<button type="button" onclick="changeTheme()">Change the Theme</button>
 

  
    <div class="register-page">
    <div class="register-container">

        <h1>Create New Account</h1>
        <form action="ZooRegister.php" method="post">
            
            <label>First Name:</label>
            <input type="text" name="fname" required>

            <label>Last Name:</label>
            <input type="text" name="lname" required>
            
            <label>Email:</label>
            <input type="text" name="email" required>
            
            <label>Username:</label>
            <input type="text" name="username" required>
            
            <label>Password:</label>
            <input type="password" name="password" required>
            
            <button type="submit">Register</button>
        </form>

         <p class="register-login-link">
            Already have an account?
            <a href="ZooLogin.php" class="btn-link">Login here</a>
        </p>

    </div>
    </div>

 


    <?php
    include 'ZooConnect.php';

    if ($_SERVER["REQUEST_METHOD"] == "POST") {  # "POST" means the form was submitted — so the code inside only runs when you press the Register button.
        $fname = trim($_POST['fname']); 
        $lname = trim($_POST['lname']);
        $email = trim($_POST['email']);
        $username = trim($_POST['username']);
        $password = $_POST['password']; 


     
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT); # Hashed password for security


        // Check if username already exists
        $checkSql = "SELECT * FROM users WHERE username = ?";

        $stmt = $conn->prepare($checkSql); 
        $stmt->bind_param("s", $username); 
        $stmt->execute();
        $result = $stmt->get_result();  # $result stores the returned data (if any)




        // If the username is already taken, display an error message.
        if ($result->num_rows > 0) {

            echo "<p style='color:red;'> Username already exists. Try another one.</p>";
        
        } else {
            // Insert new user
           $sql = "INSERT INTO users (fname, lname, email, username, password) VALUES (?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssss", $fname, $lname, $email, $username, $hashedPassword);


            if ($stmt->execute()) {
                echo "<p style='color: green;'> Account created successfully! Redirecting to login...</p>";
                header("refresh:2;url=ZooLogin.php");
                
            } else {
                echo "<p style='color:red;'> Error creating account.</p>";
            }
        }

        $stmt->close();
        $conn->close();
    }
    ?>

</body>
</html>