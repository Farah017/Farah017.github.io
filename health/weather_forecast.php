<!DOCTYPE html>

<head>
    <meta charset = "UTF-8">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel = "stylesheet" href = "health.css">
    <title> Weather Forecast </title>
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
    <div class="main4 weather_content">
        <h1>Weather Forecast</h1>
        <p> Check the forecast to prepare yourself.</p>
        <br>


        <form method="GET" class="city-search">
            <input type="text" name="city" placeholder="Enter a city..." required> <!-- City search input -->
            <button type="submit">Search</button>
        </form>
    <br>




    <?php
    $apiKey = "2a1b96da878015fa6ccc0dc3e24302fd";

    // Only run weather code AFTER a city is searched
    if (isset($_GET['city']) && $_GET['city'] !== "") {

        $city = $_GET['city'];

        // Convert city → coordinates using OpenWeather geocoding API
        $geo_url = "http://api.openweathermap.org/geo/1.0/direct?q=$city&limit=1&appid=$apiKey";
        $geo_response = file_get_contents($geo_url);
        $geo_data = json_decode($geo_response, true);

        // If city not found
        if (empty($geo_data)) {
            echo "<p>City not found. Please try again.</p>";

        } else {
            // Extract coordinates
            $lat = $geo_data[0]['lat'];
            $lon = $geo_data[0]['lon'];



            // Fetch 5‑day forecast (HTTP so XAMPP doesn't complain)
            $forecast_url = "http://api.openweathermap.org/data/2.5/forecast?lat=$lat&lon=$lon&appid=$apiKey&units=metric";

            $forecast_response = file_get_contents($forecast_url);
            $forecast_data = json_decode($forecast_response, true);



            if (!isset($forecast_data['list'])) { # if forecast data does not exist
                echo "<p>Unable to load forecast data.</p>";
           

            } else {
                // Extract one forecast per day (12:00pm)
                $five_day = [];
                foreach ($forecast_data['list'] as $entry) { # loop through each 3-hour forecast entry
                    
                    if (strpos($entry['dt_txt'], "12:00:00") !== false) { # if time is 12:00:00

                        $date = date("d/m/Y", strtotime($entry['dt_txt'])); 
                        $min = round($entry['main']['temp_min']);
                        $max = round($entry['main']['temp_max']);
                        $desc = ucfirst($entry['weather'][0]['description']);
                        $icon = $entry['weather'][0]['icon'];



                        // Top tip logic
                        if ($max <= 5) { # if max temp less than or equal to 5°C
                            $tip = "Layer clothing and keep indoor spaces warm.";

                        } elseif ($max >= 25) { # if max temp greater than or equal to 25°C
                            $tip = "Stay hydrated and avoid direct sun exposure.";

                        } elseif (str_contains(strtolower($desc), "rain")) {
                            $tip = "Carry an umbrella and wear waterproof shoes.";

                        } else {
                            $tip = "Dress comfortably and check for changing conditions.";
                        }



                        $five_day[] = [ # store day data in array
                            'date' => $date, 
                            'min' => $min,
                            'max' => $max,
                            'desc' => $desc,
                            'icon' => $icon,
                            'tip' => $tip
                        ];

                        if (count($five_day) == 5) break; # stop after 5 days
                    }
                }

            }

            // Display 5‑day forecast
            echo "<h2> 5‑Day Forecast for $city </h2>";
            
            echo "<div class='forecast-container'>";
            foreach ($five_day as $day) {
                echo "<div class='forecast-day'>";

                echo "<p><strong>{$day['date']}</strong></p>";

                echo "<img src='http://openweathermap.org/img/wn/{$day['icon']}@2x.png'>";

                echo "<p>Temperature: {$day['min']}–{$day['max']}°C</p>";
                echo "<br>";
                echo "<p>Condition: {$day['desc']}</p>";

                echo "<br><br>";
                echo "<p><strong>Top Tip:</strong> {$day['tip']}</p>";

                echo "</div>";
            }

            echo "</div>";
        }
    }

 else {
    echo "<p> Please enter a city above to view the forecast. </p>";
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






   