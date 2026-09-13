<?php

// Session and database configuration for the front controller.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($connect_database) || $connect_database) {
    mysqli_report(MYSQLI_REPORT_OFF);
    $host = "localhost";
    $username = "root";
    $password = "";
    $database = "pawcare_db";

    $conn = mysqli_connect($host, $username, $password, $database);
    if (!$conn) {
        die("Database connection failed: " . mysqli_connect_error());
    }
    mysqli_set_charset($conn, "utf8mb4");
}
