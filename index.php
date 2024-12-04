<?php
include 'db.php';

try {
    // Fetch the upcoming 6 events
    $sql = "SELECT id, event_name, event_description FROM events WHERE event_date >= CURDATE() ORDER BY event_date ASC LIMIT 6";
    $result = $conn->query($sql);

    // Check if the query returned results
    if ($result->num_rows > 0) {
        $events = $result->fetch_all(MYSQLI_ASSOC);
    } else {
        $events = []; // No upcoming events
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
    die();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Responsive Navbar and Banner</title>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Responsive Navbar and Banner</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/style.css">
</head>

<body>

    <!-- Header Section -->
    <div>
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
                        <li class="nav-item active">
                            <a class="nav-link" href="index.php">Home</a>
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

        <!-- Banner Section -->
        <section class="banner-section">
            <div class="video-container">
                <video autoplay muted loop class="banner-video" id="bannerVideo">
                    <source src="./images/video.mp4" type="video/mp4">
                    Your browser does not support the video tag.
                </video>
            </div>
            <!-- <div class="banner-content">
        <h1>Welcome to Jyotidham</h1>
        <p>Experience divine spirituality with us</p>
        <a href="#" class="btn btn-primary">Learn More</a> -->
    </div>


    <section id="upcoming-events">
        <div class="container">
            <h1 class="text-left text">Upcoming Events</h1>
            <div class="row">
                <?php foreach ($events as $event): ?>
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="card event-card" style="height: 100%; width: 100%;">
                            <div class="card-body">
                                <h5 class="card-title">
                                    <a href="event-details.php?id=<?= $event['id']; ?>">
                                        <?= strlen($event['event_name']) > 50 ? substr($event['event_name'], 0, 47) . '...' : $event['event_name']; ?>
                                    </a>
                                </h5>
                                <p class="card-text">
                                    <?= strlen($event['event_description']) > 100 ? substr($event['event_description'], 0, 97) . '...' : $event['event_description']; ?>
                                </p>
                                <a href="event.php?id=<?= $event['id']; ?>" class="read">Read More</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="view-div">
                <a href="calender.php" class="view-link">View All</a>
            </div>
        </div>
    </section>

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
                            <p><a href="./admin-login.php">Admin Login</a>
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