<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['user_id']) || !isset($_GET['booking_id'])) {
    header("Location: auth.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$booking_id = $_GET['booking_id'];

// Fetch booking details
$query = "SELECT bookings.*, cars.model, cars.description, cars.price_per_day 
          FROM bookings 
          JOIN cars ON bookings.car_id = cars.id 
          WHERE bookings.id = :booking_id AND bookings.user_id = :user_id";
$stmt = $pdo->prepare($query);
$stmt->execute(['booking_id' => $booking_id, 'user_id' => $user_id]);
$booking = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$booking) {
    echo "Invalid booking.";
    exit();
}

// Calculate the number of days for the rental
$rental_days = (strtotime($booking['return_date']) - strtotime($booking['rental_date'])) / (60 * 60 * 24);

// Calculate total price before GST
$total_price_before_gst = $booking['price_per_day'] * $rental_days;

// Calculate GST (18%)
$gst_rate = 0.18;
$gst_amount = $total_price_before_gst * $gst_rate;

// Calculate final total price
$total_price_with_gst = $total_price_before_gst + $gst_amount;

// Handle payment form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['pay_now'])) {
    // Handle payment processing here

    echo "<script>
            Swal.fire({
                title: 'Payment Successful!',
                text: 'Your payment has been processed successfully.',
                icon: 'success',
                confirmButtonText: 'OK'
            });
          </script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment</title>
    <link rel="stylesheet" href="payment.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css">
    <style>
        .text-center {
            margin-top: 140px;
            text-align: center;
        }
        .btn {
            padding: 10px 20px;
            font-size: 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .btn-success {
            background-color: #3ecc6d;
            color: #fff;
        }
        .btn-success:hover {
            background-color: #05cf48;
        }
    </style>
</head>
<body>
    <div class="container">
        <section class="booking-summary">
            <h3>Booking Summary</h3>
            <p>Car Model: <?php echo htmlspecialchars($booking['model']); ?></p>
            <p>Description: <?php echo htmlspecialchars($booking['description']); ?></p>
            <p>Price per Day: ₹<?php echo htmlspecialchars($booking['price_per_day']); ?></p>
            <p>Rental Date: <?php echo htmlspecialchars($booking['rental_date']); ?></p>
            <p>Return Date: <?php echo htmlspecialchars($booking['return_date']); ?></p>
            <p>Total Price Before GST: ₹<?php echo number_format($total_price_before_gst, 2); ?></p>
            <p>GST (18%): ₹<?php echo number_format($gst_amount, 2); ?></p>
            <p>Total Price with GST: ₹<?php echo number_format($total_price_with_gst, 2); ?></p>
        </section>

         <section class="payment-form">
            <h3>Payment Details</h3>
            <form id="payment-form">
                <div class="card-images">
                    <img src="images/visa.png" alt="Visa">
                    <img src="images/master.png" alt="MasterCard">
                    
                </div>
                <div class="card-details">
                    <label for="card_number">Card Number:</label>
                    <input type="text" name="card_number" id="card_number" required>

                    <label for="expiry_date">Expiry Date:</label>
                    <input type="text" name="expiry_date" id="expiry_date" required>

                    <label for="cvv">CVV:</label>
                    <input type="text" name="cvv" id="cvv" required>

                    <label for="card_name">Name on Card:</label>
                    <input type="text" name="card_name" id="card_name" required>
                </div>
                <div class="text-center">
                    <button type="submit" class="btn btn-success btn-lg" id="submit-btn">
                        <i class="glyphicon glyphicon-save"></i>
                        Pay Now
                    </button>
                </div>
            </form>
        </section>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#payment-form').on('submit', function(e) {
                e.preventDefault(); // Prevent the actual form submission

                swal("Payment successful!", "Your car will be delivered soon, and we'll contact you shortly", "success");

                // Optionally, you can submit the form here if needed
                // this.submit();
            });
        });
    </script>
</body>
</html>
