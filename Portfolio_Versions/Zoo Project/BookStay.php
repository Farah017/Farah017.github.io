<?php
include 'ZooConnect.php';

// Daily room limit (example: 50 rooms per day)
$dailyLimit = 50;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $checkin = $_POST['checkin'];
    $checkout = $_POST['checkout'];
    $roomSelections = $_POST['rooms'] ?? [];

    // Room prices per night
    $rooms = [
        'Suite' => 150.00,
        'Deluxe' => 100.00,
        'Standard' => 70.00
    ];

    // Loyalty points per room per night
    $pointsPerRoom = [
        'Suite' => 20,
        'Deluxe' => 15,
        'Standard' => 10
    ];

    // Calculate nights
    $checkinDate = new DateTime($checkin);
    $checkoutDate = new DateTime($checkout);
    $nights = $checkinDate->diff($checkoutDate)->days;

    $totalRooms = 0;
    $totalPrice = 0;
    $totalPoints = 0;

    // Loop through each room type
    foreach ($roomSelections as $type => $qty) {
        $qty = (int)$qty;
        if ($qty > 0) {
            $totalRooms += $qty;
            $subtotal = $rooms[$type] * $qty * $nights;
            $totalPrice += $subtotal;
            $totalPoints += $pointsPerRoom[$type] * $qty * $nights;

            // Insert booking row
            $stmt = $conn->prepare("INSERT INTO bookstay (email, name, checkin, checkout, roomtype, price, quantity) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sssssdi", $email, $name, $checkin, $checkout, $type, $subtotal, $qty);
            $stmt->execute();
            $stmt->close();
        }
    }

    // Check daily limit (simplified: total rooms booked for check-in date)
    $stmt = $conn->prepare("SELECT SUM(quantity) FROM bookstay WHERE checkin = ?");
    $stmt->bind_param("s", $checkin);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();

    if ($count + $totalRooms > $dailyLimit) {
        echo "<div class='message'>Sorry, not enough rooms available for your check-in date.</div>";
        exit;
    }

    // Update loyalty points
    $stmt = $conn->prepare("INSERT INTO loyaltypoints (email, points) VALUES (?, ?) 
                            ON DUPLICATE KEY UPDATE points = points + VALUES(points)");
    $stmt->bind_param("si", $email, $totalPoints);
    $stmt->execute();
    $stmt->close();

    echo "<div class='message'>Booking successful! You booked $totalRooms room(s) for $nights night(s), paid £$totalPrice, and earned $totalPoints loyalty points.</div>";
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>RZA Booking</title>
    <link rel="stylesheet" href="zoo.css">
    <link rel="icon" type="x-icon" href="zoofav.png">
    <script src="theme.js"></script>
</head>
<body>

<button type="button" onclick="changeTheme()">Change the Theme</button>

<div class="Stay-Booking">
    <h1>Book your Hotel Stay</h1>
    <form action="BookStay.php" method="POST">

        <label for="name">Full Name:</label>
        <input type="text" id="name" name="name" required>

        <label for="email">Email:</label>
        <input type="text" id="email" name="email" required>

        <label for="checkin">Check-In Date:</label>
        <input type="date" id="checkin" name="checkin" required>

        <label for="checkout">Check-Out Date:</label>
        <input type="date" id="checkout" name="checkout" required>

        <br><br>

        <!-- Room quantity inputs -->
        <label for="suite">Suite (£150 per night):</label>
        <input type="number" id="suite" name="rooms[Suite]" min="0" value="0"><br>

        <label for="deluxe">Deluxe (£100 per night):</label>
        <input type="number" id="deluxe" name="rooms[Deluxe]" min="0" value="0"><br>

        <label for="standard">Standard (£70 per night):</label>
        <input type="number" id="standard" name="rooms[Standard]" min="0" value="0"><br>

        <br><br>
        <button type="submit">Submit</button>
    </form>
    
    <a href="ZooDash.php" class="signup-button">Go back to dashboard</a>
</div>
</body>
</html>
