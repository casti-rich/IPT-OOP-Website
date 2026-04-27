<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form results</title>
</head>
<body>
    <?php
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    if (isset($_POST['sports'])) {
        $sports = $_POST['sports'];
    } else {
        $sports = "Not specified";
    }
    $essay = $_POST['essay'];

    echo "<h2>First name: $fname</h2>";
    echo "<h2>Last name: $lname</h2>";
    echo "<h2>Favorite sport: $sports</h2>";
    echo "<h2>Essay report  :</h2>";
    echo "<p>$essay</p>";

    ?>