<?php
session_start();
include('confi.php');
include('function.php');

if(!isset($_SESSION['Login'])){
  header("Location: login.php");

}

$msg="";
$category="";
$order_number="";
$id="";

if(isset($_GET['id']) && $_GET['id']>0){
	$id=($_GET['id']);
	$row=mysqli_fetch_assoc(mysqli_query($conn,"select * from category where id='$id'"));
	$category=$row['category'];
	$order_number=$row['order_number'];
}
if(isset($_POST['submit'])){
	$category=($_POST['category']);
	$order_number=($_POST['order_number']);
	$added_on=date('Y-m-d h:i:s');
	
	if($id==''){
		$sql="select * from category where category='$category'";
	}else{
		$sql="select * from category where category='$category' and id!='$id'";
	}	
	if(mysqli_num_rows(mysqli_query($conn,$sql))>0){
		$msg="Category already added";
	}else{
		if($id==''){
			mysqli_query($conn,"INSERT INTO category(`category`, `order_number`, `status`, `added_on`) VALUES('$category','$order_number',1,'$added_on')");
		}else{
			mysqli_query($conn,"UPDATE category SET category='$category', order_number='$order_number' WHERE id='$id'");
		}
		
		redirect('category.php');
	}
}
  // IMG UPLOD QUERY
  // $filename  = $_FILES["ctgryimg"]["name"];
  // $tmpname   = $_FILES["ctgryimg"]["tmp_name"];
  // $folder    = "category_img/".$filename;

  // move_uploaded_file( $tmpname, $folder );

  
  //cho $ctgryname,"<br>", $folder;
  // $sql = "INSERT INTO `category`(`ctgry_name`, `image`) VALUES ('$ctgryname','$folder')";
  // $result = mysqli_query($conn,$sql);

  // if($result){
  //   echo "Successfully Add New Category";
  // } else{
  //   echo "Failed";
  // } 
?>



<!DOCTYPE html>
<!-- Coding by CodingNepal | www.codingnepalweb.com -->
<html lang="en" dir="ltr">
  <head>
    <meta charset="UTF-8">
    <title>  contact us </title>
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
        <span class="dashboard">Contact Us</span>
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
      <div class="overview-boxes">
        <div class="category_form">
          <div class="right-side">
            <h4 class="text-info ">Contact Us</h4>
        </div>
        </div>
      </div>
      <div class="container center-side">
        <div class="row">
            <div class="col">
            <div class="col-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <form class="forms-sample" method="post">
                    <div class="form-group">
                      <label for="exampleInputName1">Hotel Name</label>
                      <input type="text" class="form-control" placeholder="Enter Hotel Name" name="hotel_name" required >
                    </div>
                    <div class="form-group pt-3">
                      <label for="exampleInputEmail3" required>Phone Number</label>
                      <input type="textbox" class="form-control" placeholder="Enter your contact Number" name="phone_number" required>
                    
                    <button type="submit" class="btn btn-primary mr-2 mt-4" name="submit" style="margin-left: 470px;">Submit</button>
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