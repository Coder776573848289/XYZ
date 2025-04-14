<?php
session_start();
require_once '../includes/db.php';

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

date_default_timezone_set('Asia/Kolkata');
$today = date('Y-m-d');

// Updated query for bookings2
$sql = "SELECT * FROM booking WHERE DATE(booking_time) = ? ORDER BY booking_time ASC";
$stmt = $conn->prepare($sql);

if ($stmt) {
    $stmt->bind_param("s", $today);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    die("Query preparation failed: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Today's Bookings</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --color-dark: #2a4a68;
            --color-secondary: #42688d;
            --color-mid: #3a5a68;
            --color-light: #4c6b6f;
            --text-color: #ffffff;
            --bg-light: #f9f9f9;
            --color-accent: #5a8da8;
            --color-success: #4c9e8f;
            --color-error: #d45d79;
            --text-dark: #2a3a4a;
            --input-focus: rgba(90, 141, 168, 0.2);
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--bg-light);
            color: var(--text-dark);
        }

        .header-card {
            background-color: var(--color-dark);
            color: var(--text-color);
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        .header-title {
            font-weight: 600;
            margin: 0;
            padding: 15px 20px;
        }

        .table-container {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            padding: 20px;
            margin-bottom: 20px;
        }

        .table thead th {
            background-color: var(--color-dark);
            color: var(--text-color);
            vertical-align: middle;
            text-align: center;
        }

        .table tbody tr:hover {
            background-color: rgba(90, 141, 168, 0.1);
        }

        .status-paid {
            color: var(--color-success);
            font-weight: bold;
        }

        .status-pending {
            color: var(--color-error);
            font-weight: bold;
        }

        .back-link {
            color: var(--color-accent);
            text-decoration: none;
            transition: color 0.3s;
            display: inline-block;
            margin-top: 20px;
        }

        .back-link:hover {
            color: var(--color-secondary);
        }

        .error-msg {
            color: var(--color-error);
            font-weight: 600;
            text-align: center;
            padding: 20px;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            margin: 20px auto;
            max-width: 600px;
        }

        .no-bookings {
            text-align: center;
            padding: 30px;
            color: var(--color-light);
        }

        .no-bookings i {
            font-size: 3rem;
            margin-bottom: 15px;
            color: var(--color-light);
        }

        .badge-class {
            background-color: var(--color-secondary);
            color: white;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.8rem;
            text-transform: capitalize;
        }

        .refresh-btn {
            background-color: var(--color-accent);
            color: white;
            border: none;
            border-radius: 5px;
            padding: 5px 10px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .refresh-btn:hover {
            background-color: var(--color-secondary);
        }

        @media (max-width: 768px) {
            .table-responsive {
                overflow-x: auto;
            }

            .table th,
            .table td {
                padding: 8px;
                font-size: 14px;
            }

            .header-title {
                font-size: 1.2rem;
                text-align: center;
            }
        }
    </style>
</head>

<body>
    <div class="container py-4">
        <!-- Header Card -->
        <div class="header-card">
            <div class="d-flex justify-content-between align-items-center">
                <h2 class="header-title">
                    <i class="fas fa-calendar-day me-2"></i>Today's Bookings (<?= htmlspecialchars($today) ?>)
                </h2>
                <button class="refresh-btn me-3" id="refreshBtn">
                    <i class="fas fa-sync-alt me-1"></i> Refresh
                </button>
            </div>
        </div>

        <?php if (!$result): ?>
            <div class="error-msg">
                <i class="fas fa-exclamation-triangle me-2"></i>
                Failed to retrieve bookings. Please try again later.
            </div>
        <?php else: ?>
            <div class="table-container">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>User ID</th>
                                <th>Flight ID</th>
                                <th>Return Flight</th>
                                <th>Class</th>
                                <th>Amount</th>
                                <th>Payment Status</th>
                                <th>Booking Time</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($result->num_rows > 0): ?>
                                <?php while ($row = $result->fetch_assoc()): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($row['id']) ?></td>
                                        <td><?= htmlspecialchars($row['user_id']) ?></td>
                                        <td><?= htmlspecialchars($row['flight_id']) ?></td>
                                        <td><?= $row['return_flight_id'] ? htmlspecialchars($row['return_flight_id']) : '<span class="text-muted">N/A</span>' ?>
                                        </td>
                                        <td><span class="badge-class"><?= ucfirst(htmlspecialchars($row['class_type'])) ?></span>
                                        </td>
                                        <td><strong>₹<?= number_format($row['total_amount'], 2) ?></strong></td>
                                        <td
                                            class="<?= strtolower($row['payment_status']) === 'paid' ? 'status-paid' : 'status-pending' ?>">
                                            <?= htmlspecialchars($row['payment_status']) ?>
                                        </td>
                                        <td><?= htmlspecialchars($row['booking_time']) ?></td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-primary view-btn" title="View Details"
                                                data-booking-id="<?= htmlspecialchars($row['id']) ?>">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="9" class="no-bookings">
                                        <i class="fas fa-calendar-times"></i>
                                        <h4>No bookings made today</h4>
                                        <p class="text-muted">Check back later for new bookings</p>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php endif; ?>

        <div class="text-center">
            <a href="dashbord.php" class="back-link">
                <i class="fas fa-arrow-left me-1"></i> Back to Dashboard
            </a>
        </div>
    </div>

    <!-- Booking Details Modal -->
    <div class="modal fade" id="bookingModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header" style="background-color: var(--color-dark); color: white;">
                    <h5 class="modal-title">Booking Details</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body" id="bookingDetails">
                    <!-- Details will be loaded here via JavaScript -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary"
                        style="background-color: var(--color-accent); border-color: var(--color-accent);">Print
                        Receipt</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Custom JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Refresh button functionality
            document.getElementById('refreshBtn').addEventListener('click', function () {
                window.location.reload();
            });

            // View booking details
            const viewButtons = document.querySelectorAll('.view-btn');
            const bookingModal = new bootstrap.Modal(document.getElementById('bookingModal'));

            viewButtons.forEach(button => {
                button.addEventListener('click', function () {
                    const bookingId = this.getAttribute('data-booking-id');
                    // In a real application, you would fetch the booking details from the server
                    // For this example, we'll use mock data
                    showBookingDetails(bookingId);
                });
            });

            function showBookingDetails(bookingId) {
                // This is a mock function - in a real app, you would fetch data from your server
                const bookingDetails = document.getElementById('bookingDetails');

                // Mock data - replace with actual API call
                const mockData = {
                    id: bookingId,
                    user_id: 'U' + Math.floor(1000 + Math.random() * 9000),
                    passenger_name: 'John Doe',
                    flight_id: 'FL' + Math.floor(100 + Math.random() * 900),
                    class_type: 'Economy',
                    seat_no: Math.floor(1 + Math.random() * 30) + String.fromCharCode(65 + Math.floor(Math.random() * 6)),
                    payment_status: Math.random() > 0.3 ? 'Paid' : 'Pending',
                    amount: (Math.random() * 5000 + 1000).toFixed(2),
                    booking_time: new Date().toLocaleString()
                };

                bookingDetails.innerHTML = `
                    <div class="row">
                        <div class="col-md-6">
                            <h5>Booking Information</h5>
                            <ul class="list-group list-group-flush mb-3">
                                <li class="list-group-item d-flex justify-content-between">
                                    <span>Booking ID:</span>
                                    <strong>${mockData.id}</strong>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    <span>User ID:</span>
                                    <strong>${mockData.user_id}</strong>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    <span>Passenger Name:</span>
                                    <strong>${mockData.passenger_name}</strong>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    <span>Booking Time:</span>
                                    <strong>${mockData.booking_time}</strong>
                                </li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h5>Flight Details</h5>
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item d-flex justify-content-between">
                                    <span>Flight ID:</span>
                                    <strong>${mockData.flight_id}</strong>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    <span>Class:</span>
                                    <strong>${mockData.class_type}</strong>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    <span>Seat Number:</span>
                                    <strong>${mockData.seat_no}</strong>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    <span>Amount:</span>
                                    <strong>₹${mockData.amount}</strong>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    <span>Payment Status:</span>
                                    <strong class="${mockData.payment_status === 'Paid' ? 'status-paid' : 'status-pending'}">
                                        ${mockData.payment_status}
                                    </strong>
                                </li>
                            </ul>
                        </div>
                    </div>
                `;

                bookingModal.show();
            }

            // Tooltip initialization
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'));
            tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });
    </script>
</body>

</html>