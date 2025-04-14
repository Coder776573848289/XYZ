<?php
session_start();
require_once '../includes/db.php';

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

// Get filters
$user_search = $_GET['user_id'] ?? '';
$flight_search = $_GET['flight_id'] ?? '';
$return_flight_search = $_GET['return_flight_id'] ?? '';
$class_type = $_GET['class_type'] ?? '';
$name_search = $_GET['passenger_name'] ?? '';
$gender = $_GET['gender'] ?? '';
$payment_status = $_GET['payment_status'] ?? '';
$min_age = $_GET['min_age'] ?? '';
$max_age = $_GET['max_age'] ?? '';
$booking_date = $_GET['booking_date'] ?? '';
$min_amount = $_GET['min_amount'] ?? '';
$max_amount = $_GET['max_amount'] ?? '';

// Build query
$sql = "
    SELECT b.*, p.name AS passenger_name, p.gender, p.age, p.seat_no
    FROM booking b
    JOIN passenger p ON b.id = p.booking_id
    WHERE 1=1
";

if (!empty($user_search)) {
    $sql .= " AND b.user_id LIKE '%" . $conn->real_escape_string($user_search) . "%'";
}
if (!empty($flight_search)) {
    $sql .= " AND b.flight_id LIKE '%" . $conn->real_escape_string($flight_search) . "%'";
}
if (!empty($return_flight_search)) {
    $sql .= " AND b.return_flight_id LIKE '%" . $conn->real_escape_string($return_flight_search) . "%'";
}
if (!empty($class_type)) {
    $sql .= " AND b.class_type = '" . $conn->real_escape_string($class_type) . "'";
}
if (!empty($name_search)) {
    $sql .= " AND p.name LIKE '%" . $conn->real_escape_string($name_search) . "%'";
}
if (!empty($gender)) {
    $sql .= " AND p.gender = '" . $conn->real_escape_string($gender) . "'";
}
if (!empty($payment_status)) {
    $sql .= " AND b.payment_status = '" . $conn->real_escape_string($payment_status) . "'";
}
if (!empty($min_age)) {
    $sql .= " AND p.age >= " . intval($min_age);
}
if (!empty($max_age)) {
    $sql .= " AND p.age <= " . intval($max_age);
}
if (!empty($min_amount)) {
    $sql .= " AND b.total_amount >= " . floatval($min_amount);
}
if (!empty($max_amount)) {
    $sql .= " AND b.total_amount <= " . floatval($max_amount);
}
if (!empty($booking_date)) {
    $sql .= " AND DATE(b.booking_time) = '" . $conn->real_escape_string($booking_date) . "'";
}

$sql .= " ORDER BY b.booking_time ASC";

$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Bookings</title>
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

        .navbar-custom {
            background-color: var(--color-dark);
        }

        .navbar-custom .navbar-brand,
        .navbar-custom .nav-link {
            color: var(--text-color);
        }

        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        .card-header {
            background-color: var(--color-secondary);
            color: var(--text-color);
            border-radius: 10px 10px 0 0 !important;
            padding: 15px 20px;
        }

        .btn-primary-custom {
            background-color: var(--color-accent);
            border-color: var(--color-accent);
            color: white;
        }

        .btn-primary-custom:hover {
            background-color: var(--color-secondary);
            border-color: var(--color-secondary);
        }

        .btn-reset {
            background-color: var(--color-light);
            border-color: var(--color-light);
            color: white;
        }

        .btn-reset:hover {
            background-color: var(--color-mid);
            border-color: var(--color-mid);
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--color-accent);
            box-shadow: 0 0 0 0.25rem var(--input-focus);
        }

        .status-paid {
            color: var(--color-success);
            font-weight: bold;
        }

        .status-unpaid {
            color: var(--color-error);
            font-weight: bold;
        }

        .table-responsive {
            overflow-x: auto;
        }

        .table thead th {
            background-color: var(--color-dark);
            color: var(--text-color);
            vertical-align: middle;
        }

        .table tbody tr:hover {
            background-color: rgba(90, 141, 168, 0.1);
        }

        .back-link {
            color: var(--color-accent);
            text-decoration: none;
            transition: color 0.3s;
        }

        .back-link:hover {
            color: var(--color-secondary);
        }

        .filter-section {
            background-color: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            margin-bottom: 20px;
        }

        .filter-label {
            font-weight: 600;
            color: var(--color-secondary);
            margin-bottom: 5px;
        }

        @media (max-width: 768px) {

            .filter-form .col-md-2,
            .filter-form .col-md-3,
            .filter-form .col-md-1 {
                margin-bottom: 10px;
            }

            .table th,
            .table td {
                padding: 8px;
                font-size: 14px;
            }
        }
    </style>
</head>

<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-custom mb-4">
        <div class="container">
            <a class="navbar-brand" href="#">
                <i class="fas fa-plane me-2"></i>Flight Booking System
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="dashbord.php"><i class="fas fa-tachometer-alt me-1"></i> Dashboard</a>
                    </li>
                    
                </ul>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="mb-0"><i class="fas fa-calendar-alt me-2"></i>All Bookings</h3>
                      
                    </div>
                    <div class="card-body">
                        <!-- Filter Form -->
                        <form method="GET" class="filter-form">
                            <div class="row g-3 mb-4">
                                <div class="col-md-3">
                                    <label class="filter-label">User ID</label>
                                    <input type="text" class="form-control" name="user_id" placeholder="User ID"
                                        value="<?= htmlspecialchars($user_search) ?>">
                                </div>
                                <div class="col-md-3">
                                    <label class="filter-label">Flight ID</label>
                                    <input type="text" class="form-control" name="flight_id" placeholder="Flight ID"
                                        value="<?= htmlspecialchars($flight_search) ?>">
                                </div>
                                <div class="col-md-3">
                                    <label class="filter-label">Return Flight ID</label>
                                    <input type="text" class="form-control" name="return_flight_id"
                                        placeholder="Return Flight ID"
                                        value="<?= htmlspecialchars($return_flight_search) ?>">
                                </div>
                                <div class="col-md-3">
                                    <label class="filter-label">Class Type</label>
                                    <select class="form-select" name="class_type">
                                        <option value="">All Classes</option>
                                        <option value="economy" <?= $class_type === 'economy' ? 'selected' : '' ?>>Economy
                                        </option>
                                        <option value="business" <?= $class_type === 'business' ? 'selected' : '' ?>>
                                            Business</option>
                                        <option value="first" <?= $class_type === 'first' ? 'selected' : '' ?>>First
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <div class="row g-3 mb-4">
                                <div class="col-md-4">
                                    <label class="filter-label">Passenger Name</label>
                                    <input type="text" class="form-control" name="passenger_name"
                                        placeholder="Passenger Name" value="<?= htmlspecialchars($name_search) ?>">
                                </div>
                                <div class="col-md-2">
                                    <label class="filter-label">Gender</label>
                                    <select class="form-select" name="gender">
                                        <option value="">All Genders</option>
                                        <option value="Male" <?= $gender === 'Male' ? 'selected' : '' ?>>Male</option>
                                        <option value="Female" <?= $gender === 'Female' ? 'selected' : '' ?>>Female
                                        </option>
                                        <option value="Other" <?= $gender === 'Other' ? 'selected' : '' ?>>Other</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label class="filter-label">Payment Status</label>
                                    <select class="form-select" name="payment_status">
                                        <option value="">All Payments</option>
                                        <option value="Paid" <?= $payment_status === 'Paid' ? 'selected' : '' ?>>Paid
                                        </option>
                                        <option value="Pending" <?= $payment_status === 'Pending' ? 'selected' : '' ?>>
                                            Pending</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label class="filter-label">Min Age</label>
                                    <input type="number" class="form-control" name="min_age" placeholder="Min Age"
                                        min="0" value="<?= htmlspecialchars($min_age) ?>">
                                </div>
                                <div class="col-md-2">
                                    <label class="filter-label">Max Age</label>
                                    <input type="number" class="form-control" name="max_age" placeholder="Max Age"
                                        min="0" value="<?= htmlspecialchars($max_age) ?>">
                                </div>
                            </div>

                            <div class="row g-3 mb-4">
                                <div class="col-md-3">
                                    <label class="filter-label">Min Amount</label>
                                    <input type="number" step="0.01" class="form-control" name="min_amount"
                                        placeholder="Min Amount" value="<?= htmlspecialchars($min_amount) ?>">
                                </div>
                                <div class="col-md-3">
                                    <label class="filter-label">Max Amount</label>
                                    <input type="number" step="0.01" class="form-control" name="max_amount"
                                        placeholder="Max Amount" value="<?= htmlspecialchars($max_amount) ?>">
                                </div>
                                <div class="col-md-3">
                                    <label class="filter-label">Booking Date</label>
                                    <input type="date" class="form-control" name="booking_date"
                                        value="<?= htmlspecialchars($booking_date) ?>">
                                </div>
                                <div class="col-md-3 d-flex align-items-end">
                                    <div>
                                        <button type="submit" class="btn btn-primary-custom me-2">
                                            <i class="fas fa-filter me-1"></i> Filter
                                        </button>
                                        <a href="booking.php" class="btn btn-reset">
                                            <i class="fas fa-sync-alt me-1"></i> Reset
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </form>

                        <!-- Bookings Table -->
                        <div class="table-responsive">
                            <table class="table table-hover table-striped">
                                <thead>
                                    <tr>
                                        <th>Booking ID</th>
                                        <th>User ID</th>
                                        <th>Flight ID</th>
                                        <th>Return Flight</th>
                                        <th>Class</th>
                                        <th>Amount</th>
                                        <th>Passenger</th>
                                        <th>Gender</th>
                                        <th>Age</th>
                                        <th>Seat No</th>
                                        <th>Payment</th>
                                        <th>Booking Time</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if ($result && $result->num_rows > 0): ?>
                                        <?php while ($row = $result->fetch_assoc()): ?>
                                            <tr>
                                                <td><?= htmlspecialchars($row['id']) ?></td>
                                                <td><?= htmlspecialchars($row['user_id']) ?></td>
                                                <td><?= htmlspecialchars($row['flight_id']) ?></td>
                                                <td><?= htmlspecialchars($row['return_flight_id'] ?? '-') ?></td>
                                                <td><span
                                                        class="badge bg-secondary"><?= ucfirst(htmlspecialchars($row['class_type'])) ?></span>
                                                </td>
                                                <td>₹<?= number_format($row['total_amount'], 2) ?></td>
                                                <td><?= htmlspecialchars($row['passenger_name']) ?></td>
                                                <td><?= htmlspecialchars($row['gender']) ?></td>
                                                <td><?= htmlspecialchars($row['age']) ?></td>
                                                <td><?= htmlspecialchars($row['seat_no']) ?></td>
                                                <td>
                                                    <span
                                                        class="badge <?= $row['payment_status'] === 'Paid' ? 'bg-success' : 'bg-danger' ?>">
                                                        <?= htmlspecialchars($row['payment_status']) ?>
                                                    </span>
                                                </td>
                                                <td><?= htmlspecialchars($row['booking_time']) ?></td>
                                                <td>
                                                    <button class="btn btn-sm btn-outline-primary" title="View Details">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                    <button class="btn btn-sm btn-outline-secondary" title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        <?php endwhile; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="13" class="text-center py-4">
                                                <i class="fas fa-calendar-times fa-2x mb-3"
                                                    style="color: var(--color-light);"></i>
                                                <h5>No bookings found</h5>
                                                <p class="text-muted">Try adjusting your search filters</p>
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="text-center mb-4">
            <a href="dashbord.php" class="back-link">
                <i class="fas fa-arrow-left me-1"></i> Back to Dashboard
            </a>
        </div>
    </div>

    <!-- Bootstrap 5 JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Custom JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Export button functionality
            document.getElementById('exportBtn').addEventListener('click', function () {
                // This would be replaced with actual export functionality
                alert('Export functionality would be implemented here');
            });

            // Tooltip initialization
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'));
            tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });

            // Responsive table handling
            function handleResponsiveTable() {
                const tables = document.querySelectorAll('.table-responsive');
                tables.forEach(table => {
                    if (window.innerWidth < 768) {
                        table.classList.add('table-responsive-sm');
                    } else {
                        table.classList.remove('table-responsive-sm');
                    }
                });
            }

            // Initial check
            handleResponsiveTable();

            // Check on resize
            window.addEventListener('resize', handleResponsiveTable);

            // Date picker enhancement (would need a proper date picker library for more features)
            const dateInputs = document.querySelectorAll('input[type="date"]');
            dateInputs.forEach(input => {
                input.addEventListener('focus', function () {
                    this.type = 'date';
                });

                input.addEventListener('blur', function () {
                    if (!this.value) {
                        this.type = 'text';
                    }
                });
            });
        });
    </script>
</body>

</html>