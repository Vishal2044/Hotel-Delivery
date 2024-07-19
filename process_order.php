<?php
include('./admin/confi.php');

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    // Get form data
    $name = $conn->real_escape_string($_POST['name']);
    $mobile = $conn->real_escape_string($_POST['mobile']);
    $room = $conn->real_escape_string($_POST['room']);
    $instruction = isset($_POST['instruction']) ? $conn->real_escape_string($_POST['instruction']) : '';
    $coupon_code = isset($_SESSION['coupon_code']) ? $conn->real_escape_string($_SESSION['coupon_code']) : '';
    $discount_amount = isset($_SESSION['discount']) ? $_SESSION['discount'] : 0;
    
    // Calculate the total amount and discount
    $total_amount = 0;
    $cart = $_SESSION['cart'];
    foreach ($cart as $item) {
        $dish_price = $item['dish_price'];
        $quantity = $item['quantity'];
        $cgst = ($dish_price * $quantity) * 0.025; // Assuming CGST is 2.5%
        $sgst = ($dish_price * $quantity) * 0.025; // Assuming SGST is 2.5%
        $item_total = ($dish_price * $quantity) + $cgst + $sgst;
        $total_amount += $item_total;
    }

    // Calculate the grand total after discount
    $grand_total = $total_amount - $discount_amount;

    // Insert order details into the new_orders table
    $stmt = $conn->prepare("INSERT INTO new_orders (name, mobile, room, instruction, coupon_code, discount_amount, grand_total) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssid", $name, $mobile, $room, $instruction, $coupon_code, $discount_amount, $grand_total);

    if ($stmt->execute()) {
        // Get the last inserted order ID
        $order_id = $stmt->insert_id;

        // Insert order details into the save_orders table
        $new_order_stmt = $conn->prepare("INSERT INTO save_orders (name, mobile, room, instruction, coupon_code, discount_amount, grand_total) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $new_order_stmt->bind_param("sssssid", $name, $mobile, $room, $instruction, $coupon_code, $discount_amount, $grand_total);
        $new_order_stmt->execute();
        $new_order_id = $new_order_stmt->insert_id;

        // Insert order details into the paysts_orders table
        $paysts_order_stmt = $conn->prepare("INSERT INTO paysts_orders (name, mobile, room, instruction, coupon_code, discount_amount, grand_total) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $paysts_order_stmt->bind_param("sssssid", $name, $mobile, $room, $instruction, $coupon_code, $discount_amount, $grand_total);
        $paysts_order_stmt->execute();
        $paysts_order_id = $paysts_order_stmt->insert_id;

        // Insert cart items into the order_items table
        foreach ($cart as $item) {
            $dish_name = $conn->real_escape_string($item['dish_name']);
            $dish_price = $item['dish_price'];
            $quantity = $item['quantity'];
            $cgst = ($dish_price * $quantity) * 0.025; // Assuming CGST is 2.5%
            $sgst = ($dish_price * $quantity) * 0.025; // Assuming SGST is 2.5%
            $item_total = ($dish_price * $quantity) + $cgst + $sgst;

            $item_stmt = $conn->prepare("INSERT INTO new_order_items (order_id, dish_name, dish_price, quantity, cgst, sgst, discount_amount, item_total, grand_total) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $item_stmt->bind_param("isdiidddd", $order_id, $dish_name, $dish_price, $quantity, $cgst, $sgst, $discount_amount, $item_total, $grand_total);
            $item_stmt->execute();

            // Insert cart items into the save_order_items table
            $new_item_stmt = $conn->prepare("INSERT INTO save_order_items (order_id, dish_name, dish_price, quantity, cgst, sgst, discount_amount, item_total, grand_total) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $new_item_stmt->bind_param("isdiidddd", $new_order_id, $dish_name, $dish_price, $quantity, $cgst, $sgst, $discount_amount, $item_total, $grand_total);
            $new_item_stmt->execute();

            // Insert cart items into the paysts_order_items table
            $paysts_item_stmt = $conn->prepare("INSERT INTO paysts_order_items (order_id, dish_name, dish_price, quantity, cgst, sgst, discount_amount, item_total, grand_total) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $paysts_item_stmt->bind_param("isdiidddd", $paysts_order_id, $dish_name, $dish_price, $quantity, $cgst, $sgst, $discount_amount, $item_total, $grand_total);
            $paysts_item_stmt->execute();
        }

        // Clear the cart
        unset($_SESSION['cart']);
        unset($_SESSION['discount']);
        unset($_SESSION['coupon_code']);
        echo "<div class='alert alert-success' role='alert'>Order placed successfully!</div>";
    } else {
        echo "<div class='alert alert-danger' role='alert'>Error: " . $stmt->error . "</div>";
    }

    $stmt->close();
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .alert {
            margin-left: 40%;
            margin-top: 20px;
            color: green;
            border: 1px solid black;
            width: 17%;
        }
        body {
            background-color: #f8f9fa;
            font-family: 'Arial', sans-serif;
        }
        .container {
            margin-top: 50px;
        }
        .alert {
            margin-top: 20px;
        }
        h2 {
            color: #343a40;
            margin-bottom: 30px;
        }
        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }
        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #004085;
        }
    </style>
</head>
<body>
    <div class="container text-center">
        <h2>Order Confirmation</h2>
        <a href="index.php" class="btn btn-primary">Back to Cart</a>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
