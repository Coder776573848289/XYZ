// Add animation to table rows
document.addEventListener("DOMContentLoaded", function () {
    const rows = document.querySelectorAll("tbody tr");
    rows.forEach((row, index) => {
      row.style.opacity = "0";
      row.style.transform = "translateY(10px)";
      row.style.transition = `all 0.3s ease ${index * 0.05}s`;
  
      setTimeout(() => {
        row.style.opacity = "1";
        row.style.transform = "translateY(0)";
      }, 100);
    });
  });
  
  // Confirm before deleting
  function confirmDelete(flightId) {
    if (
      confirm(
        "Are you sure you want to delete this flight? This action cannot be undone."
      )
    ) {
      // Add loading state
      const btn = document.querySelector(
        `button[onclick="confirmDelete(${flightId})"]`
      );
      btn.innerHTML = '<i class="bi bi-arrow-repeat"></i>';
      btn.disabled = true;
  
      // In a real application, you would make an AJAX call or form submission here
      window.location.href = `delete_flight.php?id=${flightId}`;
    }
  }
  
  // Toast notification function (can be called after actions)
  function showToast(message, type = "success") {
    const toast = document.createElement("div");
    toast.className = `toast-notification ${type}`;
    toast.innerHTML = `
                  <div class="toast-icon">
                      ${
                        type === "success"
                          ? '<i class="bi bi-check-circle-fill"></i>'
                          : '<i class="bi bi-exclamation-triangle-fill"></i>'
                      }
                  </div>
                  <div class="toast-message">${message}</div>
                  <button class="toast-close" onclick="this.parentElement.remove()">
                      <i class="bi bi-x"></i>
                  </button>
              `;
    document.body.appendChild(toast);
  
    setTimeout(() => {
      toast.classList.add("show");
    }, 100);
  
    setTimeout(() => {
      toast.remove();
    }, 5000);
  }
  
  // Example of how to use the toast
  // showToast('Flight deleted successfully!', 'success');
  // showToast('Failed to delete flight', 'error');