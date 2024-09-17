<?php
require 'PHPMailer.php';
require 'SMTP.php';
require 'Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Database connection details
include 'db.php';

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $conn->real_escape_string($_POST["name"]);
    $email = $conn->real_escape_string($_POST["email"]);
    $phone = $conn->real_escape_string($_POST["phone"]);
    $message = $conn->real_escape_string($_POST["message"]);

    // Prepare and execute the database query
    $stmt = $conn->prepare("INSERT INTO ContactFormSubmissions (name, email, phone, message) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name, $email, $phone, $message);

    if ($stmt->execute()) {
        // Close the statement
        $stmt->close();

        // Send an email with the form data
        $mail = new PHPMailer(true);

        try {
            // Server settings
            $mail->isSMTP();
            $mail->Host = 'mail.gozoomtech.com'; // Replace with your SMTP server address
            $mail->SMTPAuth = true;
            $mail->Username = 'info@gozoomtech.com'; // Replace with your SMTP username
            $mail->Password = 'Gozoom@123'; // Replace with your SMTP password
            $mail->SMTPSecure = 'tls'; // Enable TLS encryption, 'ssl' also possible
            $mail->Port = 587;

            // Recipients
            $mail->setFrom('info@gozoomtech.com', 'Contact Form');
            $mail->addAddress('info@gozoomtech.com'); // Replace with the desired email address

            // Email content
            $mail->isHTML(false);
            $mail->Subject = 'New Contact Form Submission';
            $mail->Body = "Name: $name\n"
                . "Email: $email\n"
                . "Phone: $phone\n"
                . "Message: $message\n";

            $mail->send();

            // Redirect to success page
            header("Location: success.html");
            exit;

        } catch (Exception $e) {
            // Error sending email
            echo "Error sending email: " . $mail->ErrorInfo;
            exit;
        }
    } else {
        // Error inserting into database
        echo "Error: " . $stmt->error;
    }

    // Close the database connection
    $conn->close();
}
?>
