<?php
include 'env_loader.php';
loadEnv(__DIR__ . '/.env');

$apiKey = getenv('OPENWEATHER_KEY');
?>

<!DOCTYPE html>

<head>
    <meta charset = "UTF-8">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel = "stylesheet" href = "health.css">
    <title> Air Quality Dashboard </title>
    <link rel = "icon" type = "x-icon" href = "images/Health logo.png">
    <script src="healththeme.js"></script>
</head>



<!-- Navigation Bar -->
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

    

    <!-- Weather Forecast content -->
    <div class="main4 aqi_content">
        <h1>Air Quality Dashboard</h1>
        <p> Check local air pollution levels and learn how to protect your health.</p>
        <br>


        <form method="GET" class="city-search">
            <input type="text" name="city" placeholder="Enter a city..." required>
            <button type="submit">Search</button>
        </form>
        <br>

<?php
$apiKey = getenv('OPENWEATHER_KEY');


// Only run air quality code AFTER  acity is searched
if (isset($_GET['city']) && $_GET['city'] !== "") {

    $city = $_GET['city'];

    // Convert city to coordinates using OpenWeather geocoding API
    $geo_url = "http://api.openweathermap.org/geo/1.0/direct?q=$city&limit=1&appid=$apiKey";
    $geo_response = file_get_contents($geo_url);
    $geo_data = json_decode($geo_response, true);



    if (empty($geo_data)) {
        echo "<p>City not found. Please try again.</p>";


} else {

    // Extract coordinates
    $lat = $geo_data[0]['lat'];
    $lon = $geo_data[0]['lon'];



    // Fetch air quality data
    $aq_url = "http://api.openweathermap.org/data/2.5/air_pollution?lat=$lat&lon=$lon&appid=$apiKey";
    $aq_response = file_get_contents($aq_url);
    $aq_data = json_decode($aq_response, true);


    
    if (!isset($aq_data['list'])) {
        echo "<p>Unable to load air quality data.</p>";

    } else {

        // Extract AQI value
        $aqi = $aq_data['list'][0]['main']['aqi'];

        // Determine class + advice BEFORE echoing container
        switch ($aqi) {
            case 1:
                $aqiClass = "aqi-good";
                $advice = "Good: Air quality is satisfactory, and air pollution poses little or no risk.";
                break;

            case 2:
                $aqiClass = "aqi-fair";
                $advice = "Fair: Air quality is acceptable; however, some pollutants may pose a moderate health concern for a very small number of people.";
                break;

            case 3:
                $aqiClass = "aqi-moderate";
                $advice = "Moderate: Members of sensitive groups may experience health effects. The general public is not likely to be affected.";
                break;

            case 4:
                $aqiClass = "aqi-poor";
                $advice = "Poor: Everyone may begin to experience health effects; members of sensitive groups may experience more serious health effects.";
                break;

            case 5:
                $aqiClass = "aqi-very-poor";
                $advice = "Very Poor: Health warnings of emergency conditions. The entire population is more likely to be affected.";
                break;

            default:
                $aqiClass = "aqi-unknown";
                $advice = "Unable to provide health advice.";
                break;
        }

        // NOW echo the container
        echo "<div class='aqi-container $aqiClass'>";

        echo "<h2>Current AQI in $city (Air Quality Index)</h2>";
        echo "<p><strong>AQI: $aqi</strong></p>";
        
        echo "<h4>Health Advice:</h4>";
        echo "<p>$advice</p>";

        echo "</div>";
    }
}
}

?>
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
