<?php
// Include your database connection file
require 'db.php';

// Get the event ID from the URL
if (isset($_GET['id'])) {
    $event_id = $_GET['id'];

    // Prepare and execute the SQL query to fetch event data
    $sql = "SELECT * FROM events WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $event_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if event data is found
    if ($result->num_rows > 0) {
        $event = $result->fetch_assoc();
    } else {
        echo "Event not found.";
        exit;
    }
} else {
    echo "No event ID provided.";
    exit;
}
?>





<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $event['event_name']; ?> - Event Details</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/style.css">
    <link rel="stylesheet" href="./css/event.css">
</head>

<body>

    <!-- Header Section -->
    <header class="header-section">
        <nav class="navbar navbar-expand-lg navbar-light bg-light nav">
            <a class="navbar-brand" href="index.php">
                <img src="./images/logo-dark-bold.png" alt="Jyotidham Logo" class="header-logo">
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item active"><a class="nav-link" href="index.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="live-satsang.html">Live Satsang</a></li>
                    <li class="nav-item"><a class="nav-link" href="donate.html">Donate</a></li>
                    <li class="nav-item"><a class="nav-link" href="calender.php">Calendar</a></li>
                    <li class="nav-item"><a class="nav-link" href="contact.html">Contact</a></li>
                </ul>
            </div>
        </nav>
    </header>

    <!-- Event Details Section -->
    <div class="container px-6">
        <div class="container all-events">
            <a href="calender.php"><< All Events</a>
        </div>
        <div class="container">
            <h1 class="event-heading"><?= $event['event_name']; ?></h1>
            <p><span><?= date('F d, Y', strtotime($event['event_date'])); ?></span> @ 
            <span><?= date('g:i A', strtotime($event['event_time'])); ?> to <?= date('g:i A', strtotime($event['event_end_time'])); ?></span>
            <span><?= $event['time_zone']; ?></span></p>
        </div>
        <div class="container event-description">
            <h3><?= $event['event_description']; ?></h3>
        </div>

        <div class="container">
            <div class="row">
                <!-- Details Column -->
                <div class="col-lg-3 col-md-6 col-sm-12">
                    <h2 class="t-head">Details</h2>
                    <h3 class="t-head">Date:</h3>
                    <p><?= date('F d, Y', strtotime($event['event_date'])); ?></p>
                    <h3 class="t-head">Time:</h3>
                    <p><span><?= date('g:i A', strtotime($event['event_time'])); ?> to <?= date('g:i A', strtotime($event['event_end_time'])); ?></span> <span><?= $event['time_zone']; ?></span></p>
                </div>

                <!-- Organizer Column -->
                <div class="col-lg-3 col-md-6 col-sm-12">
                    <h3 class="t-head">Organizer</h3>
                    <p><?= $event['organizer']; ?></p>
                </div>

                <!-- Venue Column -->
                <div class="col-lg-3 col-md-6 col-sm-12">
                    <h3 class="t-head">Venue</h3>
                    <p><?= $event['event_venue']; ?></p>
                </div>

                <!-- Map Column -->
                <div class="col-lg-3 col-md-6 col-sm-12">
                    <h3 class="t-head">Map Location</h3>
                    <iframe 
                        id="googleMap"
                        src="https://www.google.com/maps/embed/v1/place?key=AIzaSyDP4YgDI3gOakb5Y-kqrCCtCT4M8pj9Mzk&q=<?= $event['latitude']; ?>,<?= $event['longitude']; ?>" 
                        width="100%" 
                        height="250" 
                        style="border:0;" 
                        allowfullscreen="" 
                        loading="lazy" 
                        referrerpolicy="no-referrer-when-downgrade">
                    </iframe>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer Section -->
    <footer class="footer-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 col-md-12 logos">
                    <div class="logo"><img src="./images/logo-jd-light.png" alt="Jyotidham Logo" /></div>
                    <div class="logo"><img src="./images/logo-round-white.png" alt="logo-round-white" /></div>
                </div>

                <div class="col-lg-8 col-md-12 content">
                    <div class="row">
                        <div class="col-12 text-section">
                            <p>After deep prayer and meditation, a devotee is in touch with his divine consciousness; there is no greater power than that inward protection.</p>
                        </div>

                        <div class="col-lg-6 col-md-12 links">
                            <h5>Find Us Here</h5>
                            <p>Shri Param Hans Advait Mat Ontario</p>
                            <p class="address">
                                <img class="map-pin" src="https://jyotidham.ca/wp-content/uploads/2021/06/map-pin.png" alt="Map Pin" />
                                236 Ingleton Blvd, Scarborough,<br>ON M1V 3R1, Canada
                            </p>
                        </div>
                        <div class="col-lg-6 col-md-12 quick-links">
                            <h5>Quick Links</h5>
                            <p><a href="donate.html">Donate</a></p>
                            <p><a href="terms.html">Refund &amp; Privacy Policy</a></p>
                            <p><a href="./admin-login.php">Admin Login</a></p>
                            <p>We accept</p>
                            <img src="./images/payment-cards-updated.png" alt="Payment Cards">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
