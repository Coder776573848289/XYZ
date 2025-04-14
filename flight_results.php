<?php
// flight_results.php
require_once 'includes/db.php';

// Read form data using POST
$tripType = $_POST['trip_type'] ?? 'oneway';
$from = $_POST['from_location'] ?? '';
$to = $_POST['to_location'] ?? '';
$departure = $_POST['departure'] ?? '';
$return = $_POST['return_date'] ?? '';
$seatClass = $_POST['seat_class'] ?? '';
$minPrice = $_POST['min_price'] ?? '0';
$maxPrice = $_POST['max_price'] ?? '999999';

// Remove commas from price inputs (important)
$minPrice = floatval(str_replace(',', '', $minPrice));
$maxPrice = floatval(str_replace(',', '', $maxPrice));

// Convert dates
$departureDate = !empty($departure) ? date('Y-m-d', strtotime($departure)) : '';
$returnDate = !empty($return) ? date('Y-m-d', strtotime($return)) : '';

// Start SQL and params
$sql = "SELECT * FROM flight WHERE from_location = ? AND to_location = ?";
$paramTypes = 'ss';
$params = [$from, $to];

// Add filters
if ($tripType === 'oneway' && $departureDate) {
  $sql .= " AND DATE(departure) = ?";
  $paramTypes .= 's';
  $params[] = $departureDate;
} elseif ($tripType === 'round' && $departureDate && $returnDate) {
  $sql .= " AND DATE(departure) = ? AND DATE(arrival) <= ?";
  $paramTypes .= 'ss';
  $params[] = $departureDate;
  $params[] = $returnDate;
}

// Price filter
if ($seatClass === 'economy') {
  $sql .= " AND economy_price BETWEEN ? AND ?";
  $paramTypes .= 'dd';
  $params[] = $minPrice;
  $params[] = $maxPrice;
} elseif ($seatClass === 'business') {
  $sql .= " AND business_price BETWEEN ? AND ?";
  $paramTypes .= 'dd';
  $params[] = $minPrice;
  $params[] = $maxPrice;
} elseif ($seatClass === 'first') {
  $sql .= " AND first_class_price BETWEEN ? AND ?";
  $paramTypes .= 'dd';
  $params[] = $minPrice;
  $params[] = $maxPrice;
} else {
  $sql .= " AND (
        economy_price BETWEEN ? AND ? OR
        business_price BETWEEN ? AND ? OR
        first_class_price BETWEEN ? AND ?
    )";
  $paramTypes .= 'dddddd';
  $params = array_merge($params, [$minPrice, $maxPrice, $minPrice, $maxPrice, $minPrice, $maxPrice]);
}

// Prepare and execute

$stmt = $conn->prepare($sql);
$stmt->bind_param($paramTypes, ...$params);
$stmt->execute();
$result = $stmt->get_result();
$matchedFlights = $result->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8">
  <title>Flight Search Results</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <!-- Animate.css -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
  <link rel="stylesheet" href="assets/css/flight_result.css">
</head>

<body>
  <div class="results-container">
    <div class="results-header animate__animated animate__fadeIn">
      <h2><i class="fas fa-plane me-2"></i>Flight Search Results</h2>
      <!-- <p>We found the best options for your journey from <?= $from_location ?> to <?= $to_location ?></p> -->
    </div>

    <!-- <div class="filter-bar animate__animated animate__fadeIn">
      <span class="filter-label">Filter by:</span>
      <span class="filter-option active">All</span>
      <span class="filter-option">Non-stop</span>
      <span class="filter-option">Morning</span>
      <span class="filter-option">Afternoon</span>
      <span class="filter-option">Evening</span>

      <div class="sort-options">
        <span class="filter-label">Sort by:</span>
        <select class="filter-option" style="padding: 8px 15px;">
          <option>Price (Low to High)</option>
          <option>Price (High to Low)</option>
          <option>Duration (Shortest)</option>
          <option>Departure (Earliest)</option>
        </select>
      </div>
    </div> -->

    <?php if (!empty($matchedFlights)): ?>
      <?php foreach ($matchedFlights as $index => $flight): ?>
        <div class="flight-card animate__animated" style="animation-delay: <?= $index * 0.1 ?>s">
          <div class="flight-header">
            <div class="airline-name">
              <div class="airline-logo">
                <?= substr($flight['airline_name'], 0, 1) ?>
              </div>
              <?= htmlspecialchars($flight['airline_name']) ?>
              <span class="price-trend trend-down">
                <i class="fas fa-arrow-down"></i> 12%
              </span>
            </div>
            <div class="flight-status">
              <i class="fas fa-check-circle me-1"></i> <?= $flight['status'] ?>
            </div>
          </div>

          <div class="flight-body">
            <div class="route-info">
              <div class="location">
                <div class="location-code"><?= $flight['from_location'] ?></div>
                <!-- <div class="location-name"><?= $flight['departure_airport'] ?></div> -->
              </div>

              <div class="route-line"></div>

              <div class="location">
                <div class="location-code"><?= $flight['to_location'] ?></div>
                <!-- <div class="location-name"><?= $flight['arrival_airport'] ?></div> -->
              </div>
            </div>

            <div class="time-info">
              <div class="time-block">
                <div class="time-label">Departure</div>
                <div class="time-value"><?= date("H:i", strtotime($flight['departure'])) ?></div>
                <div class="time-date"><?= date("d M Y", strtotime($flight['departure'])) ?></div>
              </div>

              <div class="time-block">
                <div class="time-label">Arrival</div>
                <div class="time-value"><?= date("H:i", strtotime($flight['arrival'])) ?></div>
                <div class="time-date"><?= date("d M Y", strtotime($flight['arrival'])) ?></div>
              </div>

              <div class="time-block">
                <div class="time-label">Flight No.</div>
                <!-- <div class="time-value"><?= $flight['flight_number'] ?></div> -->
                <!-- <div class="time-date"><?= $flight['aircraft_type'] ?></div> -->
              </div>
            </div>

            <div class="duration">
              <i class="fas fa-clock"></i> <?= floor($flight['duration'] / 60) ?>h <?= $flight['duration'] % 60 ?>m total
              duration
            </div>

            <div class="seats-info">
              <div class="seat-class">
                <div class="class-name">
                  <i class="fas fa-chair"></i> Economy
                </div>
                <div class="class-price">₹<?= number_format($flight['economy_price']) ?> <small>+ taxes</small></div>
                <div class="class-availability">
                  <span
                    class="availability-dot <?= $flight['economy_seats'] < 10 ? 'low' : ($flight['economy_seats'] < 30 ? 'limited' : '') ?>"></span>
                  <?= $flight['economy_seats'] ?> seats left
                </div>
              </div>

              <div class="seat-class popular">
                <div class="class-name">
                  <i class="fas fa-briefcase"></i> Business
                </div>
                <div class="class-price">₹<?= number_format($flight['business_price']) ?> <small>+ taxes</small></div>
                <div class="class-availability">
                  <span
                    class="availability-dot <?= $flight['business_seats'] < 5 ? 'low' : ($flight['business_seats'] < 15 ? 'limited' : '') ?>"></span>
                  <?= $flight['business_seats'] ?> seats left
                </div>
              </div>

              <div class="seat-class">
                <div class="class-name">
                  <i class="fas fa-crown"></i> First Class
                </div>
                <div class="class-price">₹<?= number_format($flight['first_class_price']) ?> <small>+ taxes</small></div>
                <div class="class-availability">
                  <span
                    class="availability-dot <?= $flight['first_class_seats'] < 3 ? 'low' : ($flight['first_class_seats'] < 8 ? 'limited' : '') ?>"></span>
                  <?= $flight['first_class_seats'] ?> seats left
                </div>
              </div>
            </div>

            <a href="passenger_details.php?flight_id=<?= $flight['id'] ?>" class="book-btn">
              <i class="fas fa-ticket-alt me-2"></i> Book Now
              <span class="badge bg-white text-dark ms-2">Save
                ₹<?= number_format($flight['business_price'] * 0.12) ?></span>
            </a>
          </div>
        </div>
      <?php endforeach; ?>
    <?php else: ?>
      <div class="no-results animate__animated animate__fadeIn">
        <div class="no-results-icon">
          <i class="fas fa-plane-slash"></i>
        </div>
        <h3>No Flights Found</h3>
        <!-- <p>We couldn't find any flights matching your search criteria from <?= $from_location ?> to <?= $to_location ?> on -->
          <!-- <?= $departure_date ?>. Please try adjusting your search parameters or try different dates. -->
        </p>
        <a href="flight_search.php" class="back-link">
          <i class="fas fa-arrow-left"></i> Modify Search
        </a>
      </div>
    <?php endif; ?>

    <?php if (!empty($matchedFlights)): ?>
      <div class="text-center">
        <a href="flight_search.php" class="back-link">
          <i class="fas fa-arrow-left"></i> Back to Search
        </a>
      </div>
    <?php endif; ?>
  </div>

  <!-- Bootstrap JS Bundle with Popper -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="assets/js/flight_result.js"></script>
</body>

</html>