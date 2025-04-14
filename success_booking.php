<?php
session_start();
include 'includes/db.php';

$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) {
    header("Location: login.php");
    exit;
}

// Get latest booking by this user
$sql = "SELECT * FROM booking WHERE user_id = ? ORDER BY booking_time DESC LIMIT 1";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();
$booking = $result->fetch_assoc();
$stmt->close();

if (!$booking) {
    echo "No booking found.";
    exit;
}

$booking_id = $booking['id'];
$flight_id = $booking['flight_id'];
$return_flight_id = $booking['return_flight_id'];
$class_type = ucfirst($booking['class_type']);
$total_amount = $booking['total_amount'];
$payment_status = $booking['payment_status'];

// Get passengers
$passenger_sql = "SELECT * FROM passenger WHERE booking_id = ?";
$stmt = $conn->prepare($passenger_sql);
$stmt->bind_param('i', $booking_id);
$stmt->execute();
$passenger_result = $stmt->get_result();
$passengers = $passenger_result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// Get flight details
function getFlightDetails($conn, $flight_id) {
    $stmt = $conn->prepare("SELECT * FROM flight WHERE id = ?");
    $stmt->bind_param('i', $flight_id);
    $stmt->execute();
    $res = $stmt->get_result();
    $flight = $res->fetch_assoc();
    $stmt->close();
    return $flight;
}

$flight = getFlightDetails($conn, $flight_id);
$return_flight = $return_flight_id ? getFlightDetails($conn, $return_flight_id) : null;

?>

<!DOCTYPE html>
<html>
<head>
    <title>Booking Confirmation</title>
    <style>
        body { font-family: Arial; background: #f0f0f0; padding: 20px; }
        .ticket { background: white; padding: 20px; border-radius: 10px; max-width: 800px; margin: auto; }
        h2 { color: #333; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { padding: 10px; border: 1px solid #ccc; text-align: left; }
        .print-btn, .dash-btn {
            margin-top: 20px; padding: 10px 20px;
            background: #007BFF; color: white; border: none;
            border-radius: 5px; cursor: pointer;
        }
        .print-btn:hover, .dash-btn:hover { background: #0056b3; }
    </style>
</head>
<body>

<div class="ticket" id="ticket">
    <h2>✅ Booking Confirmed!</h2>
    <p><strong>Booking ID:</strong> <?= $booking_id ?> | <strong>Class:</strong> <?= $class_type ?> | <strong>Payment:</strong> <?= $payment_status ?></p>
    <p><strong>Total Paid:</strong> ₹<?= $total_amount ?></p>

    <?php if ($flight): ?>
    <h3>🛫 Flight Details</h3>
    <p><strong>From:</strong> <?= htmlspecialchars($flight['from_location'] ?? 'N/A') ?> |
       <strong>To:</strong> <?= htmlspecialchars($flight['to_location'] ?? 'N/A') ?></p>
    <p><strong>Departure:</strong> <?= $flight['departure'] ?? 'N/A' ?> |
       <strong>Arrival:</strong> <?= $flight['arrival'] ?? 'N/A' ?></p>
    <?php else: ?>
        <p>Flight details not found.</p>
    <?php endif; ?>

    <?php if ($return_flight): ?>
    <h3>🔁 Return Flight</h3>
    <p><strong>From:</strong> <?= htmlspecialchars($return_flight['from_location'] ?? 'N/A') ?> |
       <strong>To:</strong> <?= htmlspecialchars($return_flight['to_location'] ?? 'N/A') ?></p>
    <p><strong>Departure:</strong> <?= $return_flight['departure'] ?? 'N/A' ?> |
       <strong>Arrival:</strong> <?= $return_flight['arrival'] ?? 'N/A' ?></p>
    <?php endif; ?>



    <h3>👥 Passenger(s)</h3>
    <table>
        <tr>
            <th>Name</th>
            <th>Gender</th>
            <th>Age</th>
            <th>Seat No</th>
        </tr>
        <?php foreach ($passengers as $p): ?>
        <tr>
            <td><?= htmlspecialchars($p['name']) ?></td>
            <td><?= $p['gender'] ?></td>
            <td><?= $p['age'] ?></td>
            <td><?= $p['seat_no'] ?></td>
        </tr>
        <?php endforeach; ?>
    </table>

    <button class="print-btn" onclick="window.print()">🖨️ Print / Download Ticket</button>
    <a href="generate_ticket_pdf.php"><button class="print-btn">⬇️ Download Ticket (PDF)</button></a>
    <a href="user_dashboard.php"><button class="dash-btn">🔙 Go to Dashboard</button></a>
</div>

</body>
</html>
