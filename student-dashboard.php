<?php
// Start or resume the session
session_start();
include 'db_connection.php';

validateSession();

// Check if the user is not logged in (session variable not set)
if (!isset($_SESSION['college_id'])) {
    // Redirect to the login page
    header("Location: login.html");
    exit(); // Stop further execution
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Registration</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">

</head>
<body>
    <header>
        <nav>
            <ul>
                <li><a href="#"><span class="material-icons">home</span> Home</a></li>
                <li>
                <?php
                    // Check if student is logged in (status is 'Online')
                    if (isset($_SESSION['college_id'])) {
                        // Fetch student data
                        $studentData = fetchDataFromTable('college_students', 'college_id', $_SESSION['college_id'], 'name');

                        // Display 'Profile' instead of 'Students'
                        echo '<li><a href="#"><span class="material-icons">account_circle</span> Profile</a>';
                        echo '<ul class="dropdown">';
                        // Show student name as a dropdown item
                        echo '<li><a href="#"><span class="material-icons">person</span> ' . $studentData['name'] . '</a></li>';
                        // Add 'Dashboard' and 'Logout' links
                        echo '<li><a href="student-dashboard.php"><span class="material-icons">dashboard</span> Dashboard</a></li>';
                        echo '<li><a href="logout.php"><span class="material-icons">logout</span> Logout</a></li>';
                        echo '</ul>';
                        echo '</li>';
                    } else {
                        // Display 'Students' as usual if not logged in
                        echo '<li>';
                        echo '<a href="#"><span class="material-icons">people</span> Students</a>';
                        echo '<ul class="dropdown">';
                        echo '<li><a href="#"><span class="material-icons">login</span> Student Login</a></li>';
                        echo '<li><a href="#"><span class="material-icons">person_add</span> Student Registration</a></li>';
                        echo '</ul>';
                        echo '</li>';
                    }
                    ?>

                </li>
                <li>
                    <a href="#"><span class="material-icons">library_books</span> Courses</a>
                    <ul class="dropdown">
                        <li><a href="#"><span class="material-icons">book</span> Courses Offered</a></li>
                        <li><a href="#"><span class="material-icons">assignment</span> Apply for a Course</a></li>
                        <li><a href="#"><span class="material-icons">attach_money</span> Course Details & Fee Structure</a></li>
                    </ul>
                </li>
                <li>
                    <a href="#"><span class="material-icons">info</span> About Us</a>
                    <ul class="dropdown">
                        <li><a href="#"><span class="material-icons">school</span> About College</a></li>
                        <li><a href="#"><span class="material-icons">business</span> About Departments</a></li>
                        <li><a href="#"><span class="material-icons">people_outline</span> About Faculty</a></li>
                    </ul>
                </li>
                <li>
                    <a href="#"><span class="material-icons">more_horiz</span> More</a>
                    <ul class="dropdown">
                        <li><a href="#"><span class="material-icons">code</span> Developer</a></li>
                        <li><a href="#"><span class="material-icons">gavel</span> Terms & Conditions</a></li>
                        <li><a href="#"><span class="material-icons">lock</span> Privacy Policy</a></li>
                        <li><a href="#"><span class="material-icons">get_app</span> Download Source Code</a></li>
                    </ul>
                </li>
            </ul>
        </nav>
    </header>
    <main>
        <div class="student-dashboard">
            <div class="dashboard-item">
                <div class="dashboard-icon">
                    <span class="material-icons">account_circle</span>
                </div>
                <div class="dashboard-content">
                    <h3>Profile Management</h3>
                    <p>Students can view and update their personal information.</p>
                    <div class="dashboard-buttons">
                        <button id="viewProfileBtn">View Profile</button>
                        <button>Edit Profile</button>
                        <button>Change Password</button>
                    </div>
                </div>
            </div>
            <div class="dashboard-item">
                <div class="dashboard-icon">
                    <span class="material-icons">library_add</span>
                </div>
                <div class="dashboard-content">
                    <h3>Course Enrollment</h3>
                    <p>Students can browse and enroll in available courses.</p>
                    <div class="dashboard-buttons">
                        <button>Check Courses</button>
                        <button>Select Courses</button>
                        <button>Update Courses</button>
                    </div>
                </div>
            </div>
            <div class="dashboard-item">
                <div class="dashboard-icon">
                    <span class="material-icons">schedule</span>
                </div>
                <div class="dashboard-content">
                    <h3>Class Schedule</h3>
                    <p>View timetable and schedule of enrolled courses.</p>
                    <div class="dashboard-buttons">
                        <button>View TimeTable as of</button>
                        <button>Notifications</button>
                        <button>Announcemnets</button>

                    </div>
                </div>
            </div>
            <div class="dashboard-item">
                <div class="dashboard-icon">
                    <span class="material-icons">assignment</span>
                </div>
                <div class="dashboard-content">
                    <h3>Grades and Transcripts</h3>
                    <p>Access to grades, academic performance, and transcripts.</p>
                    <div class="dashboard-buttons">
                        <button>My Grades</button>
                        <button>Performance</button>
                        <button>Transcripts</button>
                    </div>
                </div>
            </div>
            <div class="dashboard-item">
                <div class="dashboard-icon">
                    <span class="material-icons">description</span>
                </div>
                <div class="dashboard-content">
                    <h3>Assignments and Submissions</h3>
                    <p>View, upload, and manage assignment submissions.</p>
                    <div class="dashboard-buttons">
                        <button>Check Assignme</button>
                        <button>Upload Assignme</button>
                        <button>Submit Assignme</button>
                    </div>
                </div>
            </div>
            <div class="dashboard-item">
                <div class="dashboard-icon">
                    <span class="material-icons">event_available</span>
                </div>
                <div class="dashboard-content">
                    <h3>Attendance Records</h3>
                    <p>Check attendance history , current status & elgibility.</p>
                    <div class="dashboard-buttons">
                        <button>Current Status</button>
                        <button>Check Attendance h</button>
                        <button>Check Eligibility</button>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <!-- Popup container -->


<!-- Edit profile popup container -->
    <div id="editProfilePopup" class="custom-popup">
        <!-- Popup content -->
        <div class="popup-content">
            <!-- Close button -->
            <span class="popup-close">&times;</span>
            <!-- Edit profile form -->
            <h2>Edit Profile</h2>
            <form id="editProfileForm">
                <label for="editName">Name:</label>
                <input type="text" id="editName" name="editName">
                <label for="editEmail">Email:</label>
                <input type="email" id="editEmail" name="editEmail">
                <label for="editPassword">New Password:</label>
                <input type="password" id="editPassword" name="editPassword">
                <!-- Add more fields as needed -->
                <button type="submit">Save Changes</button>
            </form>
        </div>
    </div>
    <footer>
        <p>&copy; 2024 College Student Management</p>
    </footer>
    <script>
    $(document).ready(function() {
    // Function to open the edit profile popup
    $('#editProfileBtn').click(function() {
        $('#editProfilePopup').fadeIn();
    });

    // Function to close the edit profile popup
    $('#editProfilePopup .popup-close').click(function() {
        $('#editProfilePopup').fadeOut();
    });

    // Function to handle form submission
    $('#editProfileForm').submit(function(event) {
        // Prevent default form submission
        event.preventDefault();
        
        // Perform form validation and submission here
        // You can use AJAX to send form data to the server for processing
        // After successful submission, you can close the popup and display a success message
        // Example:
        // $.post('update_profile.php', $(this).serialize(), function(response) {
        //     if (response.success) {
        //         // Close the popup
        //         $('#editProfilePopup').fadeOut();
        //         // Display a success message
        //         alert('Profile updated successfully!');
        //     } else {
        //         // Handle errors if any
        //         alert('Error: ' + response.message);
        //     }
        // }, 'json');
    });
});
</script>
    <script src="script.js"></script>
</body>
</html>
