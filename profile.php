<?php
session_start();
require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: auth.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user bookings with car details
$query = "SELECT bookings.*, cars.model, cars.description, cars.price_per_day, cars.image 
          FROM bookings 
          JOIN cars ON bookings.car_id = cars.id 
          WHERE bookings.user_id = :user_id";
$stmt = $pdo->prepare($query);
$stmt->execute(['user_id' => $user_id]);
$bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get the car_id from URL parameter if available
$selected_car_id = isset($_GET['car_id']) ? intval($_GET['car_id']) : null;

// Handle form submission for new bookings
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['book_now'])) {
    $car_id = $_POST['car_id'];
    $rental_date = $_POST['rental_date'];
    $return_date = $_POST['return_date'];
    $name = $_POST['name'];
    $phone_number = $_POST['phone_number'];
    $address = $_POST['address'];

    // Insert new booking into the bookings table
    $insertQuery = "INSERT INTO bookings (user_id, car_id, rental_date, return_date, name, phone_number, address) 
                    VALUES (:user_id, :car_id, :rental_date, :return_date, :name, :phone_number, :address)";
    $insertStmt = $pdo->prepare($insertQuery);
    $insertStmt->execute([
        'user_id' => $user_id,
        'car_id' => $car_id,
        'rental_date' => $rental_date,
        'return_date' => $return_date,
        'name' => $name,
        'phone_number' => $phone_number,
        'address' => $address,
    ]);

    // Fetch the newly created booking ID
    $booking_id = $pdo->lastInsertId();

    // Redirect to payment page with booking details
    header("Location: payment.php?booking_id=" . urlencode($booking_id));
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile - Add to Cart</title>
    <link rel="stylesheet" href="profile.css">
</head>
<body>
    <div class="container">
        <div class="left-container">
            <img src="images/P.jpg" alt="Car Image" class="car-image">
        </div>
        <div class="right-container">
            <h1>Book Your Car</h1>
            <form action="profile.php" method="post">
                <label for="car">Select Car:</label>
                <select name="car_id" id="car" required>
                    <?php
                    $carQuery = "SELECT id, model, description, price_per_day FROM cars";
                    $carStmt = $pdo->query($carQuery);
                    while ($car = $carStmt->fetch(PDO::FETCH_ASSOC)) {
                        $selected = ($car['id'] == $selected_car_id) ? 'selected' : '';
                        echo '<option value="' . $car['id'] . '" ' . $selected . '>' . htmlspecialchars($car['model'] . ' - ' . $car['description'] . ' (₹' . $car['price_per_day'] . ' per day)') . '</option>';
                    }
                    ?>
                </select>

                <label for="rental_date">Rental Date:</label>
                <input type="date" name="rental_date" id="rental_date" required>

                <label for="return_date">Return Date:</label>
                <input type="date" name="return_date" id="return_date" required>

                <label for="name">Your Name:</label>
                <input type="text" name="name" id="name" required>

                <label for="phone_number">Phone Number:</label>
                <input type="text" name="phone_number" id="phone_number" required>

                <label for="address">Address:</label>
                <textarea name="address" id="address" required></textarea>

                <button type="submit" name="book_now">Buy Now</button>
                <button type="button" onclick="window.location.href='index.php'">Back</button>
            </form>
        </div>
    </div>
</body>
</html>
