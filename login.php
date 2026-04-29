<?php
session_start();

$error = "";

$users = json_decode(file_get_contents(__DIR__ . "/users.json"), true);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email = trim($_POST['email']) ?? '';
    $password = $_POST['password'] ?? '';

    if (isset($users[$email]) && strcmp($users[$email], $password) === 0) {

        $_SESSION['email'] = $email;
        header("Location: index.php");
        exit();

    } else {
        $error = "Invalid email or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="login.css">
    <title>Login</title>

</html>

<body>
    <div class="login_container">
        <div class="form_wrapper">
            <h1>LOGIN</h1>

            <div class="form_container">
                <form method="post">
                    <input type="email" name="email" size="40" placeholder="Email"><br>
                    <input type="password" name="password" size="40" placeholder="Password"><br>
                    <input type="submit" value="Login">
                    <a class="sign-in_link" href="register.php"><p>Don't have an account? Register here.</p></a>
                </form>
                <?php if ($error): ?>
                    <p style="color:red;"><?php echo $error; ?></p>
                <?php endif; ?>
            </div>
        </div>
        


    </div>
</body>