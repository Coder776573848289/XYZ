<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require_once 'includes/db.php';
$user_id = $_SESSION['user_id'];

// Fetch user info
$user_sql = "SELECT * FROM user WHERE id = ?";
$stmt = $conn->prepare($user_sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user_result = $stmt->get_result();
$user = $user_result->fetch_assoc();

// Fetch current bookings (upcoming flights)
$current_sql = "
    SELECT b.*, f.from_location, f.to_location, f.departure, f.arrival
    FROM booking b
    JOIN flight f ON b.flight_id = f.id
    WHERE b.user_id = ? AND b.payment_status = 'Paid' AND f.departure > NOW()
    ORDER BY f.departure ASC
";
$stmt = $conn->prepare($current_sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$current_bookings = $stmt->get_result();

// Fetch travel history (past flights)
$history_sql = "
    SELECT b.*, f.from_location, f.to_location, f.departure, f.arrival
    FROM booking b
    JOIN flight f ON b.flight_id = f.id
    WHERE b.user_id = ? AND b.payment_status = 'Paid' AND f.departure <= NOW()
    ORDER BY f.departure DESC
";
$stmt = $conn->prepare($history_sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$past_bookings = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <h2 class="mb-4">Welcome, <?= htmlspecialchars($user['name']) ?></h2>

    <ul class="nav nav-tabs" id="dashboardTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile" type="button" role="tab">👤 Profile Info</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="current-tab" data-bs-toggle="tab" data-bs-target="#current" type="button" role="tab">📅 Current Bookings</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="history-tab" data-bs-toggle="tab" data-bs-target="#history" type="button" role="tab">🕘 Travel History</button>
        </li>
        <li class="nav-item ms-auto">
            <a href="logout.php" class="btn btn-danger btn-sm mt-2">🚪 Logout</a>
        </li>
    </ul>

    <div class="tab-content p-4 border bg-white" id="dashboardTabsContent">
        <!-- Profile Info -->
        <div class="tab-pane fade show active" id="profile" role="tabpanel">
            <h4>Profile Information</h4>
            <p><strong>Name:</strong> <?= htmlspecialchars($user['name']) ?></p>
            <p><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></p>
            <p><strong>Phone:</strong> <?= htmlspecialchars($user['phone']) ?></p>
            <p><strong>Joined:</strong> <?= $user['created_at'] ?></p>
        </div>

        <!-- Current Bookings -->
        <div class="tab-pane fade" id="current" role="tabpanel">
            <h4>Upcoming Bookings</h4>
            <?php if ($current_bookings->num_rows > 0): ?>
                <table class="table table-bordered table-sm">
                    <thead>
                        <tr>
                            <th>From → To</th>
                            <th>Departure</th>
                            <th>Arrival</th>
                            <th>Class</th>
                            <th>Total Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $current_bookings->fetch_assoc()): ?>
                            <tr>
                                <td><?= $row['from_location'] ?> → <?= $row['to_location'] ?></td>
                                <td><?= $row['departure'] ?></td>
                                <td><?= $row['arrival'] ?></td>
                                <td><?= ucfirst($row['class_type']) ?></td>
                                <td>₹<?= $row['total_amount'] ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No upcoming bookings found.</p>
            <?php endif; ?>
        </div>

        <!-- Travel History -->
        <div class="tab-pane fade" id="history" role="tabpanel">
            <h4>Past Travel History</h4>
            <?php if ($past_bookings->num_rows > 0): ?>
                <table class="table table-bordered table-sm">
                    <thead>
                        <tr>
                            <th>From → To</th>
                            <th>Departure</th>
                            <th>Arrival</th>
                            <th>Class</th>
                            <th>Total Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $past_bookings->fetch_assoc()): ?>
                            <tr>
                                <td><?= $row['from_location'] ?> → <?= $row['to_location'] ?></td>
                                <td><?= $row['departure'] ?></td>
                                <td><?= $row['arrival'] ?></td>
                                <td><?= ucfirst($row['class_type']) ?></td>
                                <td>₹<?= $row['total_amount'] ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No past travel history available.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
