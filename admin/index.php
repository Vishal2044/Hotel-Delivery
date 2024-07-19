<?php
session_start();
include('confi.php');
include('function.php');

if (!isset($_SESSION['Login'])) {
    header("Location: login.php");
    exit();
}

// $hotel_name = isset($_SESSION['hotel_name']) ? $_SESSION['hotel_name'] : 'Hotel Name';
// $logo_img = isset($_SESSION['logo_img']) ? $_SESSION['logo_img'] : 'default.jpg';


// Count the number of new orders
$sql_count = "SELECT COUNT(*) AS num_new_orders FROM new_order_items";
$result_count = $conn->query($sql_count);
$num_new_orders = ($result_count->num_rows > 0) ? $result_count->fetch_assoc()['num_new_orders'] : 0;

?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="admin.css">
    <link href='https://unpkg.com/boxicons@2.1.1/css/boxicons.min.css' rel='stylesheet'>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <style>
        .overview-boxes .box-topic {
            font-size: 16px;
            color: green;
            font-weight: 00;
        }
        .home-content .box .number {
            font-size: 30px;
        }
        .home-content .box .cart{
            margin-left: 13px;
        }
        .more-sale{
            margin-left: 85%;
            list-style: none;
        }
       
    </style>
</head>
<body onload="startRefresh()">
    <div class="sidebar">
            <?php include('adminhead.php');?>
    </div>
<section class="home-section">
    <nav>
        <div class="sidebar-button">
            <i class='bx bx-menu sidebarBtn'></i>
            <span class="dashboard">Dashboard</span>
        </div>
        <div class="search-box">
                <input type="text" name="search" class="form-control" placeholder="Search by Name, Mobile, Room or Dish" value="<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>">
                <button class="bx bx-search" type="submit"></button>
        </div>
        </div>
        <div class="weblink">
            <a href="../index.php" target="_blank">Get Your Web</a>
        </div>
         <!-- HOTEL NAME hotel_name.php -->
         <?php include('hotel_name.php') ?>
    </nav>
    <div class="home-content">
        <div class="sales-boxes">
            <div class="recent-sales box">
                <div class="overview-boxes">
                    <div class="box">
                        <div class="right-side">
                            <div class="box-topic">Today Sale</div>
                            <div class="number mt-1">
                                <?php 
                                    $start=date('Y-m-d'). ' 00-00-00';
                                    $end=date('Y-m-d'). ' 23-59-59';
                                    echo getSale($start,$end);
                                ?>
                            </div>
                            <!-- <div class="indicator">
                                <i class='bx bx-up-arrow-alt'></i>
                                <span class="text">Up from yesterday</span>
                            </div> -->
                        </div>
                         <i class='bx bx-cart-alt cart'></i> 
                    </div>
                    <div class="box">
                        <div class="right-side">
                            <div class="box-topic">Yesterday Sale</div>
                            <div class="number mt-1">
                                <?php 
                                    $start = date('Y-m-d', strtotime('-1 day')) . ' 00:00:00';
                                    $end = date('Y-m-d', strtotime('-1 day')) . ' 23:59:59';
                                    echo getSale($start, $end);
                                ?>
                            </div>
                        </div>
                         <i class='bx bx-cart-alt cart'></i> 
                    </div>
                    <div class="box">
                        <div class="right-side">
                            <div class="box-topic">7 days Sale</div>
                                <div class="number  mt-1">
                                    <?php 
                                        $start=strtotime(date('Y-m-d'));
                                        $start=strtotime("-7 day",$start);
                                        $start=date('Y-m-d',$start);
                                        $end=date('Y-m-d'). ' 23-59-59';
                                        echo getSale($start,$end);
                                    ?>
                                </div>
                            <!-- <div class="indicator">
                                <i class='bx bx-up-arrow-alt'></i>
                                <span class="text">Up from yesterday</span>
                            </div> -->
                        </div>
                        <i class='bx bx-cart-alt cart'></i>
                    </div>
                    <div class="box">
                        <div class="right-side">
                            <div class="box-topic">30 Days Sale</div>
                            <div class="number  mt-1">
                                <?php 
                                    $start=strtotime(date('Y-m-d'));
                                    $start=strtotime("-30 day",$start);
                                    $start=date('Y-m-d',$start);
                                    $end=date('Y-m-d'). ' 23-59-59';
                                    echo getSale($start,$end);
                                ?>
                            </div>
                            <!-- <div class="indicator">
                                <i class='bx bx-up-arrow-alt'></i>
                                <span class="text">Up from yesterday</span>
                            </div> -->
                        </div>
                        <i class='bx bx-cart-alt cart'></i>
                    </div>
                    <!--more sale link-->
                    <li class="more-sale mt-2">
                        <a href="view_sales.php">
                            <i class='bx bx-bar-chart-alt-2 '></i>
                            <span class="links_name">More Sales Details</span>
                        </a>
                    </li>

                    <!-- <div class="box">
                        <div class="right-side">
                            <div class="box-topic">365 Days Sale</div>
                            <div class="number  mt-1">
                                <?php 
                                    $start=strtotime(date('Y-m-d'));
                                    $start=strtotime("-365 day",$start);
                                    $start=date('Y-m-d',$start);
                                    $end=date('Y-m-d'). ' 23-59-59';
                                    echo getSale($start,$end);
                                ?>
                            </div>
                            <div class="indicator">
                                <i class='bx bx-up-arrow-alt'></i>
                                <span class="text">Up from yesterday</span>
                            </div>
                        </div>
                        <i class='bx bx-cart-alt cart'></i>
                    </div> -->
                    
                  
                </div>
                <div class="title">Recent 5 Orders</div>
                <div class="sales-details">
                    <ul class="details">
                        <?php include('admin_panel.php'); ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>
<script>
    let sidebar = document.querySelector(".sidebar");
    let sidebarBtn = document.querySelector(".sidebarBtn");
    sidebarBtn.onclick = function() {
        sidebar.classList.toggle("active");
        if(sidebar.classList.contains("active")){
            sidebarBtn.classList.replace("bx-menu", "bx-menu-alt-right");
        } else {
            sidebarBtn.classList.replace("bx-menu-alt-right", "bx-menu");
        }
    }

    function startRefresh() {
            setTimeout("window.location.reload(true);", 5000); // Reload page every 5 seconds
        }
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
