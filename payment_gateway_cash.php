<?php
session_start();
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Gateway Cash</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@500&family=Space+Grotesk:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="CSS/payment_gateway_cash.css">
    <link rel="stylesheet" href="CSS/products-navbar.css">
    <link rel="stylesheet" href="CSS/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
</head>

<body class="gateway-theme">
    <?php include 'products-navbar.php'; ?>
    <main>
        <div class="container">
            <div class="d-flex flex-row gap-5 mt-3">
                <h2 style="width: 400px;">Cash On Pickup</h2>
            </div>
            <div class="d-flex flex-row gap-5">
                <div class="p-5 border border-secondary-subtle" style="width: 700px;" id="first-row">
                    <form aria-label="Cash on pickup form" method="post" action="payment_gateway_cash.php"> <!-- placeholder action -->
                        <h2 class="mt-3">Personal Details</h2>
                        <div class="d-flex flex-row mb-3">
                            <div class="me-3">
                                <label for="first-name" class="form-label">First Name</label>
                                <input type="text" class="form-control" id="first-name" placeholder="John">
                            </div>
                            <div class="me-3">
                                <label for="middle-name" class="form-label">Middle Name</label>
                                <input type="text" class="form-control" id="middle-name" placeholder="V.">
                            </div>
                            <div class="me-3">
                                <label for="last-name" class="form-label">Last Name</label>
                                <input type="text" class="form-control" id="last-name" placeholder="Doe">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="amount" class="form-label">Amount</label>
                            <input type="number" class="form-control" id="amount" placeholder="$100.00"> <!-- This would ideally be pre-filled with the total amount from the cart -->
                        </div>

                        <h2 class="mt-4">Contact Details</h2>
                        <div class="my-3">
                            <label for="number" class="form-label">Phone Number</label>
                            <input type="tel" class="form-control" id="number" placeholder="(123) 456-7890">
                        </div>
                        <div class="mb-3">
                            <label for="email-address" class="form-label">Email address</label>
                            <input type="email" class="form-control" id="email-address" placeholder="XXXX@gmail.com">
                        </div>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>

                <div class="p-5 border border-secondary-subtle" style="width: 450px;" id="second-row">
                    <h2 class="my-3">Summary of Orders</h2>
                    <div class="card" style="width: 20rem;">
                        <ul class="list-group list-group-flush p-2">
                            <li class="list-group-item">An item</li>
                            <li class="list-group-item">A second item</li>
                            <li class="list-group-item">A third item</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>

</html>