<?php
session_start();
include './admin/confi.php';

// Initialize the cart if it's not set
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Function to calculate the cart total
function calculateCartTotal($cart) {
    $total = 0;
    foreach ($cart as $item) {
        $total += $item['dish_price'] * $item['quantity'];
    }
    return $total;
}

// Handle coupon code application
if (isset($_POST['apply_coupon'])) {
    $coupon_code = $_POST['coupon_code'];
    if (empty($coupon_code)) {
        $_SESSION['error'] = "Please enter a coupon code.";
    } else {
        $stmt = $conn->prepare("SELECT * FROM coupon_code WHERE coupon_code = ? AND status = '1' AND expired_on >= CURDATE()");
        $stmt->bind_param('s', $coupon_code);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $coupon = $result->fetch_assoc();
            $coupon_type = $coupon['coupon_type'];
            $coupon_value = $coupon['coupon_value'];
            $cart_min_value = $coupon['cart_min_value'];

            // Calculate cart total
            $cart_total = calculateCartTotal($_SESSION['cart']);

            if ($cart_total >= $cart_min_value) {
                if ($coupon_type == 'P') {
                    $discount = ($cart_total * $coupon_value) / 100 ;
                } else {
                    $discount = $coupon_value;
                }
                $_SESSION['discount'] = $discount;
                $_SESSION['coupon_code'] = $coupon_code;
                $_SESSION['final_price'] = $cart_total - $discount;
                $_SESSION['success'] = "Coupon applied successfully.";
            } else {
                $_SESSION['error'] = "Cart total must be at least $cart_min_value to use this coupon.";
            }
        } else {
            $_SESSION['error'] = "Invalid or expired coupon code.";
        }
        $stmt->close();
    }
    header("Location: cart.php");
    exit;
}

// Remove coupon code
if (isset($_POST['remove_coupon'])) {
    unset($_SESSION['discount']);
    unset($_SESSION['coupon_code']);
    unset($_SESSION['final_price']);
    header("Location: cart.php");
    exit;
}

// Fetch active coupons
$coupons = [];
$stmt = $conn->prepare("SELECT * FROM coupon_code WHERE status = '1' AND expired_on >= CURDATE()");
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $coupons[] = $row;
}
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Coupon Code</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f8f9fa;
            color: #333;
        }
        .container {
            margin-top: 50px;
        }
        .error {
            color: red;
        }
        .success {
            color: green;
        }
    </style>
</head>
<body>
<div class="container">
          <div class="row">
              <div class="col">
                  <p>
                      <a href="cart.php">Food Cart  / </a>  &nbsp;
                      <span href="#">Apply Coupon Code</span>
                  </p>
              </div>
          </div>
      </div>
    <div class="container">
        <!-- <h2 class="text-center">Apply Coupon Code</h2> -->
        <form method="post" action="coupon.php">
            <!-- <div class="form-group">
                <label for="coupon_code">Coupon Code</label>
                <input type="text" name="coupon_code" id="coupon_code" class="form-control">
            </div> -->
            <div id="coupon_code_msg">
                <?php if (isset($_SESSION['error'])): ?>
                    <div class="error"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
                <?php elseif (isset($_SESSION['success'])): ?>
                    <div class="success"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
                <?php endif; ?>
            </div>
            <!-- <button type="submit" name="apply_coupon" class="btn btn-primary">Apply Coupon</button> -->
            <?php if (isset($_SESSION['discount']) && isset($_SESSION['coupon_code'])): ?>
                <button type="submit" name="remove_coupon" class="btn btn-danger">Remove Coupon</button>
            <?php endif; ?>
        </form>

        <h3 class="text-success">Available Coupons</h3>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Coupon Code</th>
                    <th>Type</th>
                    <th>Value</th>
                    <th>Minimum Cart Value</th>
                    <th>Expires On</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($coupons as $coupon): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($coupon['coupon_code']); ?></td>
                        <td><?php echo $coupon['coupon_type'] == 'P' ? 'Percentage' : 'Fixed'; ?></td>
                        <td><?php echo $coupon['coupon_value']; ?><?php echo $coupon['coupon_type'] == 'P' ? '%' : ''; ?></td>
                        <td><?php echo $coupon['cart_min_value']; ?></td>
                        <td><?php echo $coupon['expired_on']; ?></td>
                        <td>
                            <form method="post" action="coupon.php">
                                <input type="hidden" name="coupon_code" value="<?php echo htmlspecialchars($coupon['coupon_code']); ?>">
                                <button type="submit" name="apply_coupon" class="btn btn-primary btn-sm">Apply</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
