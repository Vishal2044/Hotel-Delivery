<?php
session_start();
include('confi.php');
include('function.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit'])) {
  $open_time = $_POST['open_time'];
  $close_time = $_POST['close_time'];

  $update_query = "UPDATE restaurant_time SET open_time='$open_time', close_time='$close_time' WHERE id=1";
  if ($conn->query($update_query) === TRUE) {
      echo "";
      redirect('admin_profile.php');
  } else {
      echo "Error updating record: " . $conn->error;
  }
}

// Fetch the current open and close times
$query = "SELECT open_time, close_time FROM restaurant_time WHERE id=1";
$result = $conn->query($query);
$row = $result->fetch_assoc();
$open_time = $row['open_time'];
$close_time = $row['close_time'];
?>



<!DOCTYPE html>
<!-- Coding by CodingNepal | www.codingnepalweb.com -->
<html lang="en" dir="ltr">
  <head>
    <meta charset="UTF-8">
    <title>Update Time</title>
    <link rel="stylesheet" href="admin.css">
    <!-- Boxicons CDN Link -->
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

     <style>
        .category_form{
            margin-left: 500px;

        }
       
        .form-control{
            width: 100%;
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
        <span class="dashboard">Update Time</span>
      </div>
      <div class="search-box">
        <input type="text" placeholder="Search...">
        <i class='bx bx-search' ></i>
      </div>
      <div class="profile-details">
        <img src="images/profile.jpg" alt="">
        <b><span class="admin_name">hotel name</span></b>
      </div>
    </nav>

    <div class="home-content">
    <div class="container-fluid">
              <div class="row">
                  <div class="col">
                      <p>
                          <a href="admin_profile.php">Profile / </a> 
                          <span href="#">Update Profile</span>
                      </p>
                  </div>
              </div>
          </div>
      <div class="overview-boxes">
        <div class="category_form">
          <div class="right-side">
            <h4 class="text-info ">Restaurant Time</h4>
        </div>
        </div>
      </div>
      <div class="container center-side">
        <div class="row">
            <div class="col-md-12 ">
                <h5 style="margin-left: 25%;">Time :- </h5>
                <form action="" method="post">
                    <div class="mb-3 w-50" style="margin-left: 25%;">
                        <label for="open" class="form-label">Open Time</label>
                        <input type="time" class="form-control" id="open" name="open_time" value="<?php echo $open_time?>" required>
                    </div>
                    <div class="mb-3 w-50" style="margin-left: 25%;">
                        <label for="close" class="form-label">Close Time</label>
                        <input type="time" class="form-control" id="close" name="close_time" value="<?php echo $close_time?>" required>
                    </div>
                    <input type="submit" name="submit" value="save" class="btn btn-primary" style="margin-left: 45%;">
                </form>
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
  sidebarBtn.classList.replace("bx-menu" ,"bx-menu-alt-right");
}else
  sidebarBtn.classList.replace("bx-menu-alt-right", "bx-menu");
}
 </script>    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

</body>
</html>