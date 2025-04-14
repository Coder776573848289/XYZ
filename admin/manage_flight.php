<?php
session_start();
require_once '../includes/db.php';

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

// Fetch all flight details
$sql = "SELECT * FROM flight ORDER BY departure ASC";
$result = $conn->query($sql);
$flights = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $flights[] = $row;
    }
}

// Handle delete functionality
if (isset($_GET['delete_id']) && is_numeric($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $delete_sql = "DELETE FROM flight WHERE id = ?";
    $stmt = $conn->prepare($delete_sql);
    if ($stmt) {
        $stmt->bind_param("i", $delete_id);
        if ($stmt->execute()) {
            header("Location: manage_flight.php?deleted=true");
            exit();
        } else {
            $delete_error = "Delete Error: " . $stmt->error;
        }
        $stmt->close();
    } else {
        $delete_error = "Prepare Failed: " . $conn->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Manage Flights</title>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../assets/css/manage_flight.css">
</head>

<body>
    <div class="container-fluid">
        <div class="header">
            <h2><i class="bi bi-airplane me-2"></i>Flight Management</h2>
            <a href="add_flight.php" class="btn-add">
                <i class="bi bi-plus-circle"></i> Add Flight
            </a>
        </div>

        <?php if (isset($_GET['updated']) && $_GET['updated'] == 'true'): ?>
            <div class="alert alert-custom alert-success fade-in">
                <i class="bi bi-check-circle-fill text-success"></i>
                <div>Flight details updated successfully!</div>
            </div>
        <?php endif; ?>

        <?php if (isset($_GET['deleted']) && $_GET['deleted'] == 'true'): ?>
            <div class="alert alert-custom alert-success fade-in">
                <i class="bi bi-check-circle-fill text-success"></i>
                <div>Flight deleted successfully!</div>
            </div>
        <?php endif; ?>

        <?php if (isset($delete_error)): ?>
            <div class="alert alert-custom alert-danger fade-in">
                <i class="bi bi-exclamation-triangle-fill text-danger"></i>
                <div><?php echo $delete_error; ?></div>
            </div>
        <?php endif; ?>

        <div class="table-container fade-in">
            <?php if (!empty($flights)): ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Flight ID</th>
                                <th>Airline</th>
                                <th>From</th>
                                <th>To</th>
                                <th>Departure</th>
                                <th>Arrival</th>
                                <th>Duration</th>
                                <th>Status</th>
                                <th>Economy</th>
                                <th>Business</th>
                                <th>First Class</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($flights as $flight): ?>
                                <tr>
                                    <td><?php echo $flight['id']; ?></td>
                                    <td><?php echo htmlspecialchars($flight['airline_name']); ?></td>
                                    <td><?php echo htmlspecialchars($flight['from_location']); ?></td>
                                    <td><?php echo htmlspecialchars($flight['to_location']); ?></td>
                                    <td><?php echo date('M d, Y H:i', strtotime($flight['departure'])); ?></td>
                                    <td><?php echo date('M d, Y H:i', strtotime($flight['arrival'])); ?></td>
                                    <td><?php echo $flight['duration']; ?> min</td>
                                    <td>
                                        <?php
                                        $status = htmlspecialchars($flight['status']);
                                        $statusClass = '';
                                        if ($status == 'On Time')
                                            $statusClass = 'status-on-time';
                                        elseif ($status == 'Delayed')
                                            $statusClass = 'status-delayed';
                                        elseif ($status == 'Cancelled')
                                            $statusClass = 'status-cancelled';
                                        elseif ($status == 'Scheduled')
                                            $statusClass = 'status-scheduled';
                                        ?>
                                        <span class="badge <?php echo $statusClass; ?>"><?php echo $status; ?></span>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column">
                                            <span><?php echo $flight['economy_seats']; ?> seats</span>
                                            <span
                                                class="price">₹<?php echo number_format($flight['economy_price'], 2); ?></span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column">
                                            <span><?php echo $flight['business_seats']; ?> seats</span>
                                            <span
                                                class="price">₹<?php echo number_format($flight['business_price'], 2); ?></span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column">
                                            <span><?php echo $flight['first_class_seats']; ?> seats</span>
                                            <span
                                                class="price">₹<?php echo number_format($flight['first_class_price'], 2); ?></span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <a href="edit_flight.php?id=<?php echo $flight['id']; ?>"
                                                class="action-btn btn-edit">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <!-- <button onclick="confirmDelete(<?php echo $flight['id']; ?>)"
                                                class="action-btn btn-delete">
                                                <i class="bi bi-trash"></i>
                                            </button> -->
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="empty-state">
                    <i class="bi bi-airplane"></i>
                    <h4>No Flights Available</h4>
                    <p>There are currently no flights in the system.</p>
                    <a href="add_flight.php" class="btn-add mt-3">
                        <i class="bi bi-plus-circle"></i> Add Your First Flight
                    </a>
                </div>
            <?php endif; ?>
        </div>

        <div class="d-flex justify-content-between">
            <a href="dashbord.php" class="btn-back">
                <i class="bi bi-arrow-left"></i> Back to Dashboard
            </a>

            <?php if (!empty($flights)): ?>
                <div class="text-muted">
                    Showing <?php echo count($flights); ?> flights
                </div>
            <?php endif; ?>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/css/manage_flight.js"></script>
</body>

</html>