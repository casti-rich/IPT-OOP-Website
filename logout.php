<?php
session_start();

if (isset($_SESSION['user_id'])) {
	if (!isset($_SESSION['user_carts'])) {
		$_SESSION['user_carts'] = [];
	}

	$_SESSION['user_carts'][(int) $_SESSION['user_id']] = $_SESSION['cart'] ?? [];
}

unset($_SESSION['email'], $_SESSION['user_id'], $_SESSION['cart']);

setcookie('remember_token', '', time() - 3600, '/');

header("Location: login.php");
exit();
?>