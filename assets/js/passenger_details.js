function addPassengerFields(count) {
    const container = document.getElementById("passenger-fields");
    container.innerHTML = "";
  
    if (!count || count < 1) return;
  
    for (let i = 1; i <= count; i++) {
      const fieldset = document.createElement("fieldset");
      fieldset.innerHTML = `
                      <legend>Passenger ${i}</legend>
                      <div class="form-group">
                          <label>Full Name:</label>
                          <input type="text" class="form-control" name="passengers[${i}][name]" required>
                      </div>
                      
                      <div class="form-group">
                          <label>Gender:</label>
                          <select class="form-select" name="passengers[${i}][gender]" required>
                              <option value="">-- Select --</option>
                              <option value="Male">Male</option>
                              <option value="Female">Female</option>
                              <option value="Other">Other</option>
                          </select>
                      </div>
                      
                      <div class="form-group">
                          <label>Age:</label>
                          <input type="number" class="form-control" name="passengers[${i}][age]" min="1" required>
                      </div>
                      
                      <div class="form-group">
                          <label>Seat Number:</label>
                          <select class="form-select" name="passengers[${i}][seat_no]" required>
                              <option value="">-- Select Seat --</option>
                              <option>Standard Window Seat</option>
                              <option>Exit Row Window Seat</option>
                              <option>Bulkhead Window Seat</option>
                              <option>Window Seat Over the Wing</option>
                              <option>Rearmost Window Seat</option>
                          </select>
                      </div>
                  `;
  
      // Add animation to each new fieldset
      fieldset.style.opacity = "0";
      fieldset.style.transform = "translateY(20px)";
      container.appendChild(fieldset);
  
      // Animate in
      setTimeout(() => {
        fieldset.style.transition = "all 0.4s ease-out";
        fieldset.style.opacity = "1";
        fieldset.style.transform = "translateY(0)";
      }, 100 * i);
    }
  }
  
  // Form submission handling
  document.getElementById("bookingForm").addEventListener("submit", function (e) {
    e.preventDefault();
  
    // Show success message
    const successMessage = document.getElementById("successMessage");
    successMessage.style.display = "block";
  
    // In a real application, you would submit the form here
    // For demo purposes, we'll simulate a delay before submission
    setTimeout(() => {
      this.submit();
    }, 2000);
  });