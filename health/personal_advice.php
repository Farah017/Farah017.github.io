<?php
session_start();
include 'healthconnect.php';

$user_id = $_SESSION['user_id'] ?? null; 

# Initialize variables
$latest_bmi = null; 
$latest_hydration = null;
$weather = null;
$temperature = null;

/* -----------------------------FETCH BMI + HYDRATION------------------------------ */

if ($user_id) {

    // Fetch latest BMI
    $bmi_sql = "SELECT * FROM bmi_results WHERE user_id = ? ORDER BY created_at DESC LIMIT 1";
    $bmi_stmt = $conn->prepare($bmi_sql);
    $bmi_stmt->bind_param("i", $user_id);
    $bmi_stmt->execute();

    $bmi_result = $bmi_stmt->get_result();
    $latest_bmi = $bmi_result->fetch_assoc();



    // Fetch latest hydration
    $hyd_sql = "SELECT * FROM hydration_results WHERE user_id = ? ORDER BY created_at DESC LIMIT 1";
    $hyd_stmt = $conn->prepare($hyd_sql);
    $hyd_stmt->bind_param("i", $user_id);
    $hyd_stmt->execute();

    $hyd_result = $hyd_stmt->get_result();
    $latest_hydration = $hyd_result->fetch_assoc();
}



/* -----------------------------FETCH WEATHER (Sheffield)------------------------------ */

$apiKey = "2a1b96da878015fa6ccc0dc3e24302fd";
$city = "Sheffield";

// Get coordinates
$geo_url = "http://api.openweathermap.org/geo/1.0/direct?q=$city&limit=1&appid=$apiKey";
$geo_data = json_decode(file_get_contents($geo_url), true);

if (!empty($geo_data)) {
    $lat = $geo_data[0]['lat'];
    $lon = $geo_data[0]['lon'];


    // Get current weather
    $weather_url = "http://api.openweathermap.org/data/2.5/weather?lat=$lat&lon=$lon&appid=$apiKey&units=metric";
    $weather_data = json_decode(file_get_contents($weather_url), true);


    if (!empty($weather_data)) {
        $weather = ucfirst($weather_data['weather'][0]['main']);   // Rain, Clear, Clouds
        $temperature = round($weather_data['main']['temp']);        // e.g. 12
    }
}

// Safe fallback values
$weather = $weather ?? "Unknown"; 
$temperature = $temperature ?? 0;




/* -----------------------------BMI ADVICE LOGIC------------------------------ */

if (!$latest_bmi) {
    $bmi_advice = "No BMI data yet. Log your BMI to receive personalised advice.";
    $bmi_icon = "ℹ️";

    // Determine advice based on BMI category and weather
} else {
    $category = $latest_bmi['category'];
    
    //Underweight
    if ($category == "Underweight") {
        if ($weather == "Rain") {
            $bmi_advice = "Your BMI is below the healthy range and it's raining. Try gentle indoor exercises and focus on balanced nutrition.";
            $bmi_icon = "🌧️";
        } elseif ($weather == "Clear" || $weather == "Sunny") {
            $bmi_advice = "Your BMI is below the healthy range and it's sunny. A short walk and nutrient-rich meals could help.";
            $bmi_icon = "☀️";
        } else {
            $bmi_advice = "Your BMI is below the healthy range. Prioritise rest, gentle movement, and nourishing meals.";
            $bmi_icon = "🍲";
        }



    //Overweight or Obese
    } elseif ($category == "Overweight" || $category == "Obese") {
        if ($weather == "Rain") {
            $bmi_advice = "Your BMI is above the healthy range and it's raining. Try yoga or body weight workouts at home.";
            $bmi_icon = "🌧️";
        } elseif ($weather == "Clear" || $weather == "Sunny") {
            $bmi_advice = "Your BMI is above the healthy range and it's sunny. A walk or jog outdoors could help.";
            $bmi_icon = "☀️";
        } else {
            $bmi_advice = "Your BMI is above the healthy range. Consider indoor cardio or stretching.";
            $bmi_icon = "🏃‍♀️";
        }


        //Healthy
    } elseif ($category == "Healthy") {
        $bmi_advice = "Your BMI is within a healthy range. Keep up the good work!";
        $bmi_icon = "✅";

        //Fallback
    } else {
        $bmi_advice = "BMI category not recognised. Please check your input.";
        $bmi_icon = "❓";
    }
}





/* -----------------------------HYDRATION ADVICE LOGIC------------------------------ */

if (!$latest_hydration) {
    $hydration_advice = "No hydration data yet. Log your water intake to receive personalised advice.";
    $hydration_icon = "ℹ️";

} else {
    $glasses = $latest_hydration['glasses'];
    $goal = 8;

    if ($temperature > 25) { // hot weather
        if ($glasses < $goal) {
            $hydration_advice = "It's hot in your area and you've logged only $glasses/$goal glasses. Drink more water to stay safe.";
            $hydration_icon = "☀️💧";
        } else {
            $hydration_advice = "It's hot and you're well hydrated. Great job!";
            $hydration_icon = "☀️✅";
        }

    } else { // normal weather
        if ($glasses < $goal) {
            $hydration_advice = "You've logged $glasses/$goal glasses. Try to reach your hydration goal.";
            $hydration_icon = "💧";
        } else {
            $hydration_advice = "You've reached your hydration goal. Stay consistent!";
            $hydration_icon = "✅";
        }
    }
}
?>



<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Personalised Health Advice</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="health.css">
    <link rel = "icon" type = "x-icon" href = "images/Health logo.png">
    <script src="healththeme.js"></script>
</head>

<body>

<!-- Navigation Bar -->
<div class="navbar">
    <div class="nav-left">
        <a href="healthindex.html">
            <img src="images/Health logo.png" class="nav-logo">
        </a>
    </div>

    <ul class="nav">
        <li><a href="health_dash.php">Dashboard Home</a></li>
        <li><a href="health_tracker.php">Personal Health Tracker</a></li>
        <li><a href="personal_advice.php">Personalised Health Advice</a></li>
    </ul>

    <button onclick="changeTheme()">Change Theme</button>

    <div class="nav-right">
        <a href="#logout" class="button">Log-out</a>
    </div>
</div>





<!-- Personalised Health Advice content -->

<div class="main4 personal-content">
    <h1>Personalised Health Advice</h1>
    <p>Tailored tips based on your location and health tracker results.</p>


    <div class="advice-row">

    <div class="advice-card bmi-card">
        <h3>BMI + Weather</h3>
        <p><?= $bmi_advice ?></p>
        <div class="advice-icon"><?= $bmi_icon ?></div>
    </div>


    <div class="advice-card hydration-card">
        <h3 style="color: black;">Hydration + Weather</h3>
        <p><?= $hydration_advice ?></p>
        <div class="advice-icon"><?= $hydration_icon ?></div>
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
