<?php
session_start();
require_once __DIR__ . '/database/db.php';

// Ensure DB is available
if (! $conn) {
    die('Database connection error');
}

// Require logged-in user
$userEmail = $_SESSION['email'] ?? '';
if (empty($userEmail)) {
    // redirect to login and return to checkout after
    header('Location: login.php');
    exit();
}

// Load cart from session
$cart = $_SESSION['cart'] ?? [];
if (empty($cart)) {
    $_SESSION['checkout_message'] = 'Your cart is empty.';
    header('Location: check-out-page.php');
    exit();
}

// Resolve user id from email
$stmt = mysqli_prepare($conn, 'SELECT User_ID FROM login_credentials WHERE email = ? LIMIT 1');
mysqli_stmt_bind_param($stmt, 's', $userEmail);
mysqli_stmt_execute($stmt);
$res = mysqli_stmt_get_result($stmt);
$row = $res ? mysqli_fetch_assoc($res) : null;
mysqli_stmt_close($stmt);

if (! $row) {
    $_SESSION['checkout_message'] = 'Account not found; please log in again.';
    header('Location: login.php');
    exit();
}
$userId = (int)$row['User_ID'];

// Begin transaction
mysqli_begin_transaction($conn, MYSQLI_TRANS_START_READ_WRITE);
try {
    // Validate and lock inventories; also re-fetch canonical prices
    $cartItems = [];
    foreach ($cart as $key => $item) {
        $productId = isset($item['id']) ? (int)$item['id'] : 0;
        $qty = isset($item['quantity']) ? (int)$item['quantity'] : 0;
        if ($productId <= 0 || $qty <= 0) {
            throw new Exception('Invalid cart item.');
        }

        // Lock inventory row
        $stmt = mysqli_prepare($conn, 'SELECT p.Product_Price, COALESCE(pi.Stock,0) AS Stock FROM products p LEFT JOIN product_inventory pi ON p.Product_ID = pi.Product_ID WHERE p.Product_ID = ? FOR UPDATE');
        if (! $stmt) throw new Exception('Prepare failed: ' . mysqli_error($conn));
        mysqli_stmt_bind_param($stmt, 'i', $productId);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);
        $row = $res ? mysqli_fetch_assoc($res) : null;
        mysqli_stmt_close($stmt);

        if (! $row) throw new Exception('Product not found: ' . $productId);
        $price = (float)$row['Product_Price'];
        $stock = (int)$row['Stock'];
        if ($stock < $qty) throw new Exception('Insufficient stock for product ID ' . $productId);

        $cartItems[] = ['product_id' => $productId, 'qty' => $qty, 'price' => $price];
    }

    // Calculate total
    $total = 0.0;
    foreach ($cartItems as $it) $total += $it['price'] * $it['qty'];

    // Create transaction
    $stmt = mysqli_prepare($conn, 'INSERT INTO transactions (User_ID, Transaction_Type, Amount, Created_At) VALUES (?, ?, ?, NOW())');
    $type = 'Order';
    mysqli_stmt_bind_param($stmt, 'isd', $userId, $type, $total);
    if (! mysqli_stmt_execute($stmt)) throw new Exception('Failed to create transaction: ' . mysqli_stmt_error($stmt));
    $transactionId = mysqli_insert_id($conn);
    mysqli_stmt_close($stmt);

    // Create order
    $stmt = mysqli_prepare($conn, 'INSERT INTO orders (Transaction_ID, Order_Date, Status) VALUES (?, NOW(), ?)');
    $status = 'pending';
    mysqli_stmt_bind_param($stmt, 'is', $transactionId, $status);
    if (! mysqli_stmt_execute($stmt)) throw new Exception('Failed to create order: ' . mysqli_stmt_error($stmt));
    $orderId = mysqli_insert_id($conn);
    mysqli_stmt_close($stmt);

    // Insert items and decrement stock
    $stmtInsertItem = mysqli_prepare($conn, 'INSERT INTO order_items (Order_ID, Product_ID, Quantity, Unit_Price) VALUES (?, ?, ?, ?)');
    $stmtUpdateInv = mysqli_prepare($conn, 'UPDATE product_inventory SET Stock = Stock - ? WHERE Product_ID = ?');
    foreach ($cartItems as $it) {
        mysqli_stmt_bind_param($stmtInsertItem, 'iiid', $orderId, $it['product_id'], $it['qty'], $it['price']);
        if (! mysqli_stmt_execute($stmtInsertItem)) throw new Exception('Failed to insert order item: ' . mysqli_stmt_error($stmtInsertItem));

        mysqli_stmt_bind_param($stmtUpdateInv, 'ii', $it['qty'], $it['product_id']);
        if (! mysqli_stmt_execute($stmtUpdateInv)) throw new Exception('Failed to update inventory: ' . mysqli_stmt_error($stmtUpdateInv));
    }
    mysqli_stmt_close($stmtInsertItem);
    mysqli_stmt_close($stmtUpdateInv);

    // Create payment record (cash/complete for now)
    $stmt = mysqli_prepare($conn, 'INSERT INTO payments (Transaction_ID, Amount, Payment_Method, Gateway_ref, Payment_Date, Status) VALUES (?, ?, ?, NULL, NOW(), ?)');
    $method = 'cash';
    $payStatus = 'completed';
    mysqli_stmt_bind_param($stmt, 'idss', $transactionId, $total, $method, $payStatus);
    if (! mysqli_stmt_execute($stmt)) throw new Exception('Failed to create payment: ' . mysqli_stmt_error($stmt));
    mysqli_stmt_close($stmt);

    mysqli_commit($conn);

    // clear session cart and set checkout message
    unset($_SESSION['cart']);
    $_SESSION['checkout_message'] = 'Order confirmed! Thank you for your purchase.';
    header('Location: check-out-page.php');
    exit();

} catch (Exception $e) {
    mysqli_rollback($conn);
    // Log error server-side (could write to file); for now show message
    $_SESSION['checkout_message'] = 'Checkout failed: ' . $e->getMessage();
    header('Location: check-out-page.php');
    exit();
}

?>
