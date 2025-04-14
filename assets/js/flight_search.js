document.addEventListener("DOMContentLoaded", function () {
    // Toggle return date based on trip type
    const tripTypeRadios = document.querySelectorAll('input[name="trip_type"]');
    const returnDateInput = document.getElementById("return_date");
  
    function toggleReturnDate() {
      const isRoundTrip =
        document.querySelector('input[name="trip_type"]:checked').value ===
        "round";
      returnDateInput.disabled = !isRoundTrip;
      if (!isRoundTrip) {
        returnDateInput.value = "";
      }
    }
  
    tripTypeRadios.forEach((radio) => {
      radio.addEventListener("change", toggleReturnDate);
    });
  
    // Swap locations
    document
      .getElementById("swapLocations")
      .addEventListener("click", function () {
        const fromSelect = document.getElementById("from_location");
        const toSelect = document.getElementById("to_location");
        const tempValue = fromSelect.value;
        fromSelect.value = toSelect.value;
        toSelect.value = tempValue;
      });
  
    // Form validation
    document
      .getElementById("flightForm")
      .addEventListener("submit", function (e) {
        let isValid = true;
  
        // Reset error messages
        document.querySelectorAll(".error-message").forEach((el) => {
          el.style.display = "none";
        });
  
        // Validate from and to locations
        const fromLocation = document.getElementById("from_location").value;
        const toLocation = document.getElementById("to_location").value;
  
        if (!fromLocation) {
          document.getElementById("from-error").textContent =
            "Please select departure city";
          document.getElementById("from-error").style.display = "block";
          isValid = false;
        }
  
        if (!toLocation) {
          document.getElementById("to-error").textContent =
            "Please select destination city";
          document.getElementById("to-error").style.display = "block";
          isValid = false;
        }
  
        if (fromLocation && toLocation && fromLocation === toLocation) {
          document.getElementById("from-error").textContent =
            "Departure and destination must be different";
          document.getElementById("to-error").textContent =
            "Departure and destination must be different";
          document.getElementById("from-error").style.display = "block";
          document.getElementById("to-error").style.display = "block";
          isValid = false;
        }
  
        // Validate dates
        const departureDate = document.getElementById("departure").value;
        const returnDate = document.getElementById("return_date").value;
        const isRoundTrip =
          document.querySelector('input[name="trip_type"]:checked').value ===
          "round";
  
        if (!departureDate) {
          document.getElementById("departure-error").textContent =
            "Please select departure date";
          document.getElementById("departure-error").style.display = "block";
          isValid = false;
        }
  
        if (isRoundTrip && !returnDate) {
          document.getElementById("return-error").textContent =
            "Please select return date";
          document.getElementById("return-error").style.display = "block";
          isValid = false;
        }
  
        if (
          departureDate &&
          returnDate &&
          new Date(returnDate) < new Date(departureDate)
        ) {
          document.getElementById("return-error").textContent =
            "Return date must be after departure";
          document.getElementById("return-error").style.display = "block";
          isValid = false;
        }
  
        // Validate price range if both are entered
        const minPrice = document.getElementById("min_price").value;
        const maxPrice = document.getElementById("max_price").value;
  
        if (minPrice && maxPrice && parseInt(minPrice) > parseInt(maxPrice)) {
          alert("Maximum price must be greater than minimum price");
          isValid = false;
        }
  
        if (!isValid) {
          e.preventDefault();
  
          // Add shake animation to invalid fields
          const invalidFields = document.querySelectorAll(
            '.error-message[style*="display: block"]'
          );
          invalidFields.forEach((error) => {
            const input = error.previousElementSibling;
            if (input) {
              input.classList.add("is-invalid");
              input.parentElement.classList.add(
                "animate__animated",
                "animate__headShake"
              );
              setTimeout(() => {
                input.parentElement.classList.remove(
                  "animate__animated",
                  "animate__headShake"
                );
              }, 1000);
            }
          });
        }
      });
  
    // Initialize date inputs with min date as today
    const today = new Date().toISOString().split("T")[0];
    document.getElementById("departure").min = today;
    document.getElementById("return_date").min = today;
  
    // Update return date min date when departure date changes
    document.getElementById("departure").addEventListener("change", function () {
      const departureDate = this.value;
      if (departureDate) {
        document.getElementById("return_date").min = departureDate;
      }
    });
  
    // Add animation to form elements on load
    setTimeout(() => {
      const formGroups = document.querySelectorAll(".form-group");
      formGroups.forEach((group) => {
        group.style.opacity = "1";
      });
    }, 100);
  });