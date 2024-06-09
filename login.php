<?php
// Start or resume the session
session_start();

include 'db_connection.php';
validateSession();


// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate form inputs
    $collegeId = sanitizeInput($_POST['collegeId']);
    $password = sanitizeInput($_POST['password']);

    // Check if college ID and password are not empty
    if (empty($collegeId) || empty($password)) {
        showMessage("Please fill in all the fields.", "login.html");
        exit();
    }

    // Check if login attempts exceeded
    $loginAttempts = checkLoginAttempts($collegeId);
    if ($loginAttempts >= 3) {
        showMessage("Login attempts exceeded. Please try again later.", "login.html");
        exit();
    }

    // Check if login timer is active
    $loginTimer = checkLoginTimer($collegeId);
    if ($loginTimer) {
        showMessage("Login session expired. Please try again later.", "login.html");
        exit();
    }

    // Validate login credentials
    $validateLoginQuery = "SELECT * FROM college_students WHERE college_id='$collegeId'";
    $result = mysqli_query($conn, $validateLoginQuery);
    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);
        $hashedPassword = $row['password'];
        if (verifyPassword($password, $hashedPassword)) {
            // Login successful, reset login attempts and timer
            resetLoginAttempts($collegeId);
            resetLoginTimer($collegeId);
            // Update student status to 'Online'
            updateStudentStatus($collegeId, 'Online');
            // Store session start time
            $_SESSION['start_time'] = time();
            $_SESSION['college_id'] = $collegeId; // Start the session with college_id
            $_SESSION['name'] = $row['name']; // Store student name in session
            // Redirect to dashboard or home page
            showMessage("🎉 Login successful! Welcome!", "student-dashboard.php");
        } else {
            // Invalid password, increment login attempts
            incrementLoginAttempts($collegeId);
            showMessage("Invalid college ID or password. Please try again.", "login.html");
            exit();
        }
    } else {
        // Invalid college ID
        showMessage("Invalid college ID or password. Please try again.", "login.html");
        exit();
    }
} else {
    // If the form is not submitted, redirect to the login page
    header("Location: login.html");
}
?>
