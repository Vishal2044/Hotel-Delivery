<?php
session_start();
include('confi.php');
include('function.php');


if(!isset($_SESSION['Login'])){
  header("Location: login.php");

}

if(isset($_GET['type']) && $_GET['type']!=='' && isset($_GET['id']) && $_GET['id']>0){
	$type=$_GET['type'];
	$id=$_GET['id'];
	if($type=='delete'){
		mysqli_query($conn,"delete from coupon_code where id='$id'");
		redirect('coupon.php');
	}
	if($type=='active' || $type=='deactive'){
		$status=1;
		if($type=='deactive'){
			$status=0;
		}
		mysqli_query($conn,"update coupon_code set status='$status' where id='$id'");
		redirect('coupon.php');
	}

}

$sql="select * from coupon_code order by id desc";
$result=mysqli_query($conn,$sql);

$search = '';
if (isset($_GET['search']) && $_GET['search'] !== '') {
    $search = $_GET['search'];
}

$sql = "SELECT * FROM coupon_code";
if ($search) {
    $sql .= " WHERE coupon_code LIKE '%$search%'";
}
$sql .= " ORDER BY id DESC";
$result = mysqli_query($conn, $sql);
?>



<!DOCTYPE html>
<!-- Coding by CodingNepal | www.codingnepalweb.com -->
<html lang="en" dir="ltr">
  <head>
    <meta charset="UTF-8">
    <title>  Coupon Creat</title>
    <link rel="stylesheet" href="admin.css">
    <!-- Boxicons CDN Link -->
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <style>
        *{
            margin: 0px;
            padding: 0px;
        }
        form{
        width: 500px;
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
        <span class="dashboard">Coupon</span>
      </div>
<!-- Search Form -->
    <form method="GET" action="">
        <div class="search-box">
                <input type="text" name="search" class="form-control" placeholder="Search by Coupon Name" value="<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>">
                <button class="bx bx-search" type="submit"></button>
            </div>
        </form>
       <!-- HOTEL NAME hotel_name.php -->
       <?php include('hotel_name.php') ?>
    </nav>

      <div class="home-content">
        <div class="overview-boxes">
            <div class="">
                <div class="right-side">
                    <a href="coupon_form.php"><button type="button" class="btn btn-primary">Add New Coupon</button></a>
                </div>
            </div>
        </div>
      
        <div class="sales-boxes text-center">
            <div class="recent-sales box">
                <div class="">
                    <table>
                        <thead>
                            <tr>
                                <th width="10%">Sr. No</th>
                                <th width="10%">Code/Value</th>
                                <th width="10%">Type</th>
                                <th width="15%">Cart Min</th>
                                <th width="15%">Expired On</th>
                                <th width="15%">Added On</th>
                                <th width="20%">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(mysqli_num_rows($result)>0){
                            $i=1;
                            while($row=mysqli_fetch_assoc($result)){
                            ?>
                           <tr>
                                <td><?php echo $i?></td>
                                <td><?php echo $row['coupon_code']?><br/>
                                  <?php echo $row['coupon_value']?>
                                </td>
                                <td><?php echo $row['coupon_type']?></td>
                                <td><?php echo $row['cart_min_value']?></td>
							                  <td>
							                    <?php 
                                    if($row['expired_on']=='0000-00-00'){
                                    
                                      }else{
                                          echo $row['expired_on'];
                                      }
                                      ?>
                                </td>
                                <td>
                                  <?php 
                                  $dateStr=strtotime($row['added_on']);
                                  echo date('d-m-Y',$dateStr);
                                  ?>
							                </td>
                              <td>
                                <a href="coupon_form.php?id=<?php echo $row['id']?>"><label class="btn btn-success hand_cursor">Edit</label></a>&nbsp;
                                <?php
                                if($row['status']==1){  
                                ?>
                                <a href="?id=<?php echo $row['id']?>&type=deactive"><label class="btn btn-info hand_cursor">Active</label></a>
                                <?php
                                }else{
                                ?>
                                <a href="?id=<?php echo $row['id']?>&type=active"><label class="btn btn-warning hand_cursor">Deactive</label></a>
                                <?php
                                }
                                ?>
                                &nbsp;
                                <a href="?id=<?php echo $row['id']?>&type=delete"><label class="btn btn-danger delete_red hand_cursor">Delete</label></a>
                              </td>        
                          </tr>
                            <?php 
                              $i++;
                              } } else { ?>
                              <tr>
                                <td colspan="5">No data found</td>
                              </tr>
                              <?php } ?>
                        </tbody>
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
  sidebarBtn.classList.replace("bx-menu" ,"bx-menu-alt-right");
}else
  sidebarBtn.classList.replace("bx-menu-alt-right", "bx-menu");
}
 </script>
 </script>    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

</body>
</html>
