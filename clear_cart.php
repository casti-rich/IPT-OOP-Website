<?php
session_start();

unset($_SESSION['cart']);

header('Location: check-out-page.php');
exit();
