<?php
include 'ZooConnect.php';

// Daily room limit
$dailyLimit = 50;

$message = '';
$availability = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $checkin = $_POST['checkin'] ?? null;
    $checkout = $_POST['checkout'] ?? null;
    $roomtype = $_POST['roomtype'] ?? null;

    // Basic validation
    if (!$checkin || !$checkout || !$roomtype) {
        $message = "Please select check-in, check-out, and a room type.";
    } else {
        $checkinDate = new DateTime($checkin);
        $checkoutDate = new DateTime($checkout);

        // Ensure checkout is after checkin
        if ($checkoutDate <= $checkinDate) {
            $message = "Check-out must be after check-in.";
        } else {
            // Format for display
            $checkinFormatted = $checkinDate->format('d/m/y');
            $checkoutFormatted = $checkoutDate->format('d/m/y');

            // DatePeriod is end-exclusive; this correctly covers nights from check-in to the night before checkout
            $period = new DatePeriod($checkinDate, new DateInterval('P1D'), $checkoutDate);

            $available = true;

            foreach ($period as $date) {
                $day = $date->format('Y-m-d');

                // Count overlapping bookings for this day for the chosen room type
                $stmt = $conn->prepare("
                    SELECT COUNT(*) 
                    FROM bookstay 
                    WHERE roomtype = ? 
                      AND checkin <= ? 
                      AND checkout > ?
                ");
                $stmt->bind_param("sss", $roomtype, $day, $day);
                $stmt->execute();
                $stmt->bind_result($count);
                $stmt->fetch();
                $stmt->close();

                $remaining = $dailyLimit - (int)$count;
                $availability[$day] = max(0, $remaining);

                if ($remaining <= 0) {
                    $available = false;
                }
            }

            if (count($availability) === 0) {
                // This happens if DatePeriod produced no days (shouldn’t if checkout > checkin)
                $message = "No nights selected. Please choose a check-out date after check-in.";
            } else {
                $minRemaining = min($availability);
                if (!$available) {
                    $message = "Sorry, one or more dates in your stay are fully booked.";
                } else {
                    // Include the minimum rooms left in the message
                    $message = "Good news! $roomtype rooms are available between $checkinFormatted and $checkoutFormatted. At least $minRemaining rooms left per night.";
                }
            }
        }
    }
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Check Availability</title>
    <link rel="stylesheet" href="zoo.css">
    <link rel = "icon" type = "x-icon" href = "zoofav.png">
    <script src="theme.js"></script>
</head>


<body>
    

    <div class="dash-navbar">
        <div class="dash-nav-left"><img src="zoofav.png" class="nav-logo" alt="Logo"></div>
        <ul class="dash-nav">
            <li><a href="ZooDash.php">Dashboard</a></li>
        </ul>
    </div>



    <div class="dash-hero-section" style="margin-top:100px;">
        
    <div class="booking-summary">
            <h1 style="color:white">Check Hotel Availability</h1>
            <form method="POST" action="">
                <label for="checkin">Check-in Date:</label>
                <input type="date" id="checkin" name="checkin" required>

                <label for="checkout">Check-out Date:</label>
                <input type="date" id="checkout" name="checkout" required>

                <?php
                $rooms = ['Suite', 'Deluxe', 'Standard'];
                foreach ($rooms as $room) {
                    echo "<label><input type='radio' name='roomtype' value='$room' required> $room</label><br>";
                }
                ?>
                <br><br>

                <button type="submit">Check Availability</button>
            </form>
        </div>
    </div>


<button type="button" onclick="changeTheme()">Change the Theme</button>
  <p class="note">The site is GDPR Compliant</p>



    <?php if (!empty($message)): ?>
        <div class="message" style="margin:20px 0; font-weight:bold;"><?php echo htmlspecialchars($message); ?></div>
        <?php if ($_SERVER["REQUEST_METHOD"] == "POST"): ?>
            <h3>Rooms left per day:</h3>
            <ul>
                <?php
                if (!empty($availability)) {
                    foreach ($availability as $day => $remaining) {
                        $formatted = (new DateTime($day))->format('d/m/y');
                        if ($remaining <= 0) {
                            echo "<li><span style='color:red;'>$formatted: Fully booked</span></li>";
                        } else {
                            echo "<li>$formatted: $remaining rooms left</li>";
                        }
                    }
                } else {
                    echo "<li>No nights to display. Please adjust your dates.</li>";
                }
                ?>
            </ul>
        <?php endif; ?>
    <?php endif; ?>
</body>
</html>
