<?php
session_start();
include 'includes/db.php'; // Assumes $conn is your MySQLi connection

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_SESSION['booking'])) {
    die("No booking data found.");
}

$user_id = $_SESSION['user_id'];
$booking = $_SESSION['booking'];

$flight_id = $booking['flight_id'];
$return_flight_id = isset($booking['return_flight_id']) ? $booking['return_flight_id'] : null;
$class_type = $booking['class_type'];
$total_amount = $booking['total_amount'];
$passengers = $booking['passengers'];

$error_message = ""; // Initialize an error message variable

// ✅ Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $card_name = $_POST['card_name'] ?? '';
    $card_number = $_POST['card_number'] ?? '';
    $expiry = $_POST['expiry'] ?? '';
    $cvv = $_POST['cvv'] ?? '';

    // Basic validation
    if (empty($card_name) || empty($card_number) || empty($expiry) || empty($cvv)) {
        $error_message = "All fields are required ."; // Set the error message
    } else {
        // Simulate payment success
        $payment_success = true;

        if ($payment_success) {
            // 1. Insert booking
            $stmt = $conn->prepare("INSERT INTO booking (user_id, flight_id, return_flight_id, class_type, total_amount, payment_status) VALUES (?, ?, ?, ?, ?, 'Paid')");
            $stmt->bind_param('iiisd', $user_id, $flight_id, $return_flight_id, $class_type, $total_amount);
            $booking_inserted = $stmt->execute();
            $booking_id = $stmt->insert_id;
            $stmt->close();

            // 2. Insert passengers
            $passenger_inserted = true;
            foreach ($passengers as $p) {
                $stmt = $conn->prepare("INSERT INTO passenger (booking_id, name, gender, age, seat_no) VALUES (?, ?, ?, ?, ?)");
                $stmt->bind_param('issis', $booking_id, $p['name'], $p['gender'], $p['age'], $p['seat_no']);
                if (!$stmt->execute()) {
                    $passenger_inserted = false;
                }
                $stmt->close();
            }

            // 3. Insert dummy payment
            $payment_mode = "Dummy Razorpay";
            $transaction_id = "TXN" . time();
            $stmt = $conn->prepare("INSERT INTO payment (booking_id, payment_mode, transaction_id, amount) VALUES (?, ?, ?, ?)");
            $stmt->bind_param('issd', $booking_id, $payment_mode, $transaction_id, $total_amount);
            $payment_inserted = $stmt->execute();
            $stmt->close();

            // 4. Update seat counts
            function update_seat_count($conn, $flight_id, $class_type, $passenger_count) {
                $column = '';
                switch ($class_type) {
                    case 'economy': $column = 'economy_seats'; break;
                    case 'business': $column = 'business_seats'; break;
                    case 'first': $column = 'first_class_seats'; break;
                }

                $sql = "UPDATE flight SET $column = $column - ? WHERE id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param('ii', $passenger_count, $flight_id);
                $stmt->execute();
                $stmt->close();
            }

            $passenger_count = count($passengers);
            update_seat_count($conn, $flight_id, $class_type, $passenger_count);
            if (!empty($return_flight_id)) {
                update_seat_count($conn, $return_flight_id, $class_type, $passenger_count);
            }

            // ✅ Final check
            if ($booking_inserted && $passenger_inserted && $payment_inserted) {
                unset($_SESSION['booking']);
                echo "<script>
                    alert('✅ Payment Successfully...');
                    window.location.href = 'success_booking.php';
                </script>";
                exit();
            } else {
                $error_message = "❌ Payment failed. Please try again.";
            }
        } else {
            $error_message = "❌ Payment failed. Please try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dummy Payment</title>
    <style>
        body { font-family: Arial; padding: 20px; }
        form { max-width: 400px; margin: auto; background: #f9f9f9; padding: 20px; border-radius: 8px; }
        input { width: 100%; padding: 10px; margin-bottom: 15px; }
        button { padding: 10px 20px; background: #28a745; color: white; border: none; cursor: pointer; }
        button:hover { background: #218838; }
        h2 { text-align: center; }
        .error-message { color: red; margin-bottom: 10px; } /* Style for error message */
    </style>
</head>
<body>

<h2>Dummy Payment</h2>

<?php if (!empty($error_message)): ?>
    <p class="error-message"><?php echo $error_message; ?></p>
<?php endif; ?>

<form method="POST">
    <input type="text" name="card_name" placeholder="Cardholder Name" required />
    <input type="text" name="card_number" placeholder="Card Number" maxlength="16" required />
    <input type="text" name="expiry" placeholder="Expiry (MM/YY)" required />
    <input type="text" name="cvv" placeholder="CVV" maxlength="4" required />
    <button type="submit">Pay Now ₹<?php echo number_format($total_amount, 2); ?></button>
</form>

</body>
</html>