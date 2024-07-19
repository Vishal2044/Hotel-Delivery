<?php
// Razorpay API Key
$apiKey = "rzp_test_GE3meflCDuAQ0D";

// Retrieve posted data
$name = isset($_POST['name']) ? $_POST['name'] : '';
$mobile = isset($_POST['mobile']) ? $_POST['mobile'] : '';
$room = isset($_POST['room']) ? $_POST['room'] : '';
$instruction = isset($_POST['instruction']) ? $_POST['instruction'] : '';
$grand_total = isset($_POST['grand_total']) ? $_POST['grand_total'] : 0;
$discount = isset($_POST['discount']) ? $_POST['discount'] : 0;
$to_pay = isset($_POST['to_pay']) ? $_POST['to_pay'] : 0;
$order_id = 'OID'.rand(10,100).'END';
?>

<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://code.jquery.com/jquery-3.5.0.js"></script>    
    <link rel="stylesheet" href="razorpay.css">
</head>
<body>
<div class="row" style="padding:100px 300px;">
    <div class="col-50">
        <div class="container">
            <form action="index.php" method="POST" style="padding: 25px;">
                <div class="row">
                    <div class="col-25">
                        <h3 style="text-align: center; margin:20px 10px; font-family: lato;">Checkout Form</h3>
                        <label for="fname"><i class="fa fa-user"></i>Name</label>
                        <input type="text" id="fname" name="name" placeholder="John M. Doe" value="<?php echo htmlspecialchars($name); ?>" required>                        
                        <input type="hidden" value="<?php echo $order_id; ?>" name="orderid">
                        <input type="hidden" value="<?php echo $to_pay; ?>" name="amount">
                        <label for="mobile"><i class="fa fa-mobile"></i> Mobile Number</label>
                        <input type="text" id="mobile" name="mobile" placeholder="Mobile Number" value="<?php echo htmlspecialchars($mobile); ?>" required>
                        <label for="room"><i class="fa fa-address-card-o"></i> Room Number</label>
                        <input type="text" id="room" name="room" placeholder="155" value="<?php echo htmlspecialchars($room); ?>" required>
                        <label for="ins"><i class="fa fa-address-card-o"></i> Instruction</label>
                        <input type="text" id="ins" name="instruction" placeholder="Fast delivery...." value="<?php echo htmlspecialchars($instruction); ?>">
                        <label for="amt"><i class="fa fa-address-card-o"></i> Total Amount</label>
                        <input type="text" id="amt" name="grand_total" placeholder="₹149" value="<?php echo htmlspecialchars($grand_total - $discount, 2); ?>" readonly>
                    </div>
                </div>
                <button type="submit" name="submit" formaction="process_order.php" class="btn btn-secondary">Pay Now</button>
            </form>
        </div>
    </div>
</div>

<?php if ($_SERVER['REQUEST_METHOD'] == 'POST') { ?>
<script
    src="https://checkout.razorpay.com/v2/checkout.js"
    data-key="<?php echo $apiKey; ?>"
    data-amount="<?php echo $to_pay * 100;?>"
    data-currency="INR"
    data-id="<?php echo $order_id; ?>"    
    data-name="Foodies"
    data-description="Hotel Deliver"
    data-image="https://traidev.com/img/web-desgin-development.png"
    data-prefill.name="<?php echo htmlspecialchars($name); ?>"
    data-prefill.email="<?php echo htmlspecialchars($_POST['email']); ?>"
    data-prefill.contact="<?php echo htmlspecialchars($mobile); ?>"
    data-theme.color="#F37254"
></script>
<input type="hidden" custom="Hidden Element" name="hidden">
<?php } ?>
</body>
</html>