<?php
// Connect to the database
include 'db.php';

// Define how many events to show per page (2 months of events)
$months_to_show = 2;

// Determine the current page and offset for pagination
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $months_to_show;

// Fetch events grouped by month and sorted by date
$sql = "
    SELECT 
    day, 
    event_date, 
    DATE_FORMAT(event_date, '%Y-%m') AS event_month, 
    event_name, 
    event_description, 
    event_time, 
    event_end_time,
    time_zone,
    event_venue, 
    is_featured
FROM events
WHERE event_date >= CURDATE()
ORDER BY event_date ASC"; 

$result = $conn->query($sql);

// Array to store grouped events by month
$events_by_month = [];

// Loop through fetched events and group by month
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $month = $row['event_month'];
        if (!isset($events_by_month[$month])) {
            $events_by_month[$month] = [];
        }
        $events_by_month[$month][] = $row;
    }
}

// Close the connection
$conn->close();
?>






<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calender</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/style.css">
    <link rel="stylesheet" href="./css/calender.css">
</head>

<body>
    <div>
        <header class="header-section">
            <nav class="navbar navbar-expand-lg navbar-light bg-light nav">
                <a class="navbar-brand" href="index.html">
                    <img src="./images/logo-dark-bold.png" alt="Jyotidham Logo" class="header-logo">
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
                    aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item active">
                            <a class="nav-link" href="index.html">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="live-satsang.html">Live Satsang</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="donate.html">Donate</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="calender.php">Calender</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="contact.html">Contact</a>
                        </li>
                    </ul>
                </div>
            </nav>
        </header>

        <div class="container">
                <?php foreach ($events_by_month as $month => $events): ?>
                    <h2 class="month-heading">
                        <time class="month-time" datetime="<?= $month; ?>">
                            <?= date('F Y', strtotime($month . "-01")); ?>
                        </time>
                    </h2>

                    <?php foreach ($events as $event): ?>
                        <div class="event-container">
                            <div class="event-date">
                            <span class="day"><?= strtoupper(substr($event['day'], 0, 3)); ?></span>
                            
                            <span class="date"><?= date('d', strtotime($event['event_date'])); ?></span>

                            </div>

                            <div class="event-details">
                                <div class="event-header">
                                        <?php if ($event['is_featured']): ?>
                                            <span class="featured-icon">
                                                <svg width="15px" height="15px" viewBox="0 0 8 10" xmlns="http://www.w3.org/2000/svg">
                                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M0 0h8v10L4.049 7.439 0 10V0z"></path>
                                                </svg>
                                                <span class="featured-text">Featured</span>
                                            </span>
                                        <?php endif; ?>
                                        <span class="event-time"><?= $event['event_date']; ?> @ <?= date('H:i', strtotime($event['event_time'])); ?> <?= date('H:i', strtotime($event['event_end_time'])); ?> <?= $event['time_zone']; ?></span>
                                </div>

                                <h3 class="event-title">
                                    <a href="#" class="event-link"><?= $event['event_name']; ?></a>
                                </h3>

                                <address class="event-venue"><?= $event['event_venue']; ?></address>
                        

                                <div class="event-description">
                                    <p><?= substr($event['event_description'], 0, 200); ?><?php if (strlen($event['event_description']) > 200) echo '...'; ?></p>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endforeach; ?>
        </div>
        <!-- Pagination Links -->
        



    </div>

    <footer class="footer-section">
        <div class="container">
            <div class="row">
                <!-- Left Side: Logos -->
                <div class="col-lg-4 col-md-12 logos">
                    <div class="logo">
                        <img src="./images/logo-jd-light.png" alt="Jyotidham Logo" />
                    </div>
                    <div class="logo">
                        <img src="./images/logo-round-white.png" alt="logo-round-white" />
                    </div>
                </div>

                <!-- Right Side: Content and Links -->
                <div class="col-lg-8 col-md-12 content">
                    <div class="row">
                        <!-- Text Section -->
                        <div class="col-12 text-section">
                            <p>After deep prayer and meditation, a devotee is in touch with his divine
                                consciousness; there is no greater power than that inward protection.</p>
                        </div>

                        <!-- Two Columns -->
                        <div class="col-lg-6 col-md-12 links">
                            <h5>Find Us Here</h5>
                            <p>Shri Param Hans Advait Mat Ontario</p>
                            <p class="address">
                                <img class="map-pin" src="https://jyotidham.ca/wp-content/uploads/2021/06/map-pin.png"
                                    alt="Map Pin" />
                                236 Ingleton Blvd, Scarborough,<br>
                                ON M1V 3R1, Canada
                            </p>
                        </div>
                        <div class="col-lg-6 col-md-12 quick-links">
                            <h5>Quick Links</h5>
                            <p><a href="donate.html">Donate</a></p>
                            <p><a href="terms.html">Refund &amp; Privacy Policy</a>
                            </p>
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