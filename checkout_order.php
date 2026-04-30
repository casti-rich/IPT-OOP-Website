<?php
session_start();

if (!empty($_SESSION['cart'])) {
    unset($_SESSION['cart']);
    $_SESSION['checkout_message'] = 'Order confirmed! Thank you for your purchase.';
}

header('Location: check-out-page.php');
exit();
