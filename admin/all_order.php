<?php
session_start();
include 'confi.php';
include 'function.php';

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
        oi.discount_amount, o.coupon_code
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
            $date = new DateTime($row['created_at']);
            $formatted_date = $date->format('d-m-Y H:i:s');

            $save_orders[$order_id] = [
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
        $save_orders[$order_id]['total_price'] += $item_total_price;
        $cgst = ($item_total_price * 2.5) / 100;
        $sgst = ($item_total_price * 2.5) / 100;
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
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <title>All Order</title>
    <link rel="stylesheet" href="admin.css">
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        * {
            margin: 0px;
            padding: 0px;
        }

        @media print {
            .no-print {
                display: none;
            }
        }

        form {
            width: 500px;
        }
        .success {
            color: green; /* or any other color you prefer for success */
        }
    </style>
</head>
<body>
<div class="sidebar">
            <?php include('adminhead.php');?>
    </div>

<section class="home-section">
    <nav>
        <div class="sidebar-button">
            <i class='bx bx-menu sidebarBtn'></i>
            <span class="dashboard">All Orders</span>
        </div>
        <!-- Search Form -->
        <form method="GET" action="" style="margin-top: 70px;">
            <div class="search-box">
                <input type="text" name="search" class="form-control" placeholder="Search by Order ID, Name, Mobile, or Room" autocomplete="off" value="<?php echo htmlspecialchars($search_query); ?>">
                <button class="bx bx-search" type="submit"></button>
            </div>
            <div class="row" style="margin-top: 40px;">
                <div class="col-md-4">
                    <input type="date" name="start_date" class="form-control" placeholder="Start Date" style="padding-left: 5px; padding-right: 5px;" value="<?php echo htmlspecialchars($start_date); ?>">
                </div>
                <div class="col-md-4">
                    <input type="date" name="end_date" class="form-control" placeholder="End Date" style="padding-left: 5px; padding-right: 5px;" value="<?php echo htmlspecialchars($end_date); ?>">
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-primary">Filter</button>
                </div>
            </div>
        </form>
        <!-- HOTEL NAME hotel_name.php -->
        <?php include('hotel_name.php') ?>
    </nav>

    <div class="home-content">
        <div class="overview-boxes">
            <div class="">
                <div class="right-side">
                    <h3 class="text-primary">All Order</h3>
                </div>
            </div>
        </div>
        <div class="sales-boxes mt-3">
            <div class="recent-sales box">
                <div class="">
                    <div class="container" style="width: 1140px;">
                        <?php if (!empty($save_orders)): ?>
                            <?php foreach ($save_orders as $order_id => $order): ?>
                                <div class="card mb-3">
                                    <div class="card-header">
                                        <div class="row">
                                            <div class="col">
                                                <strong>Order ID:</strong> <span class="order-id"><?php echo $order_id; ?></span> &nbsp;
                                                <strong>Room Number:</strong> <?php echo $order['room']; ?> &nbsp;
                                                <strong>Contact Number:</strong> <?php echo $order['mobile']; ?> &nbsp;
                                                <strong>Time:</strong> <?php echo $order['created_at']; ?>  &nbsp; &nbsp; &nbsp;

                                                <button class="btn btn-success no-print" onclick="window.open('invoice.php?order_id=<?php echo $order_id; ?>', '_blank')">Print Order</button>

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
                                                    <td colspan="5" class="text-start"><strong>Grand Total:</strong></td>
                                                    <td><strong>₹<?php echo number_format($order['total_price'], 2); ?></strong></td>
                                                </tr>
                                                <tr>
                                                    <td colspan="5" class="text-start">
                                                        <strong class="success">Discount</strong>
                                                        <small class="success">(<?php echo htmlspecialchars($order['coupon_code']); ?>)</small>
                                                    </td>
                                                    <td>
                                                        <strong class="success">₹<?php echo number_format($order['discount_amount'], 2); ?></strong>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="5" class="text-start"><strong>TO  Pay:</strong></td>
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
    </div>
</section>

<script>
// function printOrder(button) {
//     var orderCard = button.closest('.card');
//     if (orderCard) {
//         var printWindow = window.open('', '', 'height=600,width=800');
//         printWindow.document.write('<html><head><title>Order Details</title>');
//         printWindow.document.write('<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">');
//         printWindow.document.write('</head><body>');
//         printWindow.document.write(orderCard.innerHTML);
//         printWindow.document.write('</body></html>');
//         printWindow.document.close();
//         printWindow.print();
//     }
// }

let sidebar = document.querySelector(".sidebar");
let sidebarBtn = document.querySelector(".sidebarBtn");
sidebarBtn.onclick = function() {
    sidebar.classList.toggle("active");
    if (sidebar.classList.contains("active")) {
        sidebarBtn.classList.replace("bx-menu", "bx-menu-alt-right");
    } else {
        sidebarBtn.classList.replace("bx-menu-alt-right", "bx-menu");
    }
}
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
