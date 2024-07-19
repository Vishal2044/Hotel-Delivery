<?php
session_start();
include 'confi.php';
include 'function.php';

if (!isset($_SESSION['Login'])) {
    header("Location: login.php");
}

$order_id = isset($_GET['order_id']) ? $_GET['order_id'] : '';

if (empty($order_id)) {
    echo "Invalid Order ID";
    exit;
}

$sql = "SELECT o.id AS order_id, o.name, o.mobile, o.room, o.instruction, o.created_at,
        oi.dish_name, oi.dish_price, oi.quantity, oi.item_total, oi.cgst, oi.sgst,
        oi.discount_amount, o.coupon_code
        FROM paysts_orders o
        JOIN paysts_order_items oi ON o.id = oi.order_id
        WHERE o.id = '$order_id'";

$result = $conn->query($sql);

if ($result->num_rows == 0) {
    echo "Order not found";
    exit;
}

$order = [];
while ($row = $result->fetch_assoc()) {
    if (empty($order)) {
        $date = new DateTime($row['created_at']);
        $formatted_date = $date->format('d-m-Y H:i:s');

        $order = [
            'name' => $row['name'],
            'mobile' => $row['mobile'],
            'room' => $row['room'],
            'instruction' => $row['instruction'],
            'created_at' => $formatted_date,
            'items' => [],
            'total_price' => 0,
            'discount_amount' => $row['discount_amount'],
            'coupon_code' => $row['coupon_code']
        ];
    }
    $item_total_price = $row['item_total'];
    $order['total_price'] += $item_total_price;
    $cgst = ($item_total_price * 2.5) / 100;
    $sgst = ($item_total_price * 2.5) / 100;
    $order['items'][] = [
        'dish_name' => $row['dish_name'],
        'dish_price' => $row['dish_price'],
        'quantity' => $row['quantity'],
        'item_total' => $item_total_price,
        'cgst' => $row['cgst'],
        'sgst' => $row['sgst'],
    ];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order Invoice</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <style>
        @media print {
            body{
                font-size: smaller;
            }
            .logo{
                font-weight: normal;
                font-size: 1.5rem
            }
            .invoic{
                font-weight: normal;
                font-size: 1.5rem
            }
            .print-btn {
                display: none;
            }
        }
    </style>
</head>
<body>
<div class="container mt-5">
    <div class="card">
        <div class="card-header">
            <nav class="navbar bg-body-tertiary">
                <div class="container-fluid">
                    <h3 class="logo fw-bolder">Hotel Name</h3>
                    <h3 class="invoice fw-bolder">INVOICE</h3>
                    </form>
                </div>
            </nav>
            <strong>Order ID:</strong> <?php echo $order_id; ?>  &nbsp; &nbsp;
            <strong>Room Number:</strong> <?php echo $order['room']; ?>  &nbsp;  &nbsp;
            <strong>Guest Name:</strong> <?php echo $order['name']; ?>  &nbsp; &nbsp; <br>

            <!-- <strong>Contact Number:</strong> <?php echo $order['mobile']; ?>  &nbsp; &nbsp; -->
            <strong>Instructions:</strong> <?php echo $order['instruction']; ?> &nbsp; &nbsp;
            <strong>Time:</strong> <?php echo $order['created_at']; ?>
        </div>
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th>Menu Item</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>CGST</th>
                        <th>SGST</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($order['items'] as $item): ?>
                        <tr>
                            <td><?php echo $item['dish_name']; ?></td>
                            <td><?php echo $item['quantity']; ?></td>
                            <td>₹<?php echo number_format($item['dish_price'], 2); ?></td>
                            <td>₹<?php echo number_format($item['cgst'], 2); ?></td>
                            <td>₹<?php echo number_format($item['sgst'], 2); ?></td>
                            <td>₹<?php echo number_format($item['item_total'], 2); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="5" class="text-start"><strong>Subtotal:</strong></td>
                        <td><strong>₹<?php echo number_format($order['total_price'], 2); ?></strong></td>
                    </tr>
                    <tr>
                        <td colspan="5" class="text-start">
                            <strong>Discount</strong>
                            <small>(<?php echo htmlspecialchars($order['coupon_code']); ?>)</small>
                        </td>
                        <td>
                            <strong>₹<?php echo number_format($order['discount_amount'], 2); ?></strong>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="5" class="text-start"><strong>Grand Total:</strong></td>
                        <td><strong>₹<?php echo number_format(($order['total_price'] - $order['discount_amount']), 2); ?></strong></td>
                    </tr>
                </tfoot>
            </table>
            <div class="text-center mt-3">
                <button class="btn btn-primary print-btn" onclick="window.print()">Print Invoice</button>
            </div>
            <div class="card-footer  invoice-footer mt-3">
                <nav class="navbar bg-body-tertiary">
                    <div class="container-fluid">
                        <p class="signature fw-light  ">Signature</p>
                        <p class="room_no fw-light">Room No <?php echo $order['room']; ?></p>
                        </form>
                    </div>
                </nav>
            </div>
            <div class="text-center mt-1">
                Thank you for your order!
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
