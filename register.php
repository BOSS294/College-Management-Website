<?php
// Include necessary files
include 'db_connection.php';
error_reporting(E_ALL);
ini_set('display_errors',1);
// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate form inputs
    $name = sanitizeInput($_POST['name']);
    $email = sanitizeInput($_POST['email']);
    $password = sanitizeInput($_POST['password']);
    $terms = isset($_POST['terms']) ? true : false;
    $privacy = isset($_POST['privacy']) ? true : false;

    // Check if name, email, and password are not empty
    if (empty($name) || empty($email) || empty($password)) {
        showMessage("Please fill in all the fields.", "register.html");
        exit();
    }

    // Check if terms and privacy checkboxes are checked
    if (!$terms || !$privacy) {
        showMessage("Please agree to the Terms & Conditions and Privacy Policy.", "register.html");
        exit();
    }

    // Hash the password
    $hashedPassword = hashPassword($password);

    // Check if the email is already taken
    $checkEmailQuery = "SELECT * FROM college_students WHERE email='$email'";
    $result = mysqli_query($conn, $checkEmailQuery);
    if (mysqli_num_rows($result) > 0) {
        showMessage("Email is already registered.", "register.html");
        exit();
    }

    // Generate college ID
    $collegeIdPrefix = "CMS-";
    $collegeIdQuery = "SELECT MAX(SUBSTRING(college_id, 5)) AS max_id FROM college_students";
    $result = mysqli_query($conn, $collegeIdQuery);
    $row = mysqli_fetch_assoc($result);
    $maxId = $row['max_id'];
    $nextId = $maxId + 1;
    $collegeId = $collegeIdPrefix . sprintf("%04d", $nextId); // Pad with leading zeros

    // Insert the user into the database
    $insertUserQuery = "INSERT INTO college_students (college_id, name, email, password) VALUES ('$collegeId', '$name', '$email', '$hashedPassword')";
    if (mysqli_query($conn, $insertUserQuery)) {
        // Log the registration activity
        logSecurityAction("Registration", "New user registered: $name, $email");
        showMessage("Registration successful. You can now login.", "login.html");
    } else {
        showMessage("An error occurred. Please try again later.", "register.html");
    }
} else {
    // If the form is not submitted, redirect to the registration page
    header("Location: register.html");
}
?>
