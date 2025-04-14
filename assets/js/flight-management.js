// Function to handle status badge colors
function getStatusBadgeClass(status) {
    switch (status.toLowerCase()) {
      case "active":
        return "active";
      case "inactive":
        return "inactive";
      case "pending":
        return "pending";
      case "scheduled":
        return "scheduled";
      default:
        return "secondary";
    }
  }
  
  // Make table rows clickable (optional)
  document.addEventListener("DOMContentLoaded", function () {
    const rows = document.querySelectorAll("tbody tr[data-href]");
  
    rows.forEach((row) => {
      row.addEventListener("click", function () {
        window.location.href = this.dataset.href;
      });
  
      // Change cursor on hover
      row.style.cursor = "pointer";
    });
  
    // Add responsive data labels
    if (window.innerWidth <= 992) {
      const headers = document.querySelectorAll("thead th");
      const cells = document.querySelectorAll("tbody td");
  
      headers.forEach((header, index) => {
        cells.forEach((cell) => {
          if (cell.cellIndex === index) {
            cell.setAttribute("data-label", header.textContent);
          }
        });
      });
    }
  });
  
  // Delete confirmation
  button.addEventListener("click", (e) => {
    if (!confirm("Are you sure...")) {
      e.preventDefault();
    }
  });