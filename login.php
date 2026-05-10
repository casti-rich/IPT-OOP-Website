<?php
session_start();

$error = "";
$email = $_COOKIE['remember_email'] ?? "";
$users = json_decode(file_get_contents(__DIR__ . "/users.json"), true);
$tokensfile = __DIR__ . "/Scripts/user-token.json";

$tokens = json_decode(file_get_contents($tokensfile), true) ?? [];

unset($_SESSION['email']);

if (!isset($_SESSION['email'])) {

    $cookieToken = $_COOKIE['remember_token'] ?? '';

    if (!empty($cookieToken)) {
        foreach ($tokens as $storedEmail => $data) {

            if (
                isset($data['token'], $data['expires']) &&
                hash_equals($data['token'], $cookieToken) &&
                $data['expires'] > time()
            ) {
                $_SESSION['email'] = $storedEmail;
                header("Location: index.php");
                exit();
            }
        }

        
        setcookie('remember_token', '', time() - 60, '/');
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email = trim($_POST['email']) ?? '';
    $password = $_POST['password'] ?? '';
    $rememberme = isset($_POST['remember_me']);

    if (isset($users[$email]) && strcmp($users[$email], $password) === 0) {
        $_SESSION['email'] = $email;
        setcookie('remember_email', $email, time() + 60, '/');

        if ($rememberme) {
            $token   = bin2hex(random_bytes(32)); 
            $expires = time() + 60; 

            
            $tokens[$email] = ['token' => $token, 'expires' => $expires];
            file_put_contents($tokensfile, json_encode($tokens, JSON_PRETTY_PRINT));

            
            setcookie('remember_token', $token, $expires, '/', '', true, true);
            
        }

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
    <link rel="stylesheet" href="CSS/login.css">
    <title>Login</title>

</head>

<body>
    <div class="login_container">
        <div class="form_wrapper">
            <h1>LOGIN</h1>

            <div class="form_container">
                <form method="post">
                    <input type="email" name="email" size="40" placeholder="Email" value="<?php echo htmlspecialchars($email); ?>"><br>
                    <input type="password" name="password" size="40" placeholder="Password"><br>
                    <input type="checkbox" name="remember_me" id="remember_me">
                    <label for="remember_me">Remember me</label><br>
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
</html>