<?php 
require_once 'includes/db.php';
include 'includes/header.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to OpenSky Airlines</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="assets/css/welcomePage.css">
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <img src="assets/image/img.jpg" alt="OpenSky Logo" class="logo-img">
                <span>OpenSky</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                        <a class="nav-link" href="flight_search.php">Quick Flight Search</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="register.php">Register</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="login.php">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="admin/login.php">Admin</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Carousel -->
    <section class="hero">
        <div id="mainCarousel" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img src="assets/image/img.jpg" class="d-block w-100" alt="Flight Image 1">
                    <div class="carousel-caption">
                        <h1>Experience the Sky Like Never Before</h1>
                        <p>Premium comfort at affordable prices</p>
                        <div class="cta-buttons">
                            <a href="#about" class="btn btn-primary">Learn More</a>
                            <a href="register.php" class="btn btn-outline">Join Now</a>
                        </div>
                    </div>
                </div>
                <div class="carousel-item">
                    <img src="assets/image/img1.jpg" class="d-block w-100" alt="Flight Image 2">
                    <div class="carousel-caption">
                        <h1>Your Journey Starts Here</h1>
                        <p>Discover our world-class services</p>
                        <div class="cta-buttons">
                            <a href="#why-us" class="btn btn-primary">Our Features</a>
                            <a href="login.php" class="btn btn-outline">Book Now</a>
                        </div>
                    </div>
                </div>
                <div class="carousel-item">
                    <img src="assets/image/img2.jpg" class="d-block w-100" alt="Flight Image 3">
                    <div class="carousel-caption">
                        <h1>Fly With Confidence</h1>
                        <p>Safety is our top priority</p>
                        <div class="cta-buttons">
                            <a href="#testimonials" class="btn btn-primary">Testimonials</a>
                            <a href="register.php" class="btn btn-outline">Sign Up</a>
                        </div>
                    </div>
                </div>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#mainCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon"></span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#mainCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon"></span>
            </button>
        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="py-5">
        <div class="container">
            <div class="section-header">
                <h2>About OpenSky</h2>
                <p>Your journey, our commitment</p>
            </div>
            <div class="row">
                <div class="col-md-4 mb-4">
                    <div class="about-card">
                        <i class="bi bi-airplane"></i>
                        <h3>Our Mission</h3>
                        <p>To provide safe, comfortable, and affordable air travel experiences to every passenger.</p>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="about-card">
                        <i class="bi bi-globe"></i>
                        <h3>Our Vision</h3>
                        <p>To become the most trusted airline, delivering excellence in service and innovation.</p>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="about-card">
                        <i class="bi bi-chat-quote"></i>
                        <h3>Our Promise</h3>
                        <p>"Fly with Comfort & Safety" - that's our guarantee to you.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Why Choose Us -->
    <section id="why-us" class="py-5 bg-light">
        <div class="container">
            <div class="section-header">
                <h2>Why Choose OpenSky</h2>
                <p>We go above and beyond for your comfort</p>
            </div>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="feature-card">
                        <img src="assets/image/rela.jpg" alt="Reliable Flights">
                        <div class="feature-content">
                            <h3>Reliable Flights</h3>
                            <p>On-time departures and arrivals for stress-free travel planning.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card">
                        <img src="assets/image/crew.jpg" alt="Expert Crew">
                        <div class="feature-content">
                            <h3>Expert Crew</h3>
                            <p>Highly trained professionals ensuring your safety and comfort.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card">
                        <img src="assets/image/cab.jpg" alt="Comfortable Seating">
                        <div class="feature-content">
                            <h3>Luxury Cabins</h3>
                            <p>Spacious seating with premium amenities for maximum comfort.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials -->
    <section id="testimonials" class="py-5">
        <div class="container">
            <div class="section-header">
                <h2>What Our Passengers Say</h2>
                <p>Hear from our satisfied customers</p>
            </div>
            <div id="testimonialCarousel" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <div class="testimonial">
                            <img src="assets/image/maitry.png" alt="Maitry Gothi">
                            <div class="testimonial-content">
                                <h4>Maitry Gothi</h4>
                                <p class="location">Surat, India</p>
                                <p class="quote">"OpenSky made my trip smooth and comfortable. The staff was incredibly helpful!"</p>
                                <div class="stars">
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="carousel-item">
                        <div class="testimonial">
                            <img src="assets/image/meet.png" alt="Meet Patel">
                            <div class="testimonial-content">
                                <h4>Meet Patel</h4>
                                <p class="location">Bhavnagar, India</p>
                                <p class="quote">"I was amazed by the punctuality and service. Definitely choosing OpenSky again!"</p>
                                <div class="stars">
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="carousel-item">
                        <div class="testimonial">
                            <img src="assets/image/kavit.png" alt="Kavit Bokarvadiya">
                            <div class="testimonial-content">
                                <h4>Kavit Bokarvadiya</h4>
                                <p class="location">Rajkot, India</p>
                                <p class="quote">"From booking to landing, everything was seamless. Highly recommended!"</p>
                                <div class="stars">
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-half"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#testimonialCarousel" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon"></span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#testimonialCarousel" data-bs-slide="next">
                    <span class="carousel-control-next-icon"></span>
                </button>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <div class="footer-brand">
                        <img src="assets/image/img.jpg" alt="OpenSky Logo">
                        <span>OpenSky</span>
                    </div>
                    <p>Fly with Comfort & Safety. OpenSky is committed to providing the best air travel experience.</p>
                </div>
                <div class="col-md-2">
                    <h4>Quick Links</h4>
                    <ul>
                        <li><a href="register.php">Register</a></li>
                        <li><a href="login.php">Login</a></li>
                        <li><a href="admin/login.php">Admin</a></li>
                    </ul>
                </div>
                <div class="col-md-3">
                    <h4>Contact Us</h4>
                    <ul class="contact-info">
                        <li><i class="bi bi-envelope"></i> support@opensky.com</li>
                        <li><i class="bi bi-phone"></i> +91-9876543210</li>
                        <li><i class="bi bi-geo-alt"></i> Terminal 3, Sky City Airport</li>
                    </ul>
                </div>
                <div class="col-md-3">
                    <h4>Follow Us</h4>
                    <div class="social-links">
                        <a href="#"><i class="bi bi-facebook"></i></a>
                        <a href="#"><i class="bi bi-twitter"></i></a>
                        <a href="#"><i class="bi bi-instagram"></i></a>
                        <a href="#"><i class="bi bi-linkedin"></i></a>
                    </div>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2025 OpenSky Airlines. All Rights Reserved.</p>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/welcome.js"></script>
</body>
</html>