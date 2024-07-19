<?php
session_start();
include('confi.php');
include('function.php');

if (isset($_GET['id'])) {
    $admin_id = $_GET['id'];

    // Fetch the admin details based on the ID
    $query = "SELECT * FROM admin_ragister WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $admin_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $admin = $result->fetch_assoc();

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Process the form submission
        $username = $_POST['username'];
        $contact_number = $_POST['contact_number'];
        $email = $_POST['email'];
        $address = $_POST['address'];

        // Update the admin details in the database
        $update_query = "UPDATE admin_ragister SET username = ?, contac_number = ?, email = ?, addres = ? WHERE id = ?";
        $update_stmt = $conn->prepare($update_query);
        $update_stmt->bind_param("ssssi", $username, $contact_number, $email, $address, $admin_id);

        if ($update_stmt->execute()) {
            $_SESSION['message'] = "Admin details updated successfully";
        } else {
            $_SESSION['message'] = "Failed to update admin details";
        }

        $update_stmt->close();
        $conn->close();

        // Redirect back to the admin profile page
        header('Location: admin_profile.php');
        exit();
    }
} else {
    // Redirect if no ID is provided
    header('Location: admin_profile.php');
    exit();
}
?>

<!DOCTYPE html>
<!-- Coding by CodingNepal | www.codingnepalweb.com -->
<html lang="en" dir="ltr">
  <head>
    <meta charset="UTF-8">
    <title>update profile</title>
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
            text-transform: capitalize;

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
        <span class="dashboard">Update Profile</span>
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
            <h4 class="text-info ">Update Profile</h4>
        </div>
        </div>
      </div>
      <div class="container center-side">
        <div class="row">
            <div class="col">
            <div class="col-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                    <form action="" method="POST">
                        <div class="mb-3">
                            <label for="hotelname" class="form-label">Hotel Name</label>
                            <input type="text" class="form-control" id="hotelname" name="hotelname" value="<?php echo htmlspecialchars($admin['hotel_name']); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" class="form-control" id="username" name="username" value="<?php echo htmlspecialchars($admin['username']); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="contact_number" class="form-label">Contact Number</label>
                            <input type="text" class="form-control" id="contact_number" name="contact_number" value="<?php echo htmlspecialchars($admin['contac_number']); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($admin['email']); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="address" class="form-label">Address</label>
                            <input type="text" class="form-control" id="address" name="address" value="<?php echo htmlspecialchars($admin['addres']); ?>" required>
                        </div>
                        <div class="mb-3">
                            <a href="forgot_password.php">Password Change</a>
                        </div>
                        <button type="submit" class="btn btn-primary" style="margin-left: 450px;">Update</button>
                    </form>
                </div>
              </div>
            </div>
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