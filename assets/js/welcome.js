// Navbar scroll effect
window.addEventListener('scroll', function() {
    const navbar = document.querySelector('.navbar');
    if (window.scrollY > 50) {
        navbar.classList.add('scrolled');
    } else {
        navbar.classList.remove('scrolled');
    }
});

// Smooth scrolling for anchor links
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function(e) {
        e.preventDefault();
        
        const targetId = this.getAttribute('href');
        if (targetId === '#') return;
        
        const targetElement = document.querySelector(targetId);
        if (targetElement) {
            window.scrollTo({
                top: targetElement.offsetTop - 80,
                behavior: 'smooth'
            });
        }
    });
});

// Initialize carousels
document.addEventListener('DOMContentLoaded', function() {
    // Main carousel
    const mainCarousel = new bootstrap.Carousel('#mainCarousel', {
        interval: 5000,
        ride: 'carousel',
        pause: 'hover'
    });
    
    // Testimonial carousel
    const testimonialCarousel = new bootstrap.Carousel('#testimonialCarousel', {
        interval: 7000,
        ride: 'carousel',
        pause: 'hover'
    });
    
    // Add animation to carousel items
    const carouselItems = document.querySelectorAll('.carousel-item');
    carouselItems.forEach((item, index) => {
        if (index === 0) item.classList.add('active');
        
        item.addEventListener('mouseenter', function() {
            this.querySelector('.carousel-caption').style.transform = 'translateY(0)';
        });
        
        item.addEventListener('mouseleave', function() {
            if (!this.classList.contains('active')) {
                this.querySelector('.carousel-caption').style.transform = 'translateY(20px)';
            }
        });
    });
    
    // Animate elements when they come into view
    const animateOnScroll = function() {
        const elements = document.querySelectorAll('.about-card, .feature-card, .testimonial');
        
        elements.forEach(element => {
            const elementPosition = element.getBoundingClientRect().top;
            const windowHeight = window.innerHeight;
            
            if (elementPosition < windowHeight - 100) {
                element.style.opacity = '1';
                element.style.transform = 'translateY(0)';
            }
        });
    };
    
    // Set initial state
    document.querySelectorAll('.about-card, .feature-card, .testimonial').forEach(element => {
        element.style.opacity = '0';
        element.style.transform = 'translateY(30px)';
        element.style.transition = 'all 0.6s ease-out';
    });
    
    // Run on load and scroll
    animateOnScroll();
    window.addEventListener('scroll', animateOnScroll);
});