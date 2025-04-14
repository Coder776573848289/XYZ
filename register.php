<?php include 'includes/header.php'; ?>
<link rel="stylesheet" href="assets/css/register.css">

<div class="register-container">
    <div class="register-left">
        <div class="left-content">
            <div class="logo-card">
                <img src="assets/image/img.jpg" alt="Logo" class="logo-spin">
                <h2>OpenSky</h2>
                <p class="tagline">Elevate your digital experience</p>
            </div>
            <div class="features-list">
                <div class="feature-item">
                    <i class="feature-icon">✈️</i>
                    <span>Fast & seamless onboarding</span>
                </div>
                <div class="feature-item">
                    <i class="feature-icon">🔒</i>
                    <span>Military-grade security</span>
                </div>
                <div class="feature-item">
                    <i class="feature-icon">🌐</i>
                    <span>Global accessibility</span>
                </div>
            </div>
        </div>
    </div>
    <div class="register-right">
        <div class="form-container">
            <div class="form-header">
                <h2>Create Your Account</h2>
                <p>Join our community today</p>
            </div>
            <form method="POST" class="register-form">
                <div class="input-group">
                    <i class="input-icon">👤</i>
                    <input type="text" name="name" placeholder="Full Name" required>
                </div>
                <div class="input-group">
                    <i class="input-icon">✉️</i>
                    <input type="email" name="email" placeholder="Email" required>
                </div>
                <div class="input-group">
                    <i class="input-icon">🔑</i>
                    <input type="password" name="password" placeholder="Password" required>
                    <span class="password-toggle">👁️</span>
                </div>
                <div class="terms-group">
                    <input type="checkbox" id="terms" required>
                    <label for="terms">I agree to the <a href="#">Terms</a> and <a href="#">Privacy Policy</a></label>
                </div>
                <button type="submit" name="register" class="btn-register">
                    <span>Register Now</span>
                    <i class="arrow-icon">→</i>
                </button>
            </form>
            <!-- <div class="social-login">
                <p class="divider"><span>Or sign up with</span></p>
                <div class="social-icons">
                    <a href="#" class="social-icon">G</a>
                    <a href="#" class="social-icon">f</a>
                    <a href="#" class="social-icon">in</a>
                </div>
            </div> -->
            <p class="login-link">Already have an account? <a href="login.php">Login here</a></p>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>

<?php
require_once 'includes/db.php';

if (isset($_POST['register'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $checkEmail = mysqli_query($conn, "SELECT * FROM user WHERE email = '$email'");
    if (mysqli_num_rows($checkEmail) > 0) {
        echo "<div class='flash-message error'><span>⚠️</span> Email already exists!</div>";
    } else {
        $insert = mysqli_query($conn, "INSERT INTO user (name, email, password) VALUES ('$name', '$email', '$password')");
        if ($insert) {
            echo "<div class='flash-message success'><span>✅</span> Registered successfully! Redirecting...</div>";
            echo "<script>setTimeout(() => window.location.href = 'login.php', 1500);</script>";
        } else {
            echo "<div class='flash-message error'><span>❌</span> Registration failed. Please try again.</div>";
        }
    }
}
?>