<?php
session_start();
require_once '../includes/db.php'; // DB connection file path

$error = ""; // Initialize an error message variable

if (isset($_POST['login'])) {
    // Collecting email and password from form
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (empty($username) || empty($password)) {
        $error = "Please enter both username and password.";
    } else {
        // Check if admin exists
        $sql = "SELECT * FROM admin WHERE username=? AND password=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $username, $password);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            // Valid admin
            $_SESSION['admin'] = $username;
            header("Location: dashbord.php");
            exit();
        } else {
            // Invalid credentials
            $error = "Invalid username or password.";
        }
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Admin Login | OpenSky</title>
    <link rel="stylesheet" href="../assets/css/admin-login.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body>
    <div class="login-container">
        <div class="login-left">
            <div class="left-content">
                <div class="logo-card">
                    <img src="../assets/image/img.jpg" alt="OpenSky Logo" class="logo-spin">
                    <h2>Admin Dashboard</h2>
                    <p class="tagline">Manage your flight booking system</p>
                </div>
                <div class="features-list">
                    <div class="feature-item">
                        <i class="feature-icon fas fa-shield-alt"></i>
                        <span>Secure admin portal</span>
                    </div>
                    <div class="feature-item">
                        <i class="feature-icon fas fa-chart-line"></i>
                        <span>Real-time analytics</span>
                    </div>
                    <div class="feature-item">
                        <i class="feature-icon fas fa-cog"></i>
                        <span>System configuration</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="login-right">
            <div class="form-container">
                <div class="form-header">
                    <h2>Admin Login</h2>
                    <p>Access your management console</p>
                </div>
                <form method="POST" class="login-form">
                    <div class="input-group">
                        <i class="input-icon fas fa-user"></i>
                        <input type="text" name="username" placeholder="Username" required>
                    </div>
                    <div class="input-group">
                        <i class="input-icon fas fa-lock"></i>
                        <input type="password" name="password" placeholder="Password" required>
                        <span class="password-toggle"><i class="fas fa-eye"></i></span>
                    </div>

                    <button type="submit" name="login" class="btn-login">
                        <span>Log In</span>
                        <i class="fas fa-arrow-right arrow-icon"></i>
                    </button>
                </form>
                <p class="register-link"><a href="../index.php"><i class="fas fa-arrow-left"></i> Back to main site</a>
                </p>
            </div>
        </div>
    </div>
</body>

</html>