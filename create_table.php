<?php

function createTablesIfNotExist($conn) {
    // Query to create college_students table
    $createCollegeStudentsTableSQL = "CREATE TABLE IF NOT EXISTS college_students (
        college_id VARCHAR(10) PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        email VARCHAR(100) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        status ENUM('Online', 'Offline') DEFAULT 'Offline',
        rank ENUM('Student', 'Faculty', 'Admin') DEFAULT 'Student',
        registration_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        login_attempts INT DEFAULT 0,
        login_timer TIMESTAMP
    )";
    

    // Query to create student_logger table
    $createStudentLoggerTableSQL = "CREATE TABLE IF NOT EXISTS student_logger (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        email VARCHAR(100) NOT NULL UNIQUE,
        action VARCHAR(255) NOT NULL,
        timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";

    // Execute the queries
    $result1 = mysqli_query($conn, $createCollegeStudentsTableSQL);
    $result2 = mysqli_query($conn, $createStudentLoggerTableSQL);

    // Check if both queries were successful
    if ($result1 && $result2) {
        return true; // Success
    } else {
        return false; // Error
    }
}

?>
