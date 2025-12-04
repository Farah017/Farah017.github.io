<?php
include 'ZooConnect.php';

// Daily ticket limit
$dailyLimit = 100;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Get form data
    $name = $_POST['name'];
    $email = $_POST['email'];
    $date = $_POST['date'];
    $tickets = $_POST['tickets'] ?? [];


    // Ticket prices
    $prices = [
        'Family' => 50.00,
        'Adult' => 20.00,
        'Student' => 15.00,
        'Child' => 10.00
    ];


    // Loyalty points per ticket
    $pointsPerTicket = [
        'Family' => 10,
        'Adult' => 5,
        'Student' => 3,
        'Child' => 2
    ];


    // Calculate totals
    $totalTickets = 0;
    $totalPrice = 0;
    $totalPoints = 0;

    foreach ($tickets as $type => $qty) {
        $qty = (int)$qty;
        if ($qty > 0) {
            $totalTickets += $qty;
            $totalPrice += $prices[$type] * $qty;
            $totalPoints += $pointsPerTicket[$type] * $qty;
        }
    }



    // Check current bookings for selected date
    $stmt = $conn->prepare("SELECT SUM(quantity) FROM booktickets WHERE date = ?");
    $stmt->bind_param("s", $date);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();

    if ($count + $totalTickets > $dailyLimit) {
        echo "<div class='message'>Sorry, not enough tickets available for this date.</div>";
        exit;
    }


    // Insert each ticket type separately
    foreach ($tickets as $type => $qty) {
        $qty = (int)$qty;
        if ($qty > 0) {
            $priceForType = $prices[$type] * $qty;

            $stmt = $conn->prepare("INSERT INTO booktickets (email, name, date, tickettype, price, quantity) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssdi", $email, $name, $date, $type, $priceForType, $qty);
            $stmt->execute();
            $stmt->close();
        }
    }


    // Update loyalty points
    $stmt = $conn->prepare("INSERT INTO loyaltypoints (email, points) VALUES (?, ?) ON DUPLICATE KEY UPDATE points = points + VALUES(points)");
    $stmt->bind_param("si", $email, $totalPoints);
    $stmt->execute();
    $stmt->close();

    echo "<div class='message'>Booking successful! You booked $totalTickets tickets, paid £$totalPrice, and earned $totalPoints loyalty points.</div>";
}
?>


<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>RZA Dashboard</title>
    <link rel="stylesheet" href="zoo.css">
    <link rel="icon" type="x-icon" href="zoofav.png">
    <script src="theme.js"></script>
</head>


<body>

<button type="button" onclick="changeTheme()">Change the Theme</button>

<div class="Ticket-Booking"> 
    <h1>Book your Tickets</h1>
    <form action="BookTickets.php" method="POST">

        <label for="name">Full Name:</label>
        <input type="text" id="name" name="name" required>

        <label for="email">Email:</label>
        <input type="text" id="email" name="email" required>

        <label for="date">Date:</label>
        <input type="date" id="date" name="date" required>

        <br><br>
        

        <!-- Ticket quantity inputs -->
        <label for="family">Family Tickets (£50 each):</label>
        <input type="number" id="family" name="tickets[Family]" min="0" value="0"><br>

        <label for="adult">Adult Tickets (£20 each):</label>
        <input type="number" id="adult" name="tickets[Adult]" min="0" value="0"><br>

        <label for="student">Student Tickets (£15 each):</label>
        <input type="number" id="student" name="tickets[Student]" min="0" value="0"><br>

        <label for="child">Child Tickets (£10 each):</label>
        <input type="number" id="child" name="tickets[Child]" min="0" value="0"><br>

        <br><br>
        <button type="submit">Submit</button>
    </form>
</div>

<a href="ZooDash.php" class="signup-button">Go back to dashboard</a>

</body>
</html>
