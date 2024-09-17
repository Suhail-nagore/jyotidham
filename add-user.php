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
<div class="container mt-5">
    <h2>User Registration</h2>
    <form action="add-user.php" method="POST" id="userForm">
        <!-- First Name -->
        <div class="mb-3">
            <label for="firstName" class="form-label">First Name</label>
            <input type="text" class="form-control" id="firstName" name="firstName" required>
        </div>

        <!-- Last Name -->
        <div class="mb-3">
            <label for="lastName" class="form-label">Last Name</label>
            <input type="text" class="form-control" id="lastName" name="lastName" required>
        </div>

        <!-- Email with Dynamic Validation -->
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" required>
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
            <label for="houseNumber" class="form-label">House Number</label>
            <input type="number" class="form-control" id="houseNumber" name="houseNumber" required>
        </div>
        <div class="mb-3">
            <label for="street" class="form-label">Street</label>
            <input type="text" class="form-control" id="street" name="street" required>
        </div>
        <div class="mb-3">
            <label for="city" class="form-label">City</label>
            <input type="text" class="form-control" id="city" name="city" required>
        </div>
        <div class="mb-3">
            <label for="state" class="form-label">State/Province</label>
            <input type="text" class="form-control" id="state" name="stateProvince" required>
        </div>
        <div class="mb-3">
            <label for="country" class="form-label">Country</label>
            <input type="text" class="form-control" id="country" name="country" value="Canada" readonly required>
        </div>

        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Include the database connection
    include 'db.php';

    // Collect and sanitize form data
    $firstName = $conn->real_escape_string($_POST['firstName']);
    $lastName = $conn->real_escape_string($_POST['lastName']);
    $email = $conn->real_escape_string($_POST['email']);
    $fullPhoneNumber = $conn->real_escape_string($_POST['fullPhoneNumber']);
    $houseNumber = $conn->real_escape_string($_POST['houseNumber']);
    $street = $conn->real_escape_string($_POST['street']);
    $city = $conn->real_escape_string($_POST['city']);
    $stateProvince = $conn->real_escape_string($_POST['stateProvince']);
    $country = $conn->real_escape_string($_POST['country']);

    // Insert data into Usersdetails table
    $sql = "INSERT INTO Usersdetails (FirstName, LastName, Email, phoneNumber, Address, houseNumber, street, city, stateProvince, country) 
            VALUES ('$firstName', '$lastName', '$email', '$fullPhoneNumber', '$houseNumber $street, $city, $stateProvince, $country', '$houseNumber', '$street', '$city', '$stateProvince', '$country')";

    if ($conn->query($sql) === TRUE) {
        echo "<div class='alert alert-success mt-3'>New record created successfully</div>";
    } else {
        echo "<div class='alert alert-danger mt-3'>Error: " . $sql . "<br>" . $conn->error . "</div>";
    }

    $conn->close();
}
?>

<!-- Google Maps API for Autocomplete -->
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDP4YgDI3gOakb5Y-kqrCCtCT4M8pj9Mzk&libraries=places"></script>
<script>
function initAutocomplete() {
    var streetField = document.getElementById('street');
    var cityField = document.getElementById('city');
    var stateField = document.getElementById('state');
    var countryField = document.getElementById('country');

    // Initialize Autocomplete only for the street input
    var autocomplete = new google.maps.places.Autocomplete(streetField, {
        types: ['address'],
        componentRestrictions: { country: 'ca' } // Restrict results to Canada
    });

    // When a place is selected from the autocomplete dropdown
    autocomplete.addListener('place_changed', function () {
        var place = autocomplete.getPlace();

        // Loop through address components and assign values to the respective fields
        for (var i = 0; i < place.address_components.length; i++) {
            var component = place.address_components[i];
            var types = component.types;

            // Match components with fields and assign the values
            if (types.includes("route")) {
                streetField.value = component.long_name; // Set street name (route)
            }
            if (types.includes("locality")) {
                cityField.value = component.long_name; // Set city (locality)
            }
            if (types.includes("administrative_area_level_1")) {
                stateField.value = component.long_name; // Set state (administrative_area_level_1)
            }
            if (types.includes("country")) {
                countryField.value = component.long_name; // Set country
            }
        }
    });
}

// Initialize the autocomplete on page load
google.maps.event.addDomListener(window, 'load', initAutocomplete);


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
        initialCountry: "auto", // Automatically detect user's country
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
