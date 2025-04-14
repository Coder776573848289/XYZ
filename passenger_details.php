<?php
session_start();
$flight_id = isset($_GET['flight_id']) ? intval($_GET['flight_id']) : 0;
if ($flight_id <= 0) {
    die("Invalid Flight Selection");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Passenger Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --navy-blue: #003366;
            --gold: #FFD700;
            --deep-red: #8B0000;
            --teal: #008080;
            --off-white: #F5F5F5;
            --text-dark: #2a3a4a;
            --transition-speed: 0.3s;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, var(--off-white) 0%, #e0e0e0 100%);
            color: var(--text-dark);
            padding: 20px;
            min-height: 100vh;
        }

        .container {
            max-width: 900px;
            margin: 30px auto;
            animation: fadeIn 0.5s ease-out;
        }

        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            background: white;
            transition: transform var(--transition-speed) ease;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        .card-header {
            background: var(--navy-blue);
            color: white;
            padding: 20px;
            position: relative;
        }

        .card-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--gold) 0%, var(--teal) 100%);
        }

        .form-control {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 12px 15px;
            transition: all var(--transition-speed) ease;
            width: 100%;
        }

        .form-control:focus {
            border-color: var(--navy-blue);
            box-shadow: 0 0 0 3px rgba(0, 51, 102, 0.2);
            outline: none;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--navy-blue) 0%, var(--teal) 100%);
            border: none;
            padding: 12px 30px;
            border-radius: 8px;
            color: white;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all var(--transition-speed) ease;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 51, 102, 0.3);
        }

        fieldset {
            border: 1px solid #e0e0e0;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 25px;
            position: relative;
            overflow: hidden;
        }

        fieldset::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 5px;
            height: 100%;
            background: linear-gradient(to bottom, var(--navy-blue), var(--teal));
        }

        legend {
            padding: 0 10px;
            font-weight: 600;
            color: var(--navy-blue);
        }

        .success-message {
            background-color: var(--teal);
            color: white;
            padding: 15px;
            border-radius: 8px;
            margin-top: 20px;
            text-align: center;
            animation: slideUp 0.5s ease-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .passenger-count select {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='%23003366' d='M7.247 11.14L2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right 0.75rem center;
            background-size: 16px 12px;
            appearance: none;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .invalid {
            border-color: var(--deep-red) !important;
            animation: shake 0.5s;
        }

        @keyframes shake {

            0%,
            100% {
                transform: translateX(0);
            }

            25% {
                transform: translateX(5px);
            }

            75% {
                transform: translateX(-5px);
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h2 class="mb-0">Passenger Details</h2>
            </div>
            <div class="card-body">
                <form method="POST" action="confirm_booking.php" id="bookingForm">
                    <input type="hidden" name="flight_id" value="<?= $flight_id ?>">

                    <div class="form-group passenger-count">
                        <label for="num_passengers">Number of Passengers:</label>
                        <select class="form-select" id="num_passengers" name="num_passengers"
                            onchange="addPassengerFields(this.value)" required>
                            <option value="">-- Select --</option>
                            <?php for ($i = 1; $i <= 6; $i++): ?>
                                <option value="<?= $i ?>"><?= $i ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>

                    <div id="passenger-fields"></div>

                    <div class="form-group">
                        <label for="class_type">Seat Class:</label>
                        <select class="form-select" id="class_type" name="class_type" required>
                            <option value="">-- Select --</option>
                            <option value="economy">Economy</option>
                            <option value="business">Business</option>
                            <option value="first">First</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary">Confirm Booking</button>

                    <div id="successMessage" class="success-message">
                        Booking confirmed! Redirecting to payment...
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function addPassengerFields(count) {
            const container = document.getElementById('passenger-fields');
            container.innerHTML = '';

            if (!count || count < 1) return;

            for (let i = 1; i <= count; i++) {
                const fieldset = document.createElement('fieldset');
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
                fieldset.style.opacity = '0';
                fieldset.style.transform = 'translateY(20px)';
                container.appendChild(fieldset);

                // Animate in
                setTimeout(() => {
                    fieldset.style.transition = 'all 0.4s ease-out';
                    fieldset.style.opacity = '1';
                    fieldset.style.transform = 'translateY(0)';
                }, 100 * i);
            }
        }

        // Form submission handling
        document.getElementById('bookingForm').addEventListener('submit', function (e) {
            e.preventDefault();

            // Show success message
            const successMessage = document.getElementById('successMessage');
            successMessage.style.display = 'block';

            // In a real application, you would submit the form here
            // For demo purposes, we'll simulate a delay before submission
            setTimeout(() => {
                this.submit();
            }, 2000);
        });



        // ///////////////////////


        // Dynamic seat map generation
        function generateSeatMap(passengers) {
            const seatMap = document.createElement('div');
            seatMap.className = 'seat-map';

            // Example seat layout
            const seatTypes = ['window', 'middle', 'aisle', 'middle'];
            for (let i = 1; i <= passengers * 2; i++) {
                const seat = document.createElement('div');
                seat.className = `seat ${seatTypes[i % 4]}`;
                seat.textContent = `Seat ${i}`;
                seat.onclick = function () {
                    this.classList.toggle('selected');
                    updateSeatPreview();
                };
                seatMap.appendChild(seat);
            }
            document.getElementById('seatPreview').appendChild(seatMap);
        }

        // Ripple effect for button
        document.querySelector('.ripple').addEventListener('click', function (e) {
            let ripple = document.createElement('div');
            ripple.className = 'ripple-effect';
            ripple.style.left = e.clientX - this.offsetLeft + 'px';
            ripple.style.top = e.clientY - this.offsetTop + 'px';
            this.appendChild(ripple);
            setTimeout(() => ripple.remove(), 600);
        });

        // Form validation
        function validateForm() {
            let isValid = true;
            document.querySelectorAll('.form-control').forEach(input => {
                if (!input.value) {
                    input.classList.add('invalid');
                    isValid = false;
                }
            });
            return isValid;
        }

        // Initialize animations
        document.querySelectorAll('.form-group').forEach((el, i) => {
            el.style.animation = `fadeIn 0.5s ease-out ${i * 0.1}s both`;
        });
    </script>
</body>

</html>