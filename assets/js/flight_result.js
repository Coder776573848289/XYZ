document.addEventListener("DOMContentLoaded", function () {
    // Add animation to flight cards on load
    const flightCards = document.querySelectorAll(".flight-card");
    flightCards.forEach((card, index) => {
      card.style.animationDelay = `${index * 0.1}s`;
      card.style.opacity = "1";
    });
  
    // Filter options functionality
    const filterOptions = document.querySelectorAll(".filter-option");
    filterOptions.forEach((option) => {
      option.addEventListener("click", function () {
        if (!this.classList.contains("active") && !this.tagName === "SELECT") {
          document
            .querySelector(".filter-option.active")
            .classList.remove("active");
          this.classList.add("active");
        }
      });
    });
  
    // Add click animation to book buttons
    const bookButtons = document.querySelectorAll(".book-btn");
    bookButtons.forEach((button) => {
      button.addEventListener("click", function (e) {
        // Add ripple effect
        const ripple = document.createElement("span");
        ripple.classList.add("ripple-effect");
        this.appendChild(ripple);
  
        // Get click position
        const rect = this.getBoundingClientRect();
        const x = e.clientX - rect.left;
        const y = e.clientY - rect.top;
  
        // Position ripple
        ripple.style.left = `${x}px`;
        ripple.style.top = `${y}px`;
  
        // Remove ripple after animation
        setTimeout(() => {
          ripple.remove();
        }, 600);
      });
    });
  
    // Simulate price changes
    setInterval(() => {
      const priceTrends = document.querySelectorAll(".price-trend");
      priceTrends.forEach((trend) => {
        const change = Math.random() > 0.5 ? 1 : -1;
        const percent = Math.floor(Math.random() * 8) + 1;
  
        if (change > 0) {
          trend.innerHTML = `<i class="fas fa-arrow-up"></i> ${percent}%`;
          trend.className = "price-trend trend-up";
        } else {
          trend.innerHTML = `<i class="fas fa-arrow-down"></i> ${percent}%`;
          trend.className = "price-trend trend-down";
        }
      });
    }, 5000);
  });