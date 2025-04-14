<?php
session_start();
require_once 'includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $flight_id = intval($_POST['flight_id'] ?? 0);
    $num_passengers = intval($_POST['num_passengers'] ?? 0);
    $class_type = $_POST['class_type'] ?? '';
    $passengers = $_POST['passengers'] ?? [];

    if ($flight_id <= 0 || $num_passengers <= 0 || empty($class_type) || count($passengers) !== $num_passengers) {
        die("Invalid booking data.");
    }

    // Fetch flight details
    $stmt = $conn->prepare("SELECT * FROM flight WHERE id = ?");
    $stmt->bind_param('i', $flight_id);
    $stmt->execute();
    $flightResult = $stmt->get_result();

    if ($flightResult->num_rows === 0) {
        die("Flight not found.");
    }

    $flight = $flightResult->fetch_assoc();

    // Determine seat price
    switch ($class_type) {
        case 'economy':
            $seat_price = $flight['economy_price'];
            break;
        case 'business':
            $seat_price = $flight['business_price'];
            break;
        case 'first':
            $seat_price = $flight['first_class_price'];
            break;
        default:
            die("Invalid seat class selected.");
    }

    $total_amount = $seat_price * $num_passengers;

    // Save details in session for payment processing
    $_SESSION['booking'] = [
        'flight_id' => $flight_id,
        'class_type' => $class_type,
        'num_passengers' => $num_passengers,
        'total_amount' => $total_amount,
        'passengers' => $passengers,
    ];
} else {
    header("Location: flight_search.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Confirm Booking</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <h2 class="mb-4">Review and Confirm Your Booking</h2>

    <h4>Flight Information</h4>
    <table class="table table-bordered">
        <tr>
            <th>From</th>
            <td><?= htmlspecialchars($flight['from_location']) ?></td>
            <th>To</th>
            <td><?= htmlspecialchars($flight['to_location']) ?></td>
        </tr>
        <tr>
            <th>Departure</th>
            <td><?= date("d M Y, H:i", strtotime($flight['departure'])) ?></td>
            <th>Arrival</th>
            <td><?= date("d M Y, H:i", strtotime($flight['arrival'])) ?></td>
        </tr>
        <tr>
            <th>Seat Class</th>
            <td colspan="3"><?= ucfirst($class_type) ?></td>
        </tr>
    </table>

    <h4>Passenger Details</h4>
    <table class="table table-striped">
        <thead>
        <tr>
            <th>#</th>
            <th>Name</th>
            <th>Gender</th>
            <th>Age</th>
            <th>Seat</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($passengers as $i => $p): ?>
            <tr>
                <td><?= $i ?></td>
                <td><?= htmlspecialchars($p['name']) ?></td>
                <td><?= htmlspecialchars($p['gender']) ?></td>
                <td><?= intval($p['age']) ?></td>
                <td><?= htmlspecialchars($p['seat_no']) ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <h5>Total Amount: ₹<?= number_format($total_amount) ?></h5>

    <form method="POST" action="payment.php">
        <button type="submit" class="btn btn-success">Proceed to Payment</button>
        <a href="passenger_details.php?flight_id=<?= $flight_id ?>" class="btn btn-secondary">Edit Details</a>
    </form>
</div>
</body>
</html>
