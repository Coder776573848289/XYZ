<?php
session_start();
include 'includes/db.php'; // assumes $conn is your MySQLi connection

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

// Dummy Razorpay success simulation
$payment_success = true;

if ($payment_success) {
    // 1. Insert booking
    $stmt = $conn->prepare("INSERT INTO booking (user_id, flight_id, return_flight_id, class_type, total_amount, payment_status) VALUES (?, ?, ?, ?, ?, 'Paid')");
    $stmt->bind_param('iiisd', $user_id, $flight_id, $return_flight_id, $class_type, $total_amount);
    $stmt->execute();
    $booking_id = $stmt->insert_id;
    $stmt->close();

    // 2. Insert passengers
    foreach ($passengers as $p) {
        $stmt = $conn->prepare("INSERT INTO passenger (booking_id, name, gender, age, seat_no) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param('issis', $booking_id, $p['name'], $p['gender'], $p['age'], $p['seat_no']);
        $stmt->execute();
        $stmt->close();
    }

    // 3. Insert dummy payment
    $payment_mode = "Dummy Razorpay";
    $transaction_id = "TXN" . time();
    $stmt = $conn->prepare("INSERT INTO payment (booking_id, payment_mode, transaction_id, amount) VALUES (?, ?, ?, ?)");
    $stmt->bind_param('issd', $booking_id, $payment_mode, $transaction_id, $total_amount);
    $stmt->execute();
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

    // Cleanup and redirect
    unset($_SESSION['booking']);
    header("Location: success_booking.php");
    exit();
} else {
    echo "Payment failed. Please try again.";
}
?>
