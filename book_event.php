<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
$servername = "localhost";
$username = "root"; 
$password = "";  
$dbname = "dcora_db"; 

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Sanitize and retrieve form data using htmlspecialchars to prevent XSS attacks
    $name = htmlspecialchars($_POST['FirstName']);
    $email = htmlspecialchars($_POST['Email']);
    $phone = htmlspecialchars($_POST['PhoneNumber']);
    $message = htmlspecialchars($_POST['Message']);
    $package = htmlspecialchars($_POST['package']);
    $event_type = htmlspecialchars($_POST['eventType']); // Get the event type

    // Use a prepared statement to prevent SQL injection
    $stmt = $conn->prepare("INSERT INTO bookings (name, email, phone, message, package, event_type) VALUES (?, ?, ?, ?, ?, ?)");
    
    $stmt->bind_param("ssssss", $name, $email, $phone, $message, $package, $event_type);

    // Execute the statement and check for success
    if ($stmt->execute()) {
        header("Location: packages.html?status=success&event=" . urlencode($event_type));
    } else {
        // If there's an error, redirect back with an error message
        header("Location: packages.html?status=error&event=" . urlencode($event_type));
    }

    $stmt->close();
    $conn->close();
    exit(); 
}
?>