<?php
    // Database connection instructions:
    // Go to phpmyadmin and create a new database named "rhythm_link".
    // Go to user accounts tab and find root, localhost, password is empty
    // Update the variables below with your database credentials if they differ from the defaults.
    $db_server = "localhost";
    $db_user = "root";
    $db_pass = "";
    $db_name = "rhythm_link";
    // Comment out the line below if $conn is showing as undefined in other files.
    //$conn = "";

    $conn = mysqli_connect($db_server, $db_user, $db_pass, $db_name);

    // Expose connection error without emitting output to keep headers intact.
    if (!$conn) {
        $db_error = mysqli_connect_error();
    }

    date_default_timezone_set('Asia/Manila');

    mysqli_query($conn, "SET time_zone = '+08:00'");

    function processExpiredRentals(mysqli $conn): void
{
    $result = mysqli_query(
        $conn,
        "SELECT Rental_ID, Product_ID
         FROM rentals
         WHERE Status = 'active'
           AND Rent_End <= NOW()"
    );

    if (!$result) {
        return;
    }

    while ($rental = mysqli_fetch_assoc($result)) {

        mysqli_query(
            $conn,
            "UPDATE rentals
             SET Status = 'expired'
             WHERE Rental_ID = {$rental['Rental_ID']}"
        );

        mysqli_query(
            $conn,
            "UPDATE product_inventory
             SET Stock = Stock + 1,
                 Last_Updated = NOW()
             WHERE Product_ID = {$rental['Product_ID']}"
        );
    }
}
?>