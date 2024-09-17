<?php
// Database connection
include 'db.php';

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $day = $_POST['day'];
    $event_date = $_POST['event_date'];
    $event_time = $_POST['event_time'];
    $event_end_time = $_POST['event_end_time']; // New field for event end time
    $time_zone = $_POST['time_zone'];
    $event_name = $_POST['event_name'];
    $event_description = $_POST['event_description'];
    $organizer = $_POST['organizer'];
    $event_venue = $_POST['event_venue'];
    $latitude = $_POST['latitude'];
    $longitude = $_POST['longitude'];
    $is_featured = isset($_POST['is_featured']) ? 1 : 0;

    // Insert event into database
    $sql = "INSERT INTO events (day, event_date, event_time, event_end_time, time_zone, event_name, event_description, organizer, event_venue, latitude, longitude, is_featured) 
            VALUES ('$day', '$event_date', '$event_time', '$event_end_time', '$time_zone', '$event_name', '$event_description', '$organizer', '$event_venue', '$latitude', '$longitude', '$is_featured')";

    if ($conn->query($sql) === TRUE) {
        echo "<div class='alert alert-success'>New event created successfully</div>";
    } else {
        echo "<div class='alert alert-danger'>Error: " . $sql . "<br>" . $conn->error . "</div>";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Event Input Form</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Maps API (for autocomplete and map selector) -->
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDP4YgDI3gOakb5Y-kqrCCtCT4M8pj9Mzk&libraries=places"></script>
</head>
<body>
<div class="container mt-5">
    <h2>Add Event</h2>
    <form action="add-event.php" method="post">
        <!-- Day Dropdown -->
        <div class="form-group">
            <label for="day">Day</label>
            <select class="form-control" id="day" name="day" required>
                <option value="">Select Day</option>
                <option value="Monday">Monday</option>
                <option value="Tuesday">Tuesday</option>
                <option value="Wednesday">Wednesday</option>
                <option value="Thursday">Thursday</option>
                <option value="Friday">Friday</option>
                <option value="Saturday">Saturday</option>
                <option value="Sunday">Sunday</option>
            </select>
        </div>

        <!-- Date Picker -->
        <div class="form-group">
            <label for="date">Date</label>
            <input type="date" class="form-control" id="date" name="event_date" required>
        </div>

        <!-- Event Time Dropdown -->
        <div class="form-group">
            <label for="event_time">Event Start Time</label>
            <select class="form-control" id="event_time" name="event_time" required>
                <option value="">Select  Start Time</option>
                <?php
                // Loop for generating times from 12:00 AM to 11:30 PM
                $times = ['AM', 'PM']; // Array for AM/PM
                foreach ($times as $period) {
                    for ($hour = 1; $hour < 12; $hour++) {
                        $hour_formatted = str_pad($hour, 2, '0', STR_PAD_LEFT);
                        echo "<option value='$hour_formatted:00:00 $period'>$hour_formatted:00 $period</option>";
                        echo "<option value='$hour_formatted:30:00 $period'>$hour_formatted:30 $period</option>";
                    }
                }
                ?>
            </select>

        </div>

        <!-- New Event End Time Field -->
        <div class="form-group">
                <label for="event_end_time" class="form-label">Event End Time</label>
                <select class="form-control" id="event_end_time" name="event_end_time" required>
                    <option value="">Select End Time</option>
                </select>
        </div>

        <!-- Time Zone Dropdown -->
        <div class="form-group">
            <label for="time_zone">Time Zone</label>
            <select class="form-control" id="time_zone" name="time_zone" required>
                <option value="IST">IST (Indian Standard Time)</option>
                <option value="EST">EST (Eastern Standard Time)</option>
                <option value="EDT">EDT (Eastern Daylight Time)</option>
                <option value="PST">PST (Pacific Standard Time)</option>
                <option value="GMT">GMT (Greenwich Mean Time)</option>
                <!-- Add other time zones as necessary -->
            </select>
        </div>

        <!-- Event Name -->
        <div class="form-group">
            <label for="event_name">Event Name</label>
            <input type="text" class="form-control" id="event_name" name="event_name" placeholder="Enter event name" required>
        </div>

        <!-- Event Description -->
        <div class="form-group">
            <label for="event_description">Event Description</label>
            <textarea class="form-control" id="event_description" name="event_description" rows="3" placeholder="Enter event description" required></textarea>
        </div>

        <!-- Organizer -->
        <div class="form-group">
            <label for="organizer">Organizer</label>
            <input type="text" class="form-control" id="organizer" name="organizer" placeholder="Enter organizer name" required>
        </div>

        <!-- Venue with Autocomplete (Canada) -->
        <div class="form-group">
            <label for="event_venue">Event Venue</label>
            <input type="text" class="form-control" id="event_venue" name="event_venue" placeholder="Enter venue address" required>
        </div>

        <!-- Google Map for Latitude and Longitude -->
        <div class="form-group">
            <label>Event Location (Select on Map)</label>
            <div id="map" style="height: 400px;"></div>
            <input type="hidden" id="latitude" name="latitude">
            <input type="hidden" id="longitude" name="longitude">
        </div>

        <!-- Is Featured Dropdown -->
        <div class="form-group">
            <label for="is_featured">Is Featured</label>
            <select class="form-control" id="is_featured" name="is_featured" required>
                <option value="0">No</option>
                <option value="1">Yes</option>
            </select>
        </div>

        <!-- Submit Button -->
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>

<!-- Bootstrap JS and dependencies -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<!-- Google Maps and Address Autocomplete -->
<script>
    let map;
    let marker;
    let autocomplete;

    function initMap() {
        // Default location (Canada)
        const defaultLocation = { lat: 56.1304, lng: -106.3468 };

        // Initialize the map
        map = new google.maps.Map(document.getElementById('map'), {
            center: defaultLocation,
            zoom: 5
        });

        // Marker to select location
        marker = new google.maps.Marker({
            position: defaultLocation,
            map: map,
            draggable: true
        });

        // Event listener for marker position change
        google.maps.event.addListener(marker, 'position_changed', function() {
            const lat = marker.getPosition().lat();
            const lng = marker.getPosition().lng();
            document.getElementById('latitude').value = lat;
            document.getElementById('longitude').value = lng;
        });

        // Autocomplete for venue input
        autocomplete = new google.maps.places.Autocomplete(document.getElementById('event_venue'), {
            types: ['geocode'],
            componentRestrictions: { country: 'ca' } // Restrict to Canada
        });

        autocomplete.addListener('place_changed', function () {
            const place = autocomplete.getPlace();
            if (place.geometry) {
                map.setCenter(place.geometry.location);
                marker.setPosition(place.geometry.location);
            }
        });
    }

    window.onload = initMap;
</script>

<script>
        $('#event_time').change(function() {
            var startTime = $('#event_time').val();
            var startHour = parseInt(startTime.split(':')[0]);
            var startPeriod = startTime.split(' ')[1];
            var endTimeDropdown = $('#event_end_time');

            endTimeDropdown.empty();
            endTimeDropdown.append('<option value="">Select End Time</option>');

            var periods = ['AM', 'PM'];
            for (var periodIndex = periods.indexOf(startPeriod); periodIndex < periods.length; periodIndex++) {
                var period = periods[periodIndex];
                for (var hour = (periodIndex == periods.indexOf(startPeriod)) ? startHour + 1 : 1; hour < 12; hour++) {
                    var hourFormatted = hour < 10 ? '0' + hour : hour;
                    if (hour > startHour || period != startPeriod) {
                        endTimeDropdown.append('<option value="' + hourFormatted + ':00:00 ' + period + '">' + hourFormatted + ':00 ' + period + '</option>');
                        endTimeDropdown.append('<option value="' + hourFormatted + ':30:00 ' + period + '">' + hourFormatted + ':30 ' + period + '</option>');
                    }
                }
            }
        });


        document.getElementById("phoneNumber").addEventListener("input", function () {
            const phoneInput = this;
            if (phoneInput.value.length !== 10 || isNaN(phoneInput.value)) {
                phoneInput.classList.add('is-invalid');
            } else {
                phoneInput.classList.remove('is-invalid');
            }
        });
    </script>
</body>
</html>
