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

        // Optionally send email here (commented out for now)
        /*
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'mail.gozoomtech.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'info@gozoomtech.com';
            $mail->Password = 'Gozoom@123';
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            $mail->setFrom('info@gozoomtech.com', 'Contact Form');
            $mail->addAddress('info@gozoomtech.com');

            $mail->isHTML(false);
            $mail->Subject = 'New Contact Form Submission';
            $mail->Body = "Name: $name\nEmail: $email\nPhone: $phone\nMessage: $message";

            $mail->send();
        } catch (Exception $e) {
            echo "<script>alert('Email sending failed: " . $mail->ErrorInfo . "');</script>";
        }
        */

        // Redirect to homepage on success
        header("Location: success.html");
        exit;
    } else {
        // Show alert if database insertion fails
        echo "<script>alert('Error saving data: " . $stmt->error . "'); window.history.back();</script>";
    }

    // Close the connection
    $conn->close();
}
?>
