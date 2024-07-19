<?php
session_start();
include('confi.php');
include('function.php');

if(!isset($_SESSION['Login'])){
  header("Location: login.php");

}
$msg="";
$coupon_code="";
$coupon_type="";
$coupon_value="";
$cart_min_value="";
$expired_on="";
$id="";

if(isset($_GET['id']) && $_GET['id']>0){
	$id=($_GET['id']);
	$row=mysqli_fetch_assoc(mysqli_query($conn,"select * from coupon_code where id='$id'"));
	$coupon_code=$row['coupon_code'];
	$coupon_type=$row['coupon_type'];
	$coupon_value=$row['coupon_value'];
	$cart_min_value=$row['cart_min_value'];
	$expired_on=$row['expired_on'];
}

if(isset($_POST['submit'])){
	$coupon_code=($_POST['coupon_code']);
	$coupon_type=($_POST['coupon_type']);
	$coupon_value=($_POST['coupon_value']);
	$cart_min_value=($_POST['cart_min_value']);
	$expired_on=($_POST['expired_on']);
	$added_on=date('Y-m-d h:i:s');
	
	if($id==''){
		$sql="select * from coupon_code where coupon_code='$coupon_code'";
	}else{
		$sql="select * from coupon_code where coupon_code='$coupon_code' and id!='$id'";
	}	
	if(mysqli_num_rows(mysqli_query($conn,$sql))>0){
		$msg="Coupon code already added";
	}else{
		if($id==''){
			
			mysqli_query($conn,"insert into coupon_code(coupon_code,coupon_type,coupon_value,cart_min_value,expired_on,status,added_on) values('$coupon_code','$coupon_type','$coupon_value','$cart_min_value','$expired_on',1,'$added_on')");
		}else{
			mysqli_query($conn,"update coupon_code set coupon_code='$coupon_code', coupon_type='$coupon_type' , coupon_value='$coupon_value', cart_min_value='$cart_min_value', expired_on='$expired_on' where id='$id'");
		}
		
		redirect('coupon.php');
	}
}
?>



<!DOCTYPE html>
<!-- Coding by CodingNepal | www.codingnepalweb.com -->
<html lang="en" dir="ltr">
  <head>
    <meta charset="UTF-8">
    <title>Coupon Form</title>
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
        <span class="dashboard">Coupon</span>
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
                      <a href="coupon.php">Coupon / </a> 
                      <span href="#">Add New coupon</span>
                  </p>
              </div>
          </div>
      </div>
      <div class="overview-boxes">
        <div class="category_form">
          <div class="right-side">
            <h4 class="text-info ">Add New Coupon</h4>
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
                                    <label for="coupon">Coupon Code</label>
                                    <input type="text" class="form-control" placeholder="Coupon Code" name="coupon_code" required value="<?php echo $coupon_code?>">
                                    <div class="error mt8"><?php echo $msg?></div>
                                </div>
                                <div class="form-group pt-3">
                                    <label for="coupon_typ">Coupon Type</label>
                                    <select name="coupon_type" required class="form-control">
                                        <option value="">Select Type</option>
                                        <?php
                                            $arr=array('P'=>'Percentage','F'=>'Fixed');
                                            foreach($arr as $key=>$val){
                                                if($key==$coupon_type){
                                                    echo "<option value='".$key."' selected>".$val."</option>";
                                                }else{
                                                    echo "<option value='".$key."'>".$val."</option>";
                                                }
                                                
                                            }
                                        ?>
                                    </select>
                                
                                </div>
                                <div class="form-group pt-3">
                                    <label for="coupon_value" required>Coupon Value</label>
                                    <input type="textbox" class="form-control" placeholder="Coupon Value" name="coupon_value"  value="<?php echo $coupon_value?>">
                                </div>
                                <div class="form-group pt-3">
                                    <label for="cart_min_value" required>Cart Min Value</label>
                                    <input type="textbox" class="form-control" placeholder="Cart Min Value" name="cart_min_value"  value="<?php echo $cart_min_value?>">
                                </div>
                                <div class="form-group pt-3">
                                    <label for="expired_on">Expired On</label>
                                    <input type="date" class="form-control" placeholder="Expired On" name="expired_on"  value="<?php echo $expired_on?>">
                                </div>
                                
                                <button type="submit" class="btn btn-primary mt-3" name="submit" style="margin-left: 550px;">Submit</button>
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