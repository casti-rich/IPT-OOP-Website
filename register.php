<?php
$taken = "";
$success = "";
$error = "";

$users = json_decode(file_get_contents(__DIR__ . "/users.json"), true);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email = trim($_POST['email']) ?? '';
    $password = ($_POST['password']) ?? '';
    $confirm = ($_POST['confirm_password']) ?? '';

    if ($email === '' || $password === '' || $confirm === '') {
        $error = "Please fill in all fields.";
    }
    elseif (!preg_match("/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/", $email)) {
        $error = "Invalid email format.";
    }

    else if ($password !== $confirm) {
        $error = "Passwords do not match.";
    }

    else if (isset($users[$email])) {
        $taken = "User is already taken.";
    }

    else {
        $users[$email] = $password;
        file_put_contents(__DIR__ . "/users.json", json_encode($users));
        $success = "Registration successful. You can now log in.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="register.css">
    <title>SIGN UP</title>

</head>

<body>
    <div class="register_container">
        <div class="form_wrapper">
            <h1>SIGN UP</h1>
            <div class="form_container">
                <form method="post">
                    <fieldset>
                        <input type="email" name="email" size="40" placeholder="Email"><br>
                        <input type="password" name="password" size="40" placeholder="Password"><br>
                        <input type="password" name="confirm_password" size="40" placeholder="Confirm Password"><br>
                        <input type="submit" value="Register">                       
                    </fieldset>
                        <a class="sign-in_link" href="login.php"><p>Already have an account? Login here.</p></a>
                        <?php if ($taken): ?>
                            <p style="color:red;"><?php echo $taken; ?></p>
                        <?php endif; ?>
                        <?php if ($success): ?>
                            <p style="color:rgb(0, 255, 0);"><?php echo $success; ?></p>
                        <?php endif; ?>
                        <?php if ($error): ?>
                            <p style="color:red;"><?php echo $error; ?></p>
                        <?php endif; ?>
                </form>
            </div>
        </div>
        


    </div>
</body>
</html>