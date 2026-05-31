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

    // Check connection | comment out the lines below after confirming the connection works
    if($conn){
        echo "Database connection successful.";
    } else {
        echo "Database connection failed: " . mysqli_connect_error();
    }
?>