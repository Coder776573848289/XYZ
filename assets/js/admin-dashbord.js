document.addEventListener("DOMContentLoaded", function () {
    // Toggle sidebar on mobile (optional)
    const sidebarToggle = document.createElement("div");
    sidebarToggle.className = "sidebar-toggle";
    sidebarToggle.innerHTML = '<i class="fas fa-bars"></i>';
    document.querySelector(".admin-header").prepend(sidebarToggle);
  
    sidebarToggle.addEventListener("click", function () {
      document.querySelector(".admin-sidebar").classList.toggle("active");
    });
  
    // Update dashboard stats (example with AJAX)
    function updateDashboardStats() {
      // In a real application, you would fetch this data from your server
      fetch("api/dashboard-stats.php")
        .then((response) => response.json())
        .then((data) => {
          // Update the cards with real data
          document.querySelector(".card:nth-child(1) p").textContent =
            data.totalFlights;
          document.querySelector(".card:nth-child(2) p").textContent =
            data.todayBookings;
          document.querySelector(".card:nth-child(3) p").textContent =
            data.pendingPayments;
          document.querySelector(".card:nth-child(4) p").textContent =
            data.activeUsers;
        })
        .catch((error) =>
          console.error("Error fetching dashboard stats:", error)
        );
    }
  
    // Call the function (you might want to call this periodically)
    // updateDashboardStats();
  
    // Add active class to current page in menu
    const currentPage = window.location.pathname.split("/").pop();
    document.querySelectorAll(".admin-menu a").forEach((link) => {
      const linkPage = link.getAttribute("href");
      if (linkPage === currentPage) {
        link.parentElement.classList.add("active");
      } else {
        link.parentElement.classList.remove("active");
      }
    });
  });