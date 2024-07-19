<?php
// Database connection
// Make sure you have a valid $conn variable for the database connection

// Count the number of new orders
$sql_count = "SELECT COUNT(DISTINCT order_id) AS num_new_orders FROM new_order_items";
$result_count = $conn->query($sql_count);
$num_new_orders = ($result_count->num_rows > 0) ? $result_count->fetch_assoc()['num_new_orders'] : 0;

// Get the current script name
$current_page = basename($_SERVER['PHP_SELF'], ".php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <!-- <link href='https://unpkg.com/boxicons/css/boxicons.min.css' rel='stylesheet'> -->

    <style>
        .sidebar {
            height: 100%;
            width: 250px;
            position: fixed;
            top: 0;
            left: 0;
            background-color: #0a2558;
            padding-top: 20px;
        }

        .sidebar a {
            padding: 15px 10px;
            text-decoration: none;
            font-size: 18px;
            color: white;
            display: block;
        }

        .sidebar a:hover, .sidebar a.active {
            background-color: #081D45;
        }

        .notification-icon {
            background-color: #ff4c4c;
            color: #fff;
            text-align: center;
            border-radius: 50%;
            padding: 3px 6px;
            font-size: 13px;
            position: absolute;
            top: 10px;
            right: 30px;
        }

        .bx-bell {
            padding-left: 30px;
            color: white;
            font-size: 22px;
        }

        .logo-details {
            display: flex;
            align-items: center;
            padding-bottom: 40px;
        }

        .logo-details i {
            font-size: 36px;
            color: white;
            margin-right: 10px;
        }

        .logo_name {
            font-size: 24px;
            color: white;
        }

        .nav-links {
            list-style: none;
            padding: 0;
        }
    </style>
</head>
<body>

<div class="sidebar">
    <div class="logo-details">
        <i class='bx bx-user'></i>
        <span class="logo_name">
            Admin
        </span>
    </div>
    <ul class="nav-links">
        <li id="dashboard"><a href="index.php" class="<?php echo $current_page == 'index' ? 'active' : ''; ?>"><i class='fa fa-dashboard'></i><span class="links_name">Dashboard</span></a></li>
        <li id="new_order">
            <a href="new_order.php" class="<?php echo $current_page == 'new_order' ? 'active' : ''; ?>">
                <i class='bx bx-cart'></i>
                <span class="links_name">New Order</span>
                <?php if ($num_new_orders > 0): ?>
                    <div class="bx bx-bell">
                        <span id="newOrderCount" class="notification-icon"><?php echo $num_new_orders; ?></span>
                    </div>
                <?php endif; ?>
            </a>
        </li>
        <li id="payment_status"><a href="payment_status.php" class="<?php echo $current_page == 'payment_status' ? 'active' : ''; ?>"><i class='bx bx-credit-card'></i><span class="links_name">Payment Status</span></a></li>
        <li id="category"><a href="category.php" class="<?php echo $current_page == 'category' ? 'active' : ''; ?>"><i class='bx bx-category'></i><span class="links_name">Category</span></a></li>
        <li id="menu"><a href="menu.php" class="<?php echo $current_page == 'menu' ? 'active' : ''; ?>"><i class='bx bx-restaurant'></i><span class="links_name">Menu</span></a></li>
        <li id="coupon"><a href="coupon.php" class="<?php echo $current_page == 'coupon' ? 'active' : ''; ?>"><i class='bx bx-purchase-tag'></i><span class="links_name">Coupon</span></a></li>
        <li id="all_order"><a href="all_order.php" class="<?php echo $current_page == 'all_order' ? 'active' : ''; ?>"><i class='bx bx-list-ul'></i><span class="links_name">All order</span></a></li>
        <li id="admin_profile"><a href="admin_profile.php" class="<?php echo $current_page == 'admin_profile' ? 'active' : ''; ?>"><i class='bx bx-user'></i><span class="links_name">My Profile</span></a></li>
        <!-- <li id="all_order"><a href="contact_us.php" class="<?php echo $current_page == 'contact_us' ? 'active' : ''; ?>"><i class='bx bx-list-ul'></i><span class="links_name">Contact Us</span></a></li> -->

        <li class="log_out"><a href="logout.php" class="<?php echo $current_page == 'logout' ? 'active' : ''; ?>"><i class='bx bx-log-out'></i><span class="links_name">Log out</span></a></li>
    </ul>
</div>

</body>
</html>
