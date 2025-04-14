<?php
session_start();
include 'includes/header.php';
require_once 'includes/db.php';

if (isset($_POST['login'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    $result = mysqli_query($conn, "SELECT * FROM user WHERE email = '$email' LIMIT 1");

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        if (password_verify($password, $row['password'])) {
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['user_name'] = $row['name'];
            echo "<div class='flash-message success'> Login successful! Redirecting...</div>";
            echo "<script>setTimeout(() => window.location.href = 'flight_search.php', 1000);</script>";
            exit();
        } else {
            echo "<div class='flash-message error'> Incorrect password.</div>";
        }
    } else {
        echo "<div class='flash-message error'> User not found.</div>";
    }
}
?>

<link rel="stylesheet" href="assets/css/login.css">

<div class="login-container">
    <div class="login-left">
        <div class="left-content">
            <div class="logo-card">
                <img src="assets/image/img.jpg" alt="OpenSky Logo" class="logo-spin">
                <h2>Welcome Back</h2>
                <p class="tagline">Ready for your next journey?</p>
            </div>
            <div class="features-list">
                <div class="feature-item">
                    <i class="feature-icon">✈️</i>
                    <span>Track your flights</span>
                </div>
                <div class="feature-item">
                    <i class="feature-icon">🔒</i>
                    <span>Secure account protection</span>
                </div>
                <div class="feature-item">
                    <i class="feature-icon">💎</i>
                    <span>Exclusive member benefits</span>
                </div>
            </div>
        </div>
    </div>
    <div class="login-right">
        <div class="form-container">
            <div class="form-header">
                <h2>Login to OpenSky</h2>
                <p>Access your flight dashboard</p>
            </div>
            <form method="POST" class="login-form">
                <div class="input-group">
                    <i class="input-icon">✉️</i>
                    <input type="email" name="email" placeholder="Email Address" required>
                </div>
                <div class="input-group">
                    <i class="input-icon">🔑</i>
                    <input type="password" name="password" placeholder="Password" required>
                    <span class="password-toggle">👁️</span>
                </div>
                <div class="form-options">
                    <div class="remember-me">
                        <input type="checkbox" id="remember" name="remember">
                        <label for="remember">Remember me</label>
                    </div>
                    <a href="reset_password.php" class="forgot-password">Forgot password?</a>
                </div>
                <button type="submit" name="login" class="btn-login">
                    <span>Sign In</span>
                    <i class="arrow-icon">→</i>
                </button>
            </form>
            <!-- <div class="social-login">
                <p class="divider"><span>Or continue with</span></p>
                <div class="social-icons">
                    <a href="#" class="social-icon">G</a>
                    <a href="#" class="social-icon">f</a>
                    <a href="#" class="social-icon">in</a>
                </div>
            </div> -->
            <p class="register-link">Don't have an account? <a href="register.php">Sign up</a></p>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>