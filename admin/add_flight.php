<?php
session_start();
require_once '../includes/db.php';

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $airline_name = trim($_POST['airline_name']);
    $from_location = $_POST['from_location'];
    $to_location = $_POST['to_location'];
    $departure = $_POST['departure'];
    $arrival = $_POST['arrival'];
    $economy_seats = intval($_POST['economy_seats']);
    $business_seats = intval($_POST['business_seats']);
    $first_class_seats = isset($_POST['first_class_seats']) ? intval($_POST['first_class_seats']) : 0;
    $economy_price = floatval($_POST['economy_price']);
    $business_price = floatval($_POST['business_price']);
    $first_class_price = isset($_POST['first_class_price']) ? floatval($_POST['first_class_price']) : 0.00;

    // Calculate duration in minutes
    $departure_dt = new DateTime($departure);
    $arrival_dt = new DateTime($arrival);
    $duration = $departure_dt->diff($arrival_dt)->h * 60 + $departure_dt->diff($arrival_dt)->i;

    // Status is now taken from the form submission
    $status = $_POST['status'];

    // Validate airport
    if ($from_location === $to_location) {
        die("From and To locations cannot be the same.");
    }

    $insert_sql = "INSERT INTO flight
    (airline_name, from_location, to_location, departure, arrival, duration, status,
     economy_seats, economy_price, business_seats, business_price, first_class_seats, first_class_price)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($insert_sql);
    if ($stmt) {
        $stmt->bind_param(
            "sssssisidiidi",
            $airline_name,
            $from_location,
            $to_location,
            $departure,
            $arrival,
            $duration,
            $status,
            $economy_seats,
            $economy_price,
            $business_seats,
            $business_price,
            $first_class_seats,
            $first_class_price
        );

        if ($stmt->execute()) {
            header("Location: manage_flight.php");
            exit();
        } else {
            echo "Insert Error: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Prepare Failed: " . $conn->error;
    }
    $conn->close();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Add New Flight | Flight Management</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../assets/css/add_flight.css">
</head>

<body>
    <div class="container">
        <div class="form-container fade-in">
            <div class="form-header">
                <h2><i class="bi bi-airplane"></i> Add New Flight</h2>
            </div>

            <form method="POST" id="flightForm" novalidate>
                <!-- Flight Information Section -->
                <h5 class="section-title">Flight Information</h5>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="airline_name" class="form-label">Airline Name</label>
                        <input type="text" class="form-control" id="airline_name" name="airline_name" required>
                        <div class="invalid-feedback">Please provide an airline name.</div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="status" class="form-label">Flight Status</label>
                        <select class="form-select" id="status" name="status" required>
                            <option value="On Time">On Time</option>
                            <option value="Delayed">Delayed</option>
                            <option value="Cancelled">Cancelled</option>
                            <option value="Scheduled">Scheduled</option>
                        </select>
                    </div>
                </div>

                <!-- Route Information Section -->
                <h5 class="section-title">Route Information</h5>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="from_location" class="form-label">From Location</label>
                        <select class="form-select" id="from_location" name="from_location" required>
                            <option value="">-- Select Source --</option>
                            <option value="DEL">Delhi (DEL)</option>
                            <option value="BOM">Mumbai (BOM)</option>
                            <option value="BLR">Bangalore (BLR)</option>
                            <option value="HYD">Hyderabad (HYD)</option>
                            <option value="MAA">Chennai (MAA)</option>
                            <option value="CCU">Kolkata (CCU)</option>
                        </select>
                        <div class="invalid-feedback">Please select departure location.</div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="to_location" class="form-label">To Location</label>
                        <select class="form-select" id="to_location" name="to_location" required>
                            <option value="">-- Select Destination --</option>
                            <option value="DEL">Delhi (DEL)</option>
                            <option value="BOM">Mumbai (BOM)</option>
                            <option value="BLR">Bangalore (BLR)</option>
                            <option value="HYD">Hyderabad (HYD)</option>
                            <option value="MAA">Chennai (MAA)</option>
                            <option value="CCU">Kolkata (CCU)</option>
                        </select>
                        <div class="invalid-feedback">Please select arrival location.</div>
                    </div>
                </div>

                <!-- Schedule Section -->
                <h5 class="section-title">Schedule</h5>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="departure" class="form-label">Departure Time</label>
                        <input type="datetime-local" class="form-control" id="departure" name="departure" required>
                        <div class="invalid-feedback">Departure time must be in the future.</div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="arrival" class="form-label">Arrival Time</label>
                        <input type="datetime-local" class="form-control" id="arrival" name="arrival" required>
                        <div class="invalid-feedback">Arrival must be after departure.</div>
                    </div>
                </div>

                <!-- Seating Configuration Section -->
                <h5 class="section-title">Seating Configuration</h5>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="economy_seats" class="form-label">Economy Seats</label>
                        <input type="number" class="form-control" id="economy_seats" name="economy_seats" min="0"
                            required>
                        <div class="invalid-feedback">Must be a non-negative whole number.</div>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="business_seats" class="form-label">Business Seats</label>
                        <input type="number" class="form-control" id="business_seats" name="business_seats" min="0"
                            required>
                        <div class="invalid-feedback">Must be a non-negative whole number.</div>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="first_class_seats" class="form-label">First Class Seats</label>
                        <input type="number" class="form-control" id="first_class_seats" name="first_class_seats"
                            min="0" required>
                        <div class="invalid-feedback">Must be a non-negative whole number.</div>
                    </div>
                </div>

                <!-- Pricing Section -->
                <h5 class="section-title">Pricing (₹)</h5>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="economy_price" class="form-label">Economy Price</label>
                        <div class="price-input-group">
                            <input type="number" class="form-control price-input" id="economy_price"
                                name="economy_price" step="0.01" min="0" required>
                        </div>
                        <div class="invalid-feedback">Must be a non-negative value.</div>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="business_price" class="form-label">Business Price</label>
                        <div class="price-input-group">
                            <input type="number" class="form-control price-input" id="business_price"
                                name="business_price" step="0.01" min="0" required>
                        </div>
                        <div class="invalid-feedback">Must be a non-negative value.</div>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="first_class_price" class="form-label">First Class Price</label>
                        <div class="price-input-group">
                            <input type="number" class="form-control price-input" id="first_class_price"
                                name="first_class_price" step="0.01" min="0" required>
                        </div>
                        <div class="invalid-feedback">Must be a non-negative value.</div>
                    </div>
                </div>

                <button type="submit" class="btn btn-submit">
                    <i class="bi bi-save"></i> Save Flight Details
                </button>

                <div class="text-center mt-3">
                    <a href="manage_flight.php" class="btn-back">
                        <i class="bi bi-arrow-left"></i> Back to Flight Management
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/add_flight.js"></script>
</body>

</html>