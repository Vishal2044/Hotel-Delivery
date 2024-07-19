<?php
session_start();
include('confi.php');
include('function.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['restaurant-status'])) {
    $status = $_POST['restaurant-status'];

    // Update the status in the database
    $query = "UPDATE restaurant_time SET status = ? WHERE id = 1";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $status);

    if ($stmt->execute()) {
        $_SESSION['message'] = "Status updated successfully";
    } else {
        $_SESSION['message'] = "Failed to update status";
    }

    $stmt->close();
    $conn->close();

    // Redirect back to the admin profile page
    header('Location: admin_profile.php');
    exit();
}

// Fetch restaurant status
$status_query = "SELECT status FROM restaurant_time WHERE id=1";
$status_result = $conn->query($status_query);
$status_row = $status_result->fetch_assoc();
$current_status = $status_row['status'];

// SQL query to fetch user data
$sql = "SELECT id, username, contac_number, email, addres, password, join_date FROM admin_ragister";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <title>Admin Profile</title>
    <link rel="stylesheet" href="admin.css">
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <style>
        .category_form {
            margin-left: 500px;
        }

        .form-control {
            width: 100%;
        }
    </style>
</head>
<body>
<div class="sidebar">
    <?php include('adminhead.php'); ?>
</div>

<section class="home-section">
    <nav>
        <div class="sidebar-button">
            <i class='bx bx-menu sidebarBtn'></i>
            <span class="dashboard">Admin Profile</span>
        </div>
        <div class="search-box">
            <input type="text" placeholder="Search...">
            <i class='bx bx-search'></i>
        </div>
         <!-- HOTEL NAME hotel_name.php -->
         <?php include('hotel_name.php') ?>
    </nav>

    <div class="home-content">
        <div class="overview-boxes">
            <div class="category_form">
                <div class="right-side">
                    <h4 class="text-info">Admin Profile</h4>
                </div>
            </div>
        </div>
        <div class="container center-side text-center">
            <div class="row">
                <div class="col">
                    <div class="container mt-3">
                        <table class="table table-striped table-bordered">
                            <thead class="table-info">
                                <tr>
                                    <th>Username</th>
                                    <th>Contact Number</th>
                                    <th>Email</th>
                                    <th>Address</th>
                                    <th>Join Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if ($result->num_rows > 0) {
                                    // Output data of each row
                                    while ($row = $result->fetch_assoc()) {
                                        echo "<tr>";
                                        echo "<td>" . htmlspecialchars($row["username"]) . "</td>";
                                        echo "<td>" . htmlspecialchars($row["contac_number"]) . "</td>";
                                        echo "<td>" . htmlspecialchars($row["email"]) . "</td>";
                                        echo "<td>" . htmlspecialchars($row["addres"]) . "</td>";

                                        // Convert and format the join_date
                                        $joinDate = new DateTime($row["join_date"]);
                                        echo "<td>" . $joinDate->format('d-m-Y') . "</td>";
                                        echo "<td>";
                                        echo '<a href="update_admin.php?id=' . $row["id"] . '" class="btn btn-primary">Modify</a>'; // Modify button
                                        echo "</td>";
                                        echo "</tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='6' class='text-center'>No results found</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="container pt-3">
            <div class="row">
                <div class="col">
                    <h5><b>Contact Us</b></h5>
                    <p>Contact Number : +91 9904802044 / +91 9904802044 &nbsp; &nbsp;&nbsp; email: infotech@gmail.com</p>
                </div>
            </div>
        </div>
        <div class="container mt-5">
            <div class="row">
                <div class="col">
                    <h5 class="mb-4 text-success" style="margin-right: 920px; white-space: nowrap;">Restaurant Time:
                        <?php
                        // Fetch the current open and close times
                        $query = "SELECT open_time, close_time FROM restaurant_time WHERE id=1";
                        $res = $conn->query($query);
                        $row = $res->fetch_assoc();
                        $open_time = $row['open_time'];
                        $close_time = $row['close_time'];

                        echo htmlspecialchars($row["open_time"]) . " TO " . htmlspecialchars($row["close_time"]);
                        ?>
                        <a class="btn btn-primary" href="restaurant_time.php">Change Time</a>
                    </h5>
                </div>
                <div class="col">
                    <form action="" method="POST">
                        <div class="form-group">
                            <label for="restaurant-status">Select Restaurant Status:</label>
                            <select class="form-control" id="restaurant-status" name="restaurant-status">
                                <option value="open" class="text-success" <?php echo $current_status == 'open' ? 'selected' : ''; ?>>Restaurant Open</option>
                                <option value="close" class="text-danger" <?php echo $current_status == 'close' ? 'selected' : ''; ?>>Restaurant Close</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary mt-3">Update Status</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    document.getElementById('restaurant-status').addEventListener('change', function () {
        var selectedOption = this.options[this.selectedIndex];
        this.classList.remove('text-success', 'text-danger');
        if (selectedOption.value === 'close') {
            this.classList.add('text-danger');
        } else {
            this.classList.add('text-success');
        }
    });

    let sidebar = document.querySelector(".sidebar");
    let sidebarBtn = document.querySelector(".sidebarBtn");
    sidebarBtn.onclick = function () {
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
