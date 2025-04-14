<?php
// ✅ Load Dompdf
require 'dompdf/autoload.inc.php';
use Dompdf\Dompdf;

session_start();
include 'includes/db.php';

// ✅ Check login
$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) {
    header("Location: login.php");
    exit;
}

// ✅ Fetch latest booking
$sql = "SELECT b.*, f.airline_name, f.from_location, f.to_location, f.departure, f.arrival 
        FROM booking b 
        JOIN flight f ON b.flight_id = f.id 
        WHERE b.user_id = ? 
        ORDER BY b.booking_time DESC 
        LIMIT 1";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "No booking found.";
    exit;
}

$booking = $result->fetch_assoc();

// ✅ HTML template for the ticket
$ticket_html = '
<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        h2 { color: #333; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background-color: #f2f2f2; }
        .footer { margin-top: 30px; font-size: 14px; color: #555; }
    </style>
</head>
<body>
    <h2>Flight Ticket - Booking Confirmation</h2>
    <p><strong>Booking ID:</strong> ' . $booking['id'] . '</p>
    <p><strong>Class:</strong> ' . ucfirst($booking['class_type']) . '</p>
    <p><strong>Total Amount ₹:</strong> ' . number_format($booking['total_amount'], 2) . '</p>
    
    <table>
        <tr><th>Airline</th><td>' . $booking['airline_name'] . '</td></tr>
        <tr><th>From</th><td>' . $booking['from_location'] . '</td></tr>
        <tr><th>To</th><td>' . $booking['to_location'] . '</td></tr>
        <tr><th>Departure</th><td>' . date("d M Y, h:i A", strtotime($booking['departure'])) . '</td></tr>
        <tr><th>Arrival</th><td>' . date("d M Y, h:i A", strtotime($booking['arrival'])) . '</td></tr>
        <tr><th>Booking Time</th><td>' . date("d M Y, h:i A", strtotime($booking['booking_time'])) . '</td></tr>
    </table>

    <p class="footer">Thank you for booking with us. Have a safe flight!</p>
</body>
</html>
';

// ✅ Generate PDF
$dompdf = new Dompdf();
$dompdf->loadHtml($ticket_html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$dompdf->stream("ticket_booking_{$booking['id']}.pdf", ["Attachment" => 1]);
?>
