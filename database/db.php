<?php
    // Database connection instructions:
    // Go to phpmyadmin and create a new database named "rhythm_link".
    // Go to user accounts tab and find root, localhost, password is empty
    // Update the variables below with your database credentials if they differ from the defaults.
    $db_server = "localhost";
    $db_user = "root";
    $db_pass = "";
    $db_name = "rhythm_link";
    $conn = "";

    $conn = mysqli_connect($db_server, $db_user, $db_pass, $db_name);

    // Expose connection error without emitting output to keep headers intact.
    if (!$conn) {
        $db_error = mysqli_connect_error();
    }
?>