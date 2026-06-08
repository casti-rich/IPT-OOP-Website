<?php
session_start();
require_once __DIR__ . '/database/db.php';

function finalizeCheckout(mysqli $conn, int $userId, array $cart, string $paymentMethod): void
{
    mysqli_begin_transaction($conn, MYSQLI_TRANS_START_READ_WRITE);

    try {
        $cartItems = [];

        foreach ($cart as $item) {
            $productId = isset($item['id']) ? (int) $item['id'] : 0;
            $qty = isset($item['quantity']) ? (int) $item['quantity'] : 0;

            if ($productId <= 0 || $qty <= 0) {
                throw new Exception('Invalid cart item.');
            }

            $stmt = mysqli_prepare(
                $conn,
                'SELECT p.Product_Price, COALESCE(pi.Stock, 0) AS Stock
                FROM products p
                LEFT JOIN product_inventory pi ON p.Product_ID = pi.Product_ID
                WHERE p.Product_ID = ?
                FOR UPDATE'
            );

            if (! $stmt) {
                throw new Exception('Prepare failed: ' . mysqli_error($conn));
            }

            mysqli_stmt_bind_param($stmt, 'i', $productId);
            mysqli_stmt_execute($stmt);
            $res = mysqli_stmt_get_result($stmt);
            $row = $res ? mysqli_fetch_assoc($res) : null;
            mysqli_stmt_close($stmt);

            if (! $row) {
                throw new Exception('Product not found: ' . $productId);
            }

            $price = (float) $row['Product_Price'];
            $stock = (int) $row['Stock'];

            if ($stock < $qty) {
                throw new Exception('Insufficient stock for product ID ' . $productId);
            }

            $cartItems[] = [
                'product_id' => $productId,
                'qty' => $qty,
                'price' => $price,
            ];
        }

        $subtotal = 0.0;
        foreach ($cartItems as $item) {
            $subtotal += $item['price'] * $item['qty'];
        }

        $shipping = 1.00;
        $total = $subtotal + $shipping;

        $stmt = mysqli_prepare($conn, 'INSERT INTO transactions (User_ID, Transaction_Type, Amount, Created_At) VALUES (?, ?, ?, NOW())');
        if (! $stmt) {
            throw new Exception('Prepare failed: ' . mysqli_error($conn));
        }

        $transactionType = 'Order';
        mysqli_stmt_bind_param($stmt, 'isd', $userId, $transactionType, $total);
        if (! mysqli_stmt_execute($stmt)) {
            throw new Exception('Failed to create transaction: ' . mysqli_stmt_error($stmt));
        }

        $transactionId = mysqli_insert_id($conn);
        mysqli_stmt_close($stmt);

        $stmt = mysqli_prepare($conn, 'INSERT INTO orders (Transaction_ID, Order_Date, Status) VALUES (?, NOW(), ?)');
        if (! $stmt) {
            throw new Exception('Prepare failed: ' . mysqli_error($conn));
        }

        $status = 'pending';
        mysqli_stmt_bind_param($stmt, 'is', $transactionId, $status);
        if (! mysqli_stmt_execute($stmt)) {
            throw new Exception('Failed to create order: ' . mysqli_stmt_error($stmt));
        }

        $orderId = mysqli_insert_id($conn);
        mysqli_stmt_close($stmt);

        $stmtInsertItem = mysqli_prepare($conn, 'INSERT INTO order_items (Order_ID, Product_ID, Quantity, Unit_Price) VALUES (?, ?, ?, ?)');
        if (! $stmtInsertItem) {
            throw new Exception('Prepare failed: ' . mysqli_error($conn));
        }

        $stmtUpdateInv = mysqli_prepare($conn, 'UPDATE product_inventory SET Stock = Stock - ? WHERE Product_ID = ?');
        if (! $stmtUpdateInv) {
            throw new Exception('Prepare failed: ' . mysqli_error($conn));
        }

        foreach ($cartItems as $item) {
            mysqli_stmt_bind_param($stmtInsertItem, 'iiid', $orderId, $item['product_id'], $item['qty'], $item['price']);
            if (! mysqli_stmt_execute($stmtInsertItem)) {
                throw new Exception('Failed to insert order item: ' . mysqli_stmt_error($stmtInsertItem));
            }

            mysqli_stmt_bind_param($stmtUpdateInv, 'ii', $item['qty'], $item['product_id']);
            if (! mysqli_stmt_execute($stmtUpdateInv)) {
                throw new Exception('Failed to update inventory: ' . mysqli_stmt_error($stmtUpdateInv));
            }
        }

        mysqli_stmt_close($stmtInsertItem);
        mysqli_stmt_close($stmtUpdateInv);

        if ($paymentMethod === 'gcash') {
            $gatewayRef = 'GCASH-DEMO-' . $transactionId;
            $stmt = mysqli_prepare($conn, 'INSERT INTO payments (Transaction_ID, Amount, Payment_Method, Gateway_ref, Payment_Date, Status) VALUES (?, ?, ?, ?, NOW(), ?)');
            if (! $stmt) {
                throw new Exception('Prepare failed: ' . mysqli_error($conn));
            }

            $paymentStatus = 'completed';
            mysqli_stmt_bind_param($stmt, 'idsss', $transactionId, $total, $paymentMethod, $gatewayRef, $paymentStatus);
        } else {
            $stmt = mysqli_prepare($conn, 'INSERT INTO payments (Transaction_ID, Amount, Payment_Method, Gateway_ref, Payment_Date, Status) VALUES (?, ?, ?, NULL, NOW(), ?)');
            if (! $stmt) {
                throw new Exception('Prepare failed: ' . mysqli_error($conn));
            }

            $paymentStatus = 'completed';
            mysqli_stmt_bind_param($stmt, 'idss', $transactionId, $total, $paymentMethod, $paymentStatus);
        }

        if (! mysqli_stmt_execute($stmt)) {
            throw new Exception('Failed to create payment: ' . mysqli_stmt_error($stmt));
        }

        mysqli_stmt_close($stmt);
        mysqli_commit($conn);

        unset($_SESSION['cart'], $_SESSION['checkout_payment_method']);
        $_SESSION['checkout_message'] = 'Order confirmed! Thank you for your purchase.';
        header('Location: check-out-page.php');
        exit();
    } catch (Exception $e) {
        mysqli_rollback($conn);
        unset($_SESSION['checkout_payment_method']);
        $_SESSION['checkout_message'] = 'Checkout failed: ' . $e->getMessage();
        header('Location: check-out-page.php');
        exit();
    }
}

// Ensure DB is available
if (! $conn) {
    die('Database connection error');
}

// Require logged-in user
$userId = isset($_SESSION['user_id']) ? (int) $_SESSION['user_id'] : 0;
if ($userId <= 0) {
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

$paymentMethod = strtolower(trim((string) ($_POST['payment_method'] ?? ($_SESSION['checkout_payment_method'] ?? 'cash'))));
$paymentFlow = strtolower(trim((string) ($_POST['payment_flow'] ?? '')));

if ($paymentMethod !== 'cash' && $paymentMethod !== 'gcash') {
    $_SESSION['checkout_message'] = 'Unsupported payment method selected.';
    header('Location: check-out-page.php');
    exit();
}

if ($paymentMethod === 'gcash' && $paymentFlow === 'confirm' && ($_SESSION['checkout_payment_method'] ?? '') !== 'gcash') {
    $_SESSION['checkout_message'] = 'GCash payment session expired. Please start checkout again.';
    header('Location: check-out-page.php');
    exit();
}

if ($paymentFlow !== 'confirm') {
    $_SESSION['checkout_payment_method'] = $paymentMethod;
    if ($paymentMethod === 'gcash') {
        header('Location: payment_gateway_gcash.php');
    } else {
        header('Location: payment_gateway_cash.php');
    }
    exit();
}

finalizeCheckout($conn, $userId, $cart, $paymentMethod);

?>
