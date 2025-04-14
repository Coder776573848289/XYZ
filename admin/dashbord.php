<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['admin'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | OpenSky</title>
    <link rel="stylesheet" href="../assets/css/admin-dashbord.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="admin-container">
        <!-- Sidebar Navigation -->
        <div class="admin-sidebar">
            <div class="admin-profile">
                <img src="../assets/image/img.jpg" alt="Admin Profile" class="profile-img">
                <h3><?php echo $username; ?></h3>
                <p>Administrator</p>
            </div>
            
            <nav class="admin-menu">
                <ul>
                    <li class="active">
                        <a href="#dashboard.php">
                            <i class="fas fa-tachometer-alt"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a href="manage_flight.php">
                            <i class="fas fa-plane"></i>
                            <span>Manage Flights</span>
                        </a>
                    </li>
                    <li>
                        <a href="booking.php">
                            <i class="fas fa-ticket-alt"></i>
                            <span>Bookings</span>
                        </a>
                    </li>
                    <!-- <li>
                        <a href="pending-payments.php">
                            <i class="fas fa-money-bill-wave"></i>
                            <span>Pending Payments</span>
                            <span class="badge">5</span> --><!-- notification
                        </a>
                    </li> -->
                    <li>
                        <a href="today_booking.php">
                            <i class="fas fa-calendar-day"></i>
                            <span>Today's Bookings</span>
                        </a>
                    </li>
                    <li>
                        <a href="manage_user.php">
                            <i class="fas fa-users"></i>
                            <span>Manage Users</span>
                        </a>
                    </li>
                    <li>
                        <a href="logout.php">
                            <i class="fas fa-sign-out-alt"></i>
                            <span>Logout</span>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>

        <!-- Main Content Area -->
        <div class="admin-main">
            <header class="admin-header">
                <h1>Dashboard Overview</h1>
                <div class="header-actions">
                    <span class="welcome-msg">Welcome back, <?php echo $username; ?>!</span>
                    <a href="logout.php" class="logout-btn">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a>
                </div>
            </header>

            <div class="dashboard-cards">
                <!-- Summary Cards -->
                <div class="card">
                    <div class="card-icon bg-blue">
                        <i class="fas fa-plane"></i>
                    </div>
                    <div class="card-content">
                        <h3>Total Flights</h3>
                        <!-- <p>128</p> Dynamic value from database -->
                        <a href="manage_flight.php">View All <i class="fas fa-arrow-right"></i></a>
                    </div>
                </div>

                <div class="card">
                    <div class="card-icon bg-green">
                        <i class="fas fa-ticket-alt"></i>
                    </div>
                    <div class="card-content">
                        <h3>Today's Bookings</h3>
                        <!-- <p>24</p> Dynamic value from database -->
                        <a href="today_booking.php">View All <i class="fas fa-arrow-right"></i></a>
                    </div>
                </div>
<!-- 
                <div class="card">
                    <div class="card-icon bg-orange">
                        <i class="fas fa-money-bill-wave"></i>
                    </div>
                    <div class="card-content">
                        <h3>Pending Payments</h3>
                        <p>5</p> Dynamic value from database
                        <a href="pending-payments.php">Process Now <i class="fas fa-arrow-right"></i></a>
                    </div>
                </div> -->

                <div class="card">
                    <div class="card-icon bg-purple">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="card-content">
                        <h3>Active Users</h3>
                        <!-- <p>342</p> Dynamic value from database -->
                        <a href="manage_user.php">Manage Users <i class="fas fa-arrow-right"></i></a>
                    </div>
                </div>
            </div>

            <!-- Recent Activity Section -->
           
        </div>
    </div>

    <script src="../assets/js/admin-dashboard.js"></script>
</body>
</html>