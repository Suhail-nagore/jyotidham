<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Registration Form</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2>User Registration</h2>
    <form action="add-user.php" method="POST">
        <div class="mb-3">
            <label for="firstName" class="form-label">First Name</label>
            <input type="text" class="form-control" id="firstName" name="firstName" required>
        </div>
        <div class="mb-3">
            <label for="lastName" class="form-label">Last Name</label>
            <input type="text" class="form-control" id="lastName" name="lastName" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>
        <div class="mb-3">
            <label for="phoneNumber" class="form-label">Phone Number</label>
            <input type="text" class="form-control" id="phoneNumber" name="phoneNumber" required>
        </div>
        <div class="mb-3">
            <label for="address" class="form-label">Address</label>
            <input type="text" class="form-control" id="address" name="address" required>
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>


<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Include the database connection file
    include 'db.php';

    // Collect and sanitize form data
    $firstName = $conn->real_escape_string($_POST['firstName']);
    $lastName = $conn->real_escape_string($_POST['lastName']);
    $email = $conn->real_escape_string($_POST['email']);
    $phoneNumber = $conn->real_escape_string($_POST['phoneNumber']);
    $address = $conn->real_escape_string($_POST['address']);

    // Check if the phone number already exists in the database
    $checkQuery = "SELECT * FROM Usersdetails WHERE PhoneNumber = '$phoneNumber'";
    $result = $conn->query($checkQuery);

    if ($result->num_rows > 0) {
        // Phone number already exists, fetch user details
        $row = $result->fetch_assoc();
        $existingFirstName = $row['FirstName'];
        $existingLastName = $row['LastName'];
        $existingEmail = $row['Email'];

        // Store the user details in a JavaScript variable
        echo "
        <script>
            window.onload = function() {
                document.getElementById('userDetails').innerHTML = 'Name: $existingFirstName $existingLastName<br>Email: $existingEmail';
                var userExistsModal = new bootstrap.Modal(document.getElementById('userExistsModal'));
                userExistsModal.show();
            }
        </script>
        ";
    } else {
        // Insert new user data into the database
        $sql = "INSERT INTO Usersdetails (FirstName, LastName, Email, PhoneNumber, Address) 
                VALUES ('$firstName', '$lastName', '$email', '$phoneNumber', '$address')";

        if ($conn->query($sql) === TRUE) {
            echo "<div class='alert alert-success mt-3'>New record created successfully</div>";
        } else {
            echo "<div class='alert alert-danger mt-3'>Error: " . $sql . "<br>" . $conn->error . "</div>";
        }
    }

    // Close the connection
    $conn->close();
}
?>

<!-- Modal for Existing User -->
<div class="modal fade" id="userExistsModal" tabindex="-1" aria-labelledby="userExistsModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="userExistsModalLabel">User Already Exists</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p id="userDetails"></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>


<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDP4YgDI3gOakb5Y-kqrCCtCT4M8pj9Mzk&libraries=places"></script>

<!-- JavaScript for initializing the address autocomplete -->
<script>
    function initAutocomplete() {
        var addressField = document.getElementById('address');
        var autocomplete = new google.maps.places.Autocomplete(addressField, {
            types: ['address'],
            componentRestrictions: { country: 'ca' } // Limit to Canada
        });

        // Optionally: restrict to specific types of results like geocode, establishment, etc.
        // autocomplete.setFields(['address_components', 'geometry']);

        // Bias the autocomplete to the user's geographical location
        autocomplete.addListener('place_changed', function () {
            var place = autocomplete.getPlace();
            if (!place.geometry) {
                // User did not select a prediction; reset the input.
                addressField.placeholder = 'Start typing your address';
            }
        });
    }

    // Initialize Google Places Autocomplete
    google.maps.event.addDomListener(window, 'load', initAutocomplete);
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
