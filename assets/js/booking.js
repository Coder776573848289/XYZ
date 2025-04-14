document.addEventListener("DOMContentLoaded", function () {
    // Add animation to table rows
    const tableRows = document.querySelectorAll(".booking-table tbody tr");
    tableRows.forEach((row, index) => {
      row.style.opacity = "0";
      row.style.transform = "translateY(20px)";
      row.style.transition = `all 0.3s ease ${index * 0.05}s`;
  
      // Trigger the animation
      setTimeout(() => {
        row.style.opacity = "1";
        row.style.transform = "translateY(0)";
      }, 100);
    });
  
    // Add confirmation for reset button
    const resetBtn = document.querySelector(".btn-reset");
    if (resetBtn) {
      resetBtn.addEventListener("click", function (e) {
        if (!confirm("Are you sure you want to reset all filters?")) {
          e.preventDefault();
        }
      });
    }
  
    // Add tooltips to form elements
    const formInputs = document.querySelectorAll(
      ".filter-form input, .filter-form select"
    );
    formInputs.forEach((input) => {
      input.addEventListener("focus", function () {
        this.style.borderColor = "var(--color-accent)";
        this.style.boxShadow = "0 0 0 0.25rem rgba(90, 141, 168, 0.25)";
      });
  
      input.addEventListener("blur", function () {
        this.style.borderColor = "";
        this.style.boxShadow = "";
      });
    });
  });