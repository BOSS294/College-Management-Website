<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
// Function to sanitize input data
function sanitizeInput($input) {
    global $conn; // Access the global $conn variable

    // Remove HTML and PHP tags
    $input = strip_tags($input);

    // Prevent SQL injection
    $input = mysqli_real_escape_string($conn, $input);

    // Return sanitized input
    return $input;
}

// Function to hash passwords
function hashPassword($password) {
    // Use PHP's password_hash function to hash the password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    return $hashedPassword;
}

// Function to verify hashed password
function verifyPassword($password, $hashedPassword) {
    // Use PHP's password_verify function to verify the password
    $passwordMatch = password_verify($password, $hashedPassword);
    return $passwordMatch;
}

// Function to generate a random string (for CSRF tokens, etc.)
function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $randomString;
}

// Function to log security actions
function logSecurityAction($action, $details) {
    global $conn;

    // Capture additional information
    $ip_address = $_SERVER['REMOTE_ADDR'];
    $user_agent = $_SERVER['HTTP_USER_AGENT'];

    // Escape details to prevent SQL injection
    $escaped_details = mysqli_real_escape_string($conn, $details);

    // Insert data into the database
    $sql = "INSERT INTO student_logger (action, details, ip_address, user_agent)
            VALUES ('$action', '$escaped_details', '$ip_address', '$user_agent')";

    if (mysqli_query($conn, $sql)) {
        return true; // Success
    } else {
        return false; // Error
    }
}

// Function to check login attempts
function checkLoginAttempts($collegeId) {
    global $conn;
    $query = "SELECT login_attempts FROM college_students WHERE college_id = '$collegeId'";
    $result = mysqli_query($conn, $query);
    if ($result && mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);
        return $row['login_attempts'];
    } else {
        return 0;
    }
}

// Function to increment login attempts
function incrementLoginAttempts($collegeId) {
    global $conn;
    $query = "UPDATE college_students SET login_attempts = login_attempts + 1 WHERE college_id = '$collegeId'";
    mysqli_query($conn, $query);
}

// Function to check login timer
function checkLoginTimer($collegeId) {
    global $conn;
    $query = "SELECT login_timer FROM college_students WHERE college_id = '$collegeId'";
    $result = mysqli_query($conn, $query);
    if ($result && mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);
        $loginTimer = strtotime($row['login_timer']);
        $currentTimestamp = time();
        if ($currentTimestamp - $loginTimer < 60) { // 60 seconds lock time
            return true;
        }
    }
    return false;
}

// Function to reset login timer
function resetLoginTimer($collegeId) {
    global $conn;
    $query = "UPDATE college_students SET login_timer = NULL WHERE college_id = '$collegeId'";
    mysqli_query($conn, $query);
}

// Function to reset login attempts
function resetLoginAttempts($collegeId) {
    global $conn;
    $query = "UPDATE college_students SET login_attempts = 0 WHERE college_id = '$collegeId'";
    mysqli_query($conn, $query);
}

// Function to update the status of a student
function updateStudentStatus($collegeId, $status) {
    global $conn;
    
    // Check if the provided status is valid
    if (!in_array($status, array('Online', 'Offline'))) {
        return false; // Invalid status
    }
    
    // Update the status in the database
    $query = "UPDATE college_students SET status = '$status' WHERE college_id = '$collegeId'";
    if (mysqli_query($conn, $query)) {
        return true; // Success
    } else {
        return false; // Error
    }
}

function fetchDataFromTable($tableName, $fromColumn, $searchValue, $selectColumns = '*') {
    global $conn;

    // Prepare the SQL statement
    $stmt = $conn->prepare("SELECT $selectColumns FROM $tableName WHERE $fromColumn = ?");
    if ($stmt === false) {
        return false; // Error in preparing the statement
    }

    // Bind parameters and execute the statement
    $stmt->bind_param("s", $searchValue);
    $stmt->execute();

    // Get the result
    $result = $stmt->get_result();
    if ($result === false) {
        return false; // Error in getting the result
    }

    // Fetch data from the result set
    $data = $result->fetch_assoc();

    // Close the statement
    $stmt->close();

    return $data;
}
// Function to check session duration and validity
function validateSession() {
    if (isset($_SESSION['start_time']) && isset($_SESSION['college_id'])) {
        global $conn; // Ensure the database connection is available
        $sessionDuration = 60 * 60; // 60 minutes
        $currentTime = time();
        $startTime = $_SESSION['start_time'];
        $collegeId = $_SESSION['college_id'];

        // If session has been active for more than 60 minutes, log out and update status to 'Offline'
        if ($currentTime - $startTime >= $sessionDuration) {
            // Update student status to 'Offline'
            updateStudentStatus($collegeId, 'Offline');
            // Destroy session
            session_unset();
            session_destroy();
            // Redirect to login page with session expired message
            showMessage("Login session expired. Please log in again.", "login.html");
            exit(); // Stop further execution
        }
    }
}


// Function to show a message and redirect after 5 seconds
function showMessage($message, $redirectURL) {
    $randomQuotes = array(
        "The only way to do great work is to love what you do. – Steve Jobs",
        "Success is not final, failure is not fatal: It is the courage to continue that counts. – Winston Churchill",
        "Believe you can and you're halfway there. – Theodore Roosevelt",
        "Your limitation—it's only your imagination.",
        "Push yourself, because no one else is going to do it for you.",
        "Great things never come from comfort zones.",
        "Dream it. Wish it. Do it.",
        "Success doesn’t just find you. You have to go out and get it.",
        "The harder you work for something, the greater you’ll feel when you achieve it."
    );

    $randomQuote = $randomQuotes[array_rand($randomQuotes)];

    echo <<<EOD
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Loading...</title>
<style>
/* Styles for full-page loader */
.loader-container {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    display: flex;
    align-items: center;
    justify-content: center;
    flex-direction: column;
    color: white;
    font-family: Arial, sans-serif;
    z-index: 9999;
}

.loader {
    border: 8px solid #f3f3f3;
    border-radius: 50%;
    border-top: 8px solid #3498db;
    width: 50px;
    height: 50px;
    animation: spin 2s linear infinite;
    margin-bottom: 20px;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

.message {
    font-size: 24px;
    margin-bottom: 20px;
    text-align: center;
}

.quote {
    font-style: italic;
    text-align: center;
}
</style>
</head>
<body>
<div class="loader-container">
    <div class="loader"></div>
    <div class="message">$message</div>
    <div class="quote">$randomQuote</div>
</div>
<script>
setTimeout(function() {
    window.location.href = '$redirectURL';
}, 5000); // Redirect after 5 seconds
</script>
</body>
</html>
EOD;
}
?>
