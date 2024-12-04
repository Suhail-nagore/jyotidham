<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Registration Form</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.13/css/intlTelInput.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.13/js/intlTelInput.min.js"></script>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5" style="margin-top:4rem !important; margin-bottom:4rem !important; max-width: 500px !important; border: 3px solid blue;border-radius:15px">
    <h2>User Registration</h2>
    <form action="add-user.php" method="POST" id="userForm" style="margin-top:2rem !important; margin-bottom:2rem !important;">
        <!-- First Name -->
        <div class="mb-3">
            <label for="firstName" class="form-label">First Name</label>
            <input type="text" class="form-control" id="firstName" name="firstName" placeholder="First Name" required>
        </div>

        <!-- Last Name -->
        <div class="mb-3">
            <label for="lastName" class="form-label">Last Name</label>
            <input type="text" class="form-control" id="lastName" name="lastName" placeholder="Last Name" required>
        </div>

        <!-- Email with Dynamic Validation -->
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" placeholder="Email" required>
            <div id="emailFeedback" class="invalid-feedback">
                Please enter a valid email address.
            </div>
        </div>

        <!-- Phone Number with Country Code -->
        <div class="mb-3">
            <label for="phone" class="form-label px-3">Phone Number</label>
            <input type="tel" id="phoneNumber" name="phoneNumber" class="form-control" required>
            <div id="phoneFeedback" class="invalid-feedback">
                Please enter a valid phone number.
            </div>
        </div>

        <!-- Address Fields with Autocomplete -->
        <div class="mb-3">
            <label for="address" class="form-label">Address</label>
            <input type="text" class="form-control" id="address" name="address" placeholder="Start typing your address" required>
        </div>

        <button type="submit" class="btn btn-primary">Submit</button>
        <a href="./dashboard.php" class="btn btn-secondary"> Back to Dashboard</a>
    </form>
</div>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include 'db.php';

    $firstName = $conn->real_escape_string($_POST['firstName']);
    $lastName = $conn->real_escape_string($_POST['lastName']);
    $email = $conn->real_escape_string($_POST['email']);
    $phoneNumber = $conn->real_escape_string($_POST['phoneNumber']);
    $address = $conn->real_escape_string($_POST['address']);

    $sql = "INSERT INTO Users (FirstName, LastName, Email, PhoneNumber, Address) 
            VALUES ('$firstName', '$lastName', '$email', '$phoneNumber', '$address')";

    if ($conn->query($sql) === TRUE) {
        echo "<div class='alert alert-success mt-3'>New record created successfully</div>";
    } else {
        echo "<div class='alert alert-danger mt-3'>Error: " . $sql . "<br>" . $conn->error . "</div>";
    }

    $conn->close();
}
?>

<!-- Google Maps API for Autocomplete -->
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBM1nAywoajfBnPLSqZn0z5wvUNj2ZYhF0&libraries=places"></script>
<script>
function initAutocomplete() {
    var addressField = document.getElementById('address');

    // Initialize Google Maps Autocomplete
    var autocomplete = new google.maps.places.Autocomplete(addressField, {
        types: ['geocode'],
        componentRestrictions: { country: 'ca' } // Restrict to Canada
    });

    // Optional: You can listen for the 'place_changed' event if needed
    autocomplete.addListener('place_changed', function () {
        var place = autocomplete.getPlace();
        console.log('Selected address:', place.formatted_address);
    });
}

// Initialize the autocomplete on page load
google.maps.event.addDomListener(window, 'load', initAutocomplete);
</script>

<script>
// Email validation
document.getElementById("email").addEventListener("input", function () {
    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    const emailInput = this;
    if (!emailPattern.test(emailInput.value)) {
        emailInput.classList.add('is-invalid');
    } else {
        emailInput.classList.remove('is-invalid');
    }
});
</script>

<!-- intl-tel-input initialization and functionality -->
<script>
    var phoneInputField = document.querySelector("#phoneNumber");

    // Initialize the intl-tel-input
    var iti = window.intlTelInput(phoneInputField, {
        initialCountry: "ca", // Automatically detect user's country
        geoIpLookup: function(callback) {
            fetch('https://ipinfo.io/json')
                .then(function(response) { return response.json(); })
                .then(function(data) { callback(data.country); })
                .catch(function() { callback("us"); });
        },
        separateDialCode: true, // Shows the dial code separately from the input field
        utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.13/js/utils.js" // For number formatting
    });

    // Validate the phone number on submit and send the full number to the server
    document.querySelector("form").addEventListener("submit", function(event) {
        // If the phone number is not valid
        if (!iti.isValidNumber()) {
            event.preventDefault();
            phoneInputField.classList.add("is-invalid");
            document.getElementById("phoneFeedback").textContent = "Please enter a valid phone number.";
        } else {
            phoneInputField.classList.remove("is-invalid");

            // Create a hidden input field to store the full phone number
            let hiddenPhoneInput = document.createElement("input");
            hiddenPhoneInput.type = "hidden";
            hiddenPhoneInput.name = "fullPhoneNumber";
            hiddenPhoneInput.value = iti.getNumber(); // Get full phone number in E.164 format

            // Append the hidden input to the form
            this.appendChild(hiddenPhoneInput);
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
