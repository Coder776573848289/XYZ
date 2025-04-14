<?php
// search_flights.php
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Search Flights</title>


    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/flight_search.css">
</head>

<body>
    <div class="container">
        <div class="search-container">
            <div class="search-header">
                <h2><i class="fas fa-plane me-2"></i>Search Flights</h2>
                <p class="text-muted">Find the best flights for your journey</p>
            </div>

            <form method="POST" action="flight_results.php" id="flightForm" novalidate>
                <div class="form-group mb-4">
                    <label class="form-label">Trip Type:</label>
                    <div class="trip-type">
                        <label>
                            <input type="radio" name="trip_type" value="oneway" checked class="me-2">
                            <i class="fas fa-arrow-right me-1"></i> One Way
                        </label>
                        <label>
                            <input type="radio" name="trip_type" value="round" class="me-2">
                            <i class="fas fa-exchange-alt me-1"></i> Round Trip
                        </label>
                    </div>
                </div>

                <div class="form-group mb-4 location-fields">
                    <div class="row g-3">
                        <div class="col-md-5 position-relative">
                            <label for="from_location" class="form-label">From:</label>
                            <select name="from_location" id="from_location" class="form-select" required>
                                <option value="">-- Select City --</option>
                                <option value="DEL">Delhi (DEL)</option>
                                <option value="BOM">Mumbai (BOM)</option>
                                <option value="BLR">Bangalore (BLR)</option>
                                <option value="HYD">Hyderabad (HYD)</option>
                                <option value="MAA">Chennai (MAA)</option>
                                <option value="CCU">Kolkata (CCU)</option>
                            </select>
                            <i class="fas fa-map-marker-alt form-icon"></i>
                            <div class="error-message" id="from-error"></div>
                        </div>

                        <div class="col-md-2 position-relative">
                            <button type="button" class="swap-btn" id="swapLocations">
                                <i class="fas fa-exchange-alt"></i>
                            </button>
                        </div>

                        <div class="col-md-5 position-relative">
                            <label for="to_location" class="form-label">To:</label>
                            <select name="to_location" id="to_location" class="form-select" required>
                                <option value="">-- Select City --</option>
                                <option value="DEL">Delhi (DEL)</option>
                                <option value="BOM">Mumbai (BOM)</option>
                                <option value="BLR">Bangalore (BLR)</option>
                                <option value="HYD">Hyderabad (HYD)</option>
                                <option value="MAA">Chennai (MAA)</option>
                                <option value="CCU">Kolkata (CCU)</option>
                            </select>
                            <i class="fas fa-map-marker-alt form-icon"></i>
                            <div class="error-message" id="to-error"></div>
                        </div>
                    </div>
                </div>

                <div class="form-group mb-4 date-fields">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="departure" class="form-label">Departure Date:</label>
                            <div class="position-relative">
                                <input type="date" name="departure" id="departure" class="form-control" required>
                                <i class="fas fa-calendar-alt form-icon"></i>
                                <div class="error-message" id="departure-error"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="return_date" class="form-label">Return Date:</label>
                            <div class="position-relative">
                                <input type="date" name="return_date" id="return_date" class="form-control" disabled>
                                <i class="fas fa-calendar-alt form-icon"></i>
                                <div class="error-message" id="return-error"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group mb-4">
                    <label for="seat_class" class="form-label">Seat Class:</label>
                    <select name="seat_class" id="seat_class" class="form-select">
                        <option value="">Any Class</option>
                        <option value="economy">Economy</option>
                        <option value="business">Business</option>
                        <option value="first">First Class</option>
                    </select>
                </div>

                <div class="form-group mb-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="min_price" class="form-label">Min Price (₹):</label>
                            <div class="input-group">
                                <span class="input-group-text">₹</span>
                                <input type="number" name="min_price" id="min_price" class="form-control" min="0"
                                    placeholder="0">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="max_price" class="form-label">Max Price (₹):</label>
                            <div class="input-group">
                                <span class="input-group-text">₹</span>
                                <input type="number" name="max_price" id="max_price" class="form-control" min="0"
                                    placeholder="Any">
                            </div>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-search">
                    <i class="fas fa-search me-2"></i> Search Flights
                </button>
            </form>
        </div>
    </div>

    <script src="assets/js/flight_search.js"></script>
</body>

</html>