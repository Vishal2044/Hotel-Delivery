<?php
session_start();
include('confi.php');
include('function.php');


if(!isset($_SESSION['Login'])){
  header("Location: login.php");

}
$msg="";
$category_id="";
$dish="";
$dish_detail="";
$dish_price="";
$type="";
$id="";


if(isset($_GET['id']) && $_GET['id']>0){
	$id=($_GET['id']);
	$row=mysqli_fetch_assoc(mysqli_query($conn,"select * from dish where id='$id'"));
	$category_id=$row['category_id'];
	$dish=$row['dish'];
	$type=$row['type'];
	$dish_detail=$row['dish_detail'];
	$dish_price=$row['dish_price'];
}

if(isset($_POST['submit'])){
	
	$category_id=($_POST['category_id']);
	$dish=($_POST['dish']);
	$dish_detail=($_POST['dish_detail']);
	$type=($_POST['type']);
	$dish_price=($_POST['dish_price']);
	if($id==''){
		$sql="select * from dish where dish='$dish'";
	}else{
		$sql="select * from dish where dish='$dish' and id!='$id'";
	}	
	if(mysqli_num_rows(mysqli_query($conn,$sql))>0){
		$msg="Dish already added";
	}else{
    if($id==''){
    mysqli_query($conn,"insert into dish(category_id,dish,dish_detail,status,dish_price,type) values('$category_id','$dish','$dish_detail',1,'$dish_price','$type')");
    }else{
      mysqli_query($conn,"UPDATE dish SET category_id='$category_id' , dish='$dish' , dish_detail='$dish_detail' , type='$type' , dish_price='$dish_price'  WHERE id='$id'");
      
    }
  }
  redirect('menu.php');

}

$result_category=mysqli_query($conn,"select * from category where status='1' order by category asc");
$arrType=array("veg","non-veg");


?>




<!DOCTYPE html>
<!-- Coding by CodingNepal | www.codingnepalweb.com -->
<html lang="en" dir="ltr">
  <head>
    <meta charset="UTF-8">
    <title>Menu Foem</title>
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
        *{
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
        <span class="dashboard">Add Menu Items</span>
      </div>
      <div class="search-box">
        <input type="text" placeholder="Search...">
        <i class='bx bx-search' ></i>
      </div>
       <!-- HOTEL NAME hotel_name.php -->
       <?php include('hotel_name.php') ?>
    </nav>

    <div class="home-content">
      <div class="container-fluid">
          <div class="row">
              <div class="col">
                  <p>
                      <a href="menu.php">Menu  / </a> 
                      <span href="#">Add New Menu</span>
                  </p>
              </div>
          </div>
      </div>
      <div class="overview-boxes">
        <div class="category_form">
          <div class="right-side">
            <h4 class="text-info ">Add New Items</h4>
          </div>
        </div>
      </div>
      <div class="container center-side">
        <div class="row">
            <div class="col">
            <div class="col-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                <form class="forms-sample" method="post" enctype="multipart/form-data">
                    <div class="form-group">
                      <label for="exampleInputName1">Category</label>
                        <select class="form-control" name="category_id" required>
                          <option value="">Select Category</option>
                            <?php
                              while($row_category=mysqli_fetch_assoc($result_category)){
                                if($row_category['id']==$category_id){
                                  echo "<option value='".$row_category['id']."' selected>".$row_category['category']."</option>";
                                }else{
                                  echo "<option value='".$row_category['id']."'>".$row_category['category']."</option>";
                                }
                              }
                            ?>
                        </select>
                    </div>  
                    <div class="form-group">
                      <label for="exampleInputName1">Menu Items</label>
                      <input type="text" class="form-control" placeholder="Items Name" name="dish" required value="<?php echo $dish?>">
                      <div class="error mt8"><?php echo $msg?></div>
                    </div>

                    <!-- <div class="form-group">
                      <label for="exampleInputName1">Type</label>
                      <select class="form-control" name="type" required>
                        <option value="">Select Type</option>
                        <?php 
                          foreach($arrType as $list){
                            if($list==$type){
                              echo "<option value='$list' selected>".strtoupper($list)."</option>";
                            }else{
                              echo "<option value='$list'>".strtoupper($list)."</option>";
                            }
                          }
                        ?>
                      </select>
                    </div> -->
                    <div class="form-group">
                      <label for="exampleInputEmail3" required>Item Detail</label>
                      <textarea name="dish_detail" class="form-control" placeholder="Dish Detail"><?php echo $dish_detail?></textarea>
                    </div>

                    <div class="form-group">
                      <label for="exampleInputEmail3" required>Item Price</label>

                            <input type="number" class="form-control" placeholder="Price" name="dish_price" required value="<?php echo $dish_price?>">
                          </div>
                          
                      
						
					
                    <button type="submit" class="btn btn-primary mt-4 text-center"  style="margin-left: 550px;" name="submit">Submit</button>
					
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