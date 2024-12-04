<?php
// Connect to the database
include 'db.php';

// Define the number of events per page
$events_per_page = 20;

// Get the current page from the request or default to 1
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $events_per_page;

// Fetch events for the current page
$sql = "
    SELECT 
        id, 
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
    ORDER BY event_date ASC
    LIMIT $events_per_page OFFSET $offset";

$result = $conn->query($sql);


// Prepare the events data
$events = [];
while ($row = $result->fetch_assoc()) {
    $events[] = $row;
}


$has_more_events_sql = "
    SELECT 1 
    FROM events 
    WHERE event_date >= CURDATE()
    ORDER BY event_date ASC
    LIMIT 1 OFFSET " . ($offset + $events_per_page);

$has_more_events = $conn->query($has_more_events_sql)->num_rows > 0;

// Return JSON response if it's an AJAX request
if (isset($_GET['ajax']) && $_GET['ajax'] === '1') {
    echo json_encode([
        'events' => $events,
        'has_next' => $has_more_events, // Indicates if the next page is available
        'has_prev' => $page > 1         // Indicates if the previous page is available
    ]);
    exit;
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

        <div class="container">
            <!-- Event container to dynamically update -->
            <div id="event-container">
                <!-- This will be dynamically populated -->
            </div>

            <!-- Pagination Links -->
            <div id="pagination" class="text-center">
                <button id="prev-page" class="btn btn-primary" style="display: none;">Previous</button>
                <button id="next-page" class="btn btn-primary" style="display: none;">Next</button>
            </div>

        </div>
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
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            let currentPage = 1;

            // Function to fetch events and update the DOM
            function fetchEvents(page) {
                fetch(`calender.php?page=${page}&ajax=1`)
                    .then(response => response.json())
                    .then(data => {
                        const eventContainer = document.getElementById("event-container");
                        eventContainer.innerHTML = ""; // Clear existing events

                        if (data.events.length === 0) {
                            eventContainer.innerHTML = "<p>No more events available.</p>";
                        } else {
                            data.events.forEach(event => {
                                const eventHTML = `
                                    <div class="event-container">
                                        <div class="event-date">
                                            <span class="day">${event.day.substring(0, 3).toUpperCase()}</span>
                                            <span class="date">${new Date(event.event_date).getDate()}</span>
                                        </div>
                                        <div class="event-details">
                                            <div class="event-header">
                                                ${event.is_featured ? `
                                                <span class="featured-icon">
                                                    <svg width="15px" height="15px" viewBox="0 0 8 10" xmlns="http://www.w3.org/2000/svg">
                                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M0 0h8v10L4.049 7.439 0 10V0z"></path>
                                                    </svg>
                                                    <span class="featured-text">Featured</span>
                                                </span>` : ''}
                                                <span class="event-time">${event.event_date} @ ${event.event_time} - ${event.event_end_time} ${event.time_zone}</span>
                                            </div>
                                            <h3 class="event-title">
                                                <a href="event.php?id=${event.id}" class="event-link">${event.event_name}</a>
                                            </h3>
                                            <address class="event-venue">${event.event_venue}</address>
                                            <div class="event-description">
                                                <p>${event.event_description.substring(0, 200)}${event.event_description.length > 200 ? "..." : ""}</p>
                                            </div>
                                        </div>
                                    </div>
                                `;
                                eventContainer.insertAdjacentHTML("beforeend", eventHTML);
                            });
                        }

                        // Enable/Disable pagination buttons
                        prevButton.style.display = data.has_prev ? "inline-block" : "none";
                        nextButton.style.display = data.has_next ? "inline-block" : "none";
                    })
                    .catch(error => console.error("Error fetching events:", error));
            }

            // Event listeners for pagination
            document.getElementById("prev-page").addEventListener("click", function () {
                if (currentPage > 1) {
                    currentPage--;
                    fetchEvents(currentPage);
                }
            });

            document.getElementById("next-page").addEventListener("click", function () {
                currentPage++;
                fetchEvents(currentPage);
            });

            // Initial fetch
            fetchEvents(currentPage);
        });
    </script>
</body>

</html>
