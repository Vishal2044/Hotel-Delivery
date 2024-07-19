<?php
include('confi.php');
include('function.php');
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <title>More Sales</title>
    <link rel="stylesheet" href="admin.css">
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <style>
        .overview-boxes .box-topic {
            font-size: 16px;
            color: green;
            font-weight: 600;
        }
        .home-content .box .number {
            font-size: 30px;
        }
        .home-content .box .cart {
            margin-left: 13px;
        }
        .more-sale {
            margin-left: 88%;
            list-style: none;
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
            <span class="dashboard">More Sales</span>
        </div>
        <div class="search-box">
                <input type="text" name="search" class="form-control" placeholder="Search by Name, Mobile, Room or Dish" value="<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>">
                <button class="bx bx-search" type="submit"></button>
        </div>
        <div class="profile-details">
            <img src="images/profile.jpg" alt="">
            <b><span class="admin_name">hotel name</span></b>
        </div>
    </nav>
    
    <div class="home-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col m-2">
                    <p>
                        <a href="index.php">Dashboard / </a> 
                        <span href="#">Detailed Sales</span>
                    </p>
                </div>
            </div>
        </div>
        <div class="sales-boxes">
            <div class="recent-sales box">
                <div class="title">Detailed Sales Information</div>
                <form method="GET" action="view_sales.php">
                    <div class="row">
                        <div class="col-md-4">
                            <input type="date" name="start_date" class="form-control" placeholder="Start Date" required>
                        </div>
                        <div class="col-md-4">
                            <input type="date" name="end_date" class="form-control" placeholder="End Date" required>
                        </div>
                        <div class="col-md-4">
                            <button type="submit" class="btn btn-primary">Filter</button>
                        </div>
                    </div>
                </form>
                <div class="sales-details mt-4 ">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Name</th>
                                <th>Order Id#</th>
                                <th>Room No.</th>
                                <th>Sale</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                
// Get the start and end dates from the URL parameters
$start_date = isset($_GET['start_date']) ? $_GET['start_date'] . ' 00:00:00' : null;
$end_date = isset($_GET['end_date']) ? $_GET['end_date'] . ' 23:59:59' : null;

// Call the function to get sales
$sales = getAllSales($start_date, $end_date);

// Initialize the total amount
$total_amount = 0;

while ($row = $sales->fetch_assoc()) {
    $date = new DateTime($row['created_at']);
    $amount = floatval($row['grand_total']);
    $total_amount += $amount; // Add to the total amount

    echo "<tr>";
    echo "<td>" . $date->format('d-m-Y') . "</td>";
    echo "<td>" . htmlspecialchars($row['name']) . "</td>";
    echo "<td>" . htmlspecialchars($row['order_id']) . "</td>";
    echo "<td>" . htmlspecialchars($row['room']) . "</td>";
    echo "<td>" . htmlspecialchars(number_format($amount, 2)) . "</td>";
    echo "</tr>";
}
                                
                            ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="4" class="text-end"><strong >Total Sales</strong></td>
                                <td><strong><?php echo htmlspecialchars(number_format($total_amount, 2)); ?></strong></td>
                            </tr>
                        </tfoot>
                    </table>
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
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
