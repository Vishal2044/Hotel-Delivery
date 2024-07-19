<?php
session_start();
include 'confi.php';
include 'function.php';

if (!isset($_SESSION['Login'])) {
    header("Location: login.php");
}

$order_id = isset($_GET['order_id']) ? $_GET['order_id'] : '';

if (empty($order_id)) {
    die('Order ID is required.');
}

// Fetch order details
$sql = "SELECT o.id AS order_id, o.name, o.mobile, o.room, o.instruction, o.created_at,
        oi.dish_name, oi.dish_price, oi.quantity, oi.item_total, oi.cgst, oi.sgst,
        oi.discount_amount, o.coupon_code
        FROM new_orders o
        JOIN new_order_items oi ON o.id = oi.order_id
        WHERE o.id = '$order_id'";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $order = $result->fetch_assoc();
} else {
    die('Order not found.');
}

// Fetch items for the order
$items_sql = "SELECT dish_name, dish_price, quantity, item_total, cgst, sgst
              FROM new_order_items
              WHERE order_id = '$order_id'";

$items_result = $conn->query($items_sql);

$items = [];

if ($items_result->num_rows > 0) {
    while ($item_row = $items_result->fetch_assoc()) {
        $items[] = $item_row;
    }
} else {
    die('No items found for this order.');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>KOT - Order <?php echo $order_id; ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        @media print {
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="row">
            <div class="col-6">
                <h3>KOT  <?php echo $order_id; ?></h3>
            </div>
            <div class="col-6">
                <p><strong>Room Number:</strong> <?php echo $order['room']; ?></p>
            </div>
            <div class="col">
                <p><strong>Instructions:</strong> <?php echo $order['instruction']; ?></p>
            </div>
        </div>
    </div>
                <!-- <p><strong>Contact Number:</strong> <?php echo $order['mobile']; ?></p>
                <p><strong>Customer Name:</strong> <?php echo $order['name']; ?></p> -->
                <div class="container">
                    <div class="row">
                        <div class="col">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Items Name</th>
                            <th>Quantity</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($items as $item): ?>
                            <tr>
                                <td><?php echo $item['dish_name']; ?></td>
                                <td><?php echo $item['quantity']; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <button class="btn btn-primary no-print" style="margin-left: 550px;" onclick="window.print();">Print KOT</button>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
