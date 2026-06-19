<?php
session_start();

require_once __DIR__ . '/database/db.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit;
}

if (isset($_POST['add'])) {
    $product_name = trim($_POST['product_name']);
    $product_description = trim($_POST['product_description']);
    $product_price = trim($_POST['product_price']);
    $product_inventory = trim($_POST['product_inventory']);
    $product_status = trim($_POST['product_status']);
    $product_category = trim($_POST['product_category']);

    if ($product_price < 0) {
        $add_error = "Price cannot be negative.";
    } elseif ($product_inventory < 0) {
        $add_error = "Stock cannot be negative.";
    } else {
        $filename = basename($_FILES['product_image']['name']);
        $target = 'Assets/Products/Placehold/' . $filename;

        move_uploaded_file($_FILES['product_image']['tmp_name'], $target);

        $stmt = $conn->prepare("INSERT INTO products (Product_name, Product_Desc, Product_Category, Product_Price, Product_Image_Path, Product_Status) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssdss", $product_name, $product_description, $product_category, $product_price, $target, $product_status);
        $stmt->execute();

        $product_id = $conn->insert_id;

        $stmt2 = $conn->prepare("INSERT INTO product_inventory (Product_ID, Stock) VALUES (?, ?)");
        $stmt2->bind_param("is", $product_id, $product_inventory);
        $stmt2->execute();

        $success = "Product added successfully!";
    }
}

if (isset($_POST['update'])) {
    if (!empty($_POST['product_price']) && $_POST['product_price'] < 0) {
        $update_error = "Price cannot be negative.";
    } elseif (!empty($_POST['product_inventory']) && $_POST['product_inventory'] < 0) {
        $update_error = "Stock cannot be negative.";
    } else {
        $product_id = $_POST['product_id'];

        $fetch = $conn->prepare("SELECT * FROM products WHERE Product_ID = ?");
        $fetch->bind_param("i", $product_id);
        $fetch->execute();
        $result = $fetch->get_result()->fetch_assoc();

        if (!$result) {
            $update_error = "Product ID not found.";
        } else {
            $product_name = !empty($_POST['product_name']) ? $_POST['product_name'] : $result['Product_Name'];
            $product_description = !empty($_POST['product_description']) ? $_POST['product_description'] : $result['Product_Desc'];
            $product_category = !empty($_POST['product_category']) ? $_POST['product_category'] : $result['Product_Category'];
            $product_price = !empty($_POST['product_price']) ? $_POST['product_price'] : $result['Product_Price'];
            $product_status = !empty($_POST['product_status']) ? $_POST['product_status'] : $result['Product_Status'];

            if (!empty($_FILES['product_image']['name'])) {
                $filename = basename($_FILES['product_image']['name']);
                $target = 'Assets/Products/Placehold/' . $filename;
                move_uploaded_file($_FILES['product_image']['tmp_name'], $target);
            } else {
                $target = $result['Product_Image_Path'];
            }

            $stmt = $conn->prepare("UPDATE products SET Product_name = ?, Product_Desc = ?, Product_Category = ?, Product_Price = ?, Product_Image_Path = ?, Product_Status = ? WHERE Product_ID = ?");
            $stmt->bind_param("sssdssi", $product_name, $product_description, $product_category, $product_price, $target, $product_status, $product_id);
            $stmt->execute();

            if (!empty($_POST['product_inventory'])) {
                $stmt3 = $conn->prepare("UPDATE product_inventory SET Stock = ? WHERE Product_ID = ?");
                $stmt3->bind_param("si", $_POST['product_inventory'], $product_id);
                $stmt3->execute();
            }

            $update_success = "Product updated successfully!";
        }
    }
}

if (isset($_POST['delete'])) {
    $product_id = $_POST['product_id'];
    $product_status = $_POST['product_status'];

    if ($product_id <= 0) {
        $delete_error = "Invalid Product ID.";

    } else {
         // check if product exists
        $check = $conn->prepare("SELECT Product_ID FROM products WHERE Product_ID = ?");
        $check->bind_param("i", $product_id);
        $check->execute();
        $exists = $check->get_result()->fetch_assoc();

        if (!$exists) {

            $delete_error = "Product ID not found.";

        } else {

            $stmt = $conn->prepare("UPDATE products SET Product_Status = ? WHERE Product_ID = ?");
            $stmt->bind_param("si", $product_status, $product_id);
            $stmt->execute();

            $delete_success = "Product status updated to '$product_status' successfully!";
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="CSS/products-navbar.css">
    <link rel="stylesheet" href="CSS/style.css">
    <link rel="stylesheet" href="CSS/add_products.css">
</head>

<body class="dashboard">
    <?php include 'products-navbar.php'; ?>
    <main>
        <div class="container">
            <div class="d-flex flex-row gap-5 mt-3 ">
                <h2 style="color: white;">Manage Products</h2>
            </div>
            <div class="d-flex flex-row gap-0 mt-3">
                <!-- Add Product -->
                <div class="p-5 border border-secondary-subtle border-end-0" style="width: 700px;" id="first-row">
                    <h4 class="mb-3" style="color: white;">Add Product</h4>
                    <form method="post" action="dashboard.php" enctype="multipart/form-data">
                        <div id="preview-box">
                            <span id="placeholder">preview will appear here</span>
                        </div>
                        <div class="mb-3">
                            <label for="product-image" class="form-label mt-3">Product Image</label>
                            <input type="file" id="product-image" name="product_image" accept="image/*" class="form-control" required>
                            <label for="product-name" class="form-label mt-3">Product Name</label>
                            <input type="text" class="form-control" id="product-name" name="product_name" placeholder="Enter product name" required>
                            <label for="product-description" class="form-label mt-3">Product Description</label>
                            <textarea class="form-control" id="product-description" name="product_description" rows="3" placeholder="Enter product description" required></textarea>
                            <label for="product-category" class="form-label mt-3">Product Category</label>
                                <select class="form-select"
                                        id="product-category"
                                        name="product_category"
                                        required>
                                    <option value="Bass">Bass</option>
                                    <option value="Drum">Drum</option>
                                    <option value="Guitar">Guitar</option>
                                    <option value="Keyboard">Keyboard</option>
                                    <option value="Pedal">Pedal</option>
                                    <option value="Studio">Studio</option>
                                </select>
                            <label for="product-status" class="form-label mt-3">Product Status</label>
                                <select class="form-select"
                                        id="product-status"
                                        name="product_status"
                                        required>
                                    <option value="On Sale">On Sale</option>
                                    <option value="For Rent">For Rent</option>
                                    <option value="Discontinued">Discontinued</option>
                                </select>
                            <label for="product-price" class="form-label mt-3">Product Price</label>
                            <input type="number" class="form-control" id="product-price" name="product_price" placeholder="Enter product price" step="0.01" required>
                            <label for="product-inventory" class="form-label mt-3">Product Inventory</label>
                            <input type="number" class="form-control" id="product-inventory" name="product_inventory" placeholder="Enter product inventory" required>
                            <button type="submit" name="add" class="btn btn-primary mt-3">Add Product</button>
                        </div>
                        <?php if (isset($success)): ?>
                            <div class="alert alert-success"><?= $success ?></div>
                        <?php endif; ?>
                        <?php if (isset($add_error)): ?>
                            <div class="alert alert-danger"><?= $add_error ?></div>
                        <?php endif; ?>
                    </form>
                </div>
                <!-- Update -->
                <div class="p-5 border border-secondary-subtle border-start-0 border-end-0" style="width: 450px; " id="second-row">
                    <h4 class="mb-3" style="color: white;">Update Product</h4>
                    <form method="post" action="dashboard.php" enctype="multipart/form-data">
                        <div id="preview-box2">
                            <span id="placeholder2">preview will appear here</span>
                        </div>
                        <div class="mb-3">
                            <label class="form-label mt-3">Product Image</label>
                            <input type="file" class="form-control" name="product_image" id="product-image2" accept="image/*">
                            <label class="form-label mt-3">Product ID</label>
                            <input type="number" class="form-control" name="product_id" placeholder="Enter product ID">
                            <label class="form-label mt-3">Product Name</label>
                            <input type="text" class="form-control" name="product_name" placeholder="Enter new name">
                            <label class="form-label mt-3">Product Description</label>
                            <textarea class="form-control" name="product_description" rows="3" placeholder="Enter new description"></textarea>
                            <label for="product-category" class="form-label mt-3">Product Category</label>
                                <select class="form-select"
                                        id="product-category"
                                        name="product_category"
                                        required>
                                    <option value="Bass">Bass</option>
                                    <option value="Drum">Drum</option>
                                    <option value="Guitar">Guitar</option>
                                    <option value="Keyboard">Keyboard</option>
                                    <option value="Pedal">Pedal</option>
                                    <option value="Studio">Studio</option>
                                </select>
                            <label for="product-status" class="form-label mt-3">Product Status</label>
                                <select class="form-select"
                                        id="product-status"
                                        name="product_status"
                                        required>
                                    <option value="On Sale">On Sale</option>
                                    <option value="For Rent">For Rent</option>
                                    <option value="Discontinued">Discontinued</option>
                                </select>
                            <label class="form-label mt-3">Product Price</label>
                            <input type="number" class="form-control" name="product_price" step="0.01" placeholder="Enter new price">
                            <label class="form-label mt-3">Stock</label>
                            <input type="number" class="form-control" name="product_inventory" placeholder="Enter new stock">
                        </div>
                        <button type="submit" name="update" class="btn btn-warning">Update</button>
                        <?php if (isset($update_success)): ?>
                            <div class="alert alert-success mt-3"><?= $update_success ?></div>
                        <?php endif; ?>
                        <?php if (isset($update_error)): ?>
                            <div class="alert alert-danger mt-3"><?= $update_error ?></div>
                        <?php endif; ?>
                    </form>
                </div>

                <!-- Delete -->
                <div class="p-5 border border-secondary-subtle border-start-0" style="width: 450px;" id="third-row">
                    <h4 class="mb-3" style="color: white;">Delete Product</h4>
                    <form method="post" action="dashboard.php">
                        <div class="mb-3">
                            <label class="form-label mt-3">Product ID</label>
                            <input type="number" class="form-control" name="product_id" placeholder="Enter product ID" required>
                            <div class="input-group mt-3">
                                <label class="input-group-text" for="status-select">Status</label>
                                <select class="form-select" id="status-select" name="product_status">
                                    <option value="Discontinued">Discontinued</option>
                                </select>
                            </div>
                        </div>
                        <button type="submit" name="delete" class="btn btn-danger">Apply Status Update</button>
                        <?php if (isset($delete_success)): ?>
                            <div class="alert alert-success mt-3"><?= $delete_success ?></div>
                        <?php endif; ?>
                        <?php if (isset($delete_error)): ?>
                            <div class="alert alert-danger mt-3"><?= $delete_error ?></div>
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

        const input2 = document.getElementById('product-image2');
        const box2 = document.getElementById('preview-box2');
        const placeholder2 = document.getElementById('placeholder2');

        input2.addEventListener('change', () => {
            const files = Array.from(input2.files).slice(0, 4);
            box2.innerHTML = '';

            files.forEach(file => {
                const reader = new FileReader();
                reader.onload = (e) => {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    box2.appendChild(img);
                };
                reader.readAsDataURL(file);
            });
        });
    </script>
</body>

</html>