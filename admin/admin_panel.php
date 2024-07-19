<?php
include 'confi.php';

if (!isset($_SESSION['Login'])) {
    header("Location: login.php");
}

// Fetch search query and date range if they exist
$search_query = isset($_GET['search']) ? $_GET['search'] : '';
$start_date = isset($_GET['start_date']) ? $_GET['start_date'] : '';
$end_date = isset($_GET['end_date']) ? $_GET['end_date'] : '';

// Build the SQL query with optional search and date range filters
$sql = "SELECT o.id AS order_id, o.name, o.mobile, o.room, o.instruction, o.created_at,
        oi.dish_name, oi.dish_price, oi.quantity, oi.item_total, oi.cgst, oi.sgst,
        oi.discount_amount, o.coupon_code  -- Fetch coupon_code from save_orders alias 'o'
        FROM save_orders o
        JOIN save_order_items oi ON o.id = oi.order_id
        WHERE 1=1";

if (!empty($search_query)) {
    $sql .= " AND (o.id LIKE '%$search_query%' OR o.name LIKE '%$search_query%' OR o.mobile LIKE '%$search_query%' OR o.room LIKE '%$search_query%' OR oi.dish_name LIKE '%$search_query%')";
}

if (!empty($start_date) && !empty($end_date)) {
    $sql .= " AND o.created_at BETWEEN '$start_date 00:00:00' AND '$end_date 23:59:59'";
}

$sql .= " ORDER BY o.created_at DESC, o.id ASC";

$result = $conn->query($sql);

$save_orders = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $order_id = $row['order_id'];
        if (!isset($save_orders[$order_id])) {
            // Format the date from YYYY-MM-DD to DD-MM-YYYY
            $date = new DateTime($row['created_at']);
            $formatted_date = $date->format('d-m-Y H:i:s');

            $save_orders[$order_id] = [
                'name' => $row['name'],
                'mobile' => $row['mobile'],
                'room' => $row['room'],
                'instruction' => $row['instruction'],
                'created_at' => $formatted_date,
                'items' => [],
                'total_price' => 0, // Initialize total price for the order
                'discount_amount' => $row['discount_amount'], // Store discount amount
                'coupon_code' => $row['coupon_code'] // Store coupon code
            ];
        }
        $item_total_price = $row['item_total']; // Use the item_total directly as it's already the total price for that item
        $save_orders[$order_id]['total_price'] += $item_total_price; // Sum up the total price for the order
        $cgst = ($item_total_price * 2.5) / 100; // Calculate CGST
        $sgst = ($item_total_price * 2.5) / 100; // Calculate SGST
        $save_orders[$order_id]['items'][] = [
            'dish_name' => $row['dish_name'],
            'dish_price' => $row['dish_price'],
            'quantity' => $row['quantity'],
            'item_total' => $item_total_price,
            'cgst' => $row['cgst'],
            'sgst' => $row['sgst'],
        ];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Orders</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div class="sales-boxes mt-3">
    <div class="recent-sales box">
        <div class="">
            <div class="container" style="width: 1140px;">
                <?php if (!empty($save_orders)): ?>
                    <?php
                    // Get the last 5 orders
                    $last_five_orders = array_slice($save_orders, -0, 5, true);
                    ?>
                    <?php foreach ($last_five_orders as $order_id => $order): ?>
                        <div class="card mb-3">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col">
                                        <strong>Order ID:</strong> <span class="order-id"><?php echo $order_id; ?></span> &nbsp;
                                        <strong>Room Number:</strong> <?php echo $order['room']; ?> &nbsp;
                                        <strong>Contact Number:</strong> <?php echo $order['mobile']; ?> &nbsp;
                                        <strong>Time:</strong> <?php echo $order['created_at']; ?>  &nbsp; &nbsp; &nbsp;
                                        <br>
                                        <strong>Customer Name:</strong> <?php echo $order['name']; ?> &nbsp;
                                        <strong>Instructions:</strong> <?php echo $order['instruction']; ?>
                                    </div>
                                </div>
                            </div>

                            <div class="card-body">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th scope="col">Dish Name</th>
                                            <th scope="col">Quantity</th>
                                            <th scope="col">Price</th>
                                            <th scope="col">CGST</th>
                                            <th scope="col">SGST</th>
                                            <th scope="col">Total</th>
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
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="alert alert-info">No orders found.</div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
