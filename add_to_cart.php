<?php
session_start();

$productId = $_POST['product_id'] ?? null;
$productKey = $_POST['product_key'] ?? '';
$title = $_POST['title'] ?? '';
$price = isset($_POST['price']) ? floatval($_POST['price']) : 0.0;
$image = $_POST['image'] ?? '';
$quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 0;
$inventory = isset($_POST['inventory']) ? intval($_POST['inventory']) : null;

if ($productKey === '') {
    $productKey = (string) $productId;
}

if ($productKey === '' || $quantity <= 0) {
    // Nothing to add — go back
    $redirect = $_SERVER['HTTP_REFERER'] ?? 'index.php';
    header("Location: $redirect");
    exit();
}

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

if (isset($_SESSION['cart'][$productKey])) {
    $_SESSION['cart'][$productKey]['quantity'] += $quantity;
} else {
    $_SESSION['cart'][$productKey] = [
        'id' => $productId,
        'key' => $productKey,
        'title' => $title,
        'price' => $price,
        'image' => $image,
        'quantity' => $quantity,
        'inventory' => $inventory,
    ];
}

header('Location: check-out-page.php');
exit();

?>
