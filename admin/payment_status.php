<?php
session_start();
include('confi.php'); // Ensure this file contains the database connection details.

if (!isset($_SESSION['Login'])) {
    header("Location: login.php");
    exit();
}

$searchRoom = isset($_GET['search']) ? $_GET['search'] : '';


// Fetch all invoices from the database
$sql = "
    SELECT so.room, SUM(so.grand_total) as grand_total, so.id
    FROM paysts_orders so
    WHERE so.room LIKE '%$searchRoom%'
    GROUP BY so.room";
$result = $conn->query($sql);

$rooms = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $rooms[] = $row;
    }
}

        // Handle AJAX request to delete data
        if (isset($_POST['roomId'])) {
            $roomId = $_POST['roomId'];
            
            /// Perform the deletion queries
        $deleteOrderSql = "DELETE FROM paysts_orders WHERE id = $roomId";
        $deleteItemsSql = "DELETE FROM paysts_order_items WHERE order_id = $roomId";

        if ($conn->query($deleteOrderSql) === TRUE && $conn->query($deleteItemsSql) === TRUE) {
            header('Location: payment_status.php'); // Redirect after successful deletion
            exit(); // Stop further execution
        } else {
            echo "Error deleting data: " . $conn->error;
        }

    }
    ?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <title>Payment Status</title>
    <link rel="stylesheet" href="admin.css">
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        .sales-boxes {
            text-transform: capitalize;
        }
        .box {
            width: 100%;
            height: 100px;
            background-color: #4CAF50;
            color: white;
            display: flex;
            align-items: flex-start;
            justify-content: flex-end;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin: 10px 0;
            padding: 10px;
            position: relative;
        }
        .box span {
            position: absolute;
            top: 10px;
            left: 10px;
            font-size: 1.2em;
            font-weight: bold;
        }
        .rs {
            position: absolute;
            bottom: 10px;
            left: 47px;
            font-size: 1.5em;
            font-weight: bold;
        }
        .sidebarr{
            background-color: #081D45;

        }
    </style>
</head>
<body onload="startRefresh()">
    <div class="sidebarr">
            <?php include('adminhead.php');?>
    </div>

    <section class="home-section">
        <!-- Navigation and home content -->
        <nav>
            <div class="sidebar-button">
                <i class='bx bx-menu sidebarBtn'></i>
                <span class="dashboard">Payment Status</span>
            </div>
            <form method="GET" action="">
                <div class="search-box">
                    <input type="text" name="search" class="form-control" placeholder="Search by  Room " value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                    <button class="bx bx-search" type="submit"></button>
                </div>
            </form>
            <!-- HOTEL NAME hotel_name.php -->
            <?php include('hotel_name.php') ?>
        </nav>
        <div class="home-content">
            <!-- Invoice boxes -->
            <div class="overview-boxes">
                <div class="">
                    <div class="text-center">
                        <h4 class="text-danger ">Panding Payment Status</h4>
                    </div>
                </div>
            </div>
            <div class="sales-boxes text-center">
                <div class="recent-sales">
                    <div class="container">
                        <div class="row">
                            <?php foreach ($rooms as $room): ?>
                                <div class="col-2">
                                    <div class="box">
                                        <span><?php echo htmlspecialchars($room['room']); ?></span>
                                        <p class="rs">
                                            <i class='bx bx-rupee'></i><?php echo htmlspecialchars($room['grand_total']); ?> 
                                        </p>
                                        <div class="ellipsis-vertical" onclick="confirmPayment(<?php echo $room['id']; ?>, this)">
                                            <i class='bx bx-dots-vertical-rounded' style="cursor: pointer; color:yellow ;"></i>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
        function confirmPayment(roomId, element) {
            if (confirm("Are you sure you want to confirm payment for this room?")) {
                // Send an AJAX request to delete data
                let xhr = new XMLHttpRequest();
                xhr.open("POST", "", true); // Empty URL since it's the same page
                xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                xhr.onreadystatechange = function () {
                    if (xhr.readyState === 4 && xhr.status === 200) {
                        // On success, remove the box and refresh the page
                        element.parentNode.removeChild(element);
                        window.location.reload(); // Reload the page to reflect changes
                    }
                };
                xhr.send("roomId=" + roomId);
            }
        }


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

        // function startRefresh() {
        //     setTimeout("window.location.reload(true);", 5000); // Reload page every 5 seconds
        // }
    </script>
</body>
</html>
