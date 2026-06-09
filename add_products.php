<?php
session_start();

require_once __DIR__ . '/database/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_name = $_POST['product_name'];
    $product_description = $_POST['product_description'];
    $product_price = $_POST['product_price'];
    $product_inventory = $_POST['product_inventory'];

    $filename = basename($_FILES['product_image']['name']);
    $target = 'Assets/Products/Placehold/' . $filename;

    if (move_uploaded_file($_FILES['product_image']['tmp_name'], $target)) {
        echo "File uploaded successfully.";
    } else {
        echo "Failed to move file. Check folder path and permissions.";
    }

    move_uploaded_file($_FILES['product_image']['tmp_name'], $target);

    // insert into products first
    $stmt = $conn->prepare("INSERT INTO products (Product_name, Product_Desc, Product_Price, Product_Image_Path) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $product_name, $product_description, $product_price, $target);
    $stmt->execute();

    // grab the new product's ID
    $product_id = $conn->insert_id;

    // insert into inventory using that ID
    $stmt2 = $conn->prepare("INSERT INTO product_inventory (Product_ID, Stock) VALUES (?, ?)");
    $stmt2->bind_param("is", $product_id, $product_inventory);
    $stmt2->execute();

    $success = "Product added successfully!";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard</title>
    <link rel="stylesheet" href="CSS/products-navbar.css">
    <link rel="stylesheet" href="CSS/style.css">
    <link rel="stylesheet" href="CSS/add_products.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
</head>

<body>
    <?php include 'products-navbar.php'; ?>
    <main>
        <div class="container">
            <div class="d-flex flex-row gap-5 mt-3">
                <h2 style="width: 400px;">Add Product</h2>
            </div>
            <div class="d-flex flex-row gap-5">
                <div class="p-5 border border-secondary-subtle" style="width: 700px;" id="first-row">
                    <form method="post" action="add_products.php" enctype="multipart/form-data">
                        <div id="preview-box">
                            <span id="placeholder">preview will appear here</span>
                        </div>
                        <div class="mb-3">
                            <label for="product-image" class="form-label mt-3" >Product Image</label>
                            <input type="file" id="product-image" accept="image/*" class="form-control" name="product_image" required>
                            <label for="product-name" class="form-label">Product Name</label>
                            <input type="text" class="form-control" id="product-name" name="product_name" placeholder="Enter product name" required>
                            <label for="product-description" class="form-label">Product Description</label>
                            <textarea class="form-control" id="product-description" name="product_description" rows="3" placeholder="Enter product description" required></textarea>
                            <label for="product-price" class="form-label">Product Price</label>
                            <input type="number" class="form-control" id="product-price" name="product_price" placeholder="Enter product price" step="0.01" required>
                            <label for="product-inventory" class="form-label">Product Inventory</label>
                            <input type="number" class="form-control" id="product-inventory" name="product_inventory" placeholder="Enter product inventory" required>
                            <input type="submit" class="btn btn-primary mt-3" value="Add Product">

                        </div>
                        <?php if (isset($success)): ?>
                            <div class="alert alert-success"><?= $success ?></div>
                        <?php endif; ?>
                    </form>
                </div>
            </div>
        </div>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>

    <script>
        const input = document.getElementById('product-image');
        const box = document.getElementById('preview-box');
        const placeholder = document.getElementById('placeholder');

        input.addEventListener('change', () => {
            const files = Array.from(input.files).slice(0, 4);
            box.innerHTML = '';

            files.forEach(file => {
                const reader = new FileReader();
                reader.onload = (e) => {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    box.appendChild(img);
                };
                reader.readAsDataURL(file);
            });
        });
    </script>
</body>

</html>