<?php
function pr($arr){
	echo '<pre>';
	print_r($arr);
}

function prx($arr){
	echo '<pre>';
	print_r($arr);
	die();
}

function redirect ($link){
    ?>
    <script>
        window.location.href='<?php echo $link?>';
    </script>
    <?php
    die();
}


function get_safe_value($str){
	global $conn;
	$str=mysqli_real_escape_string($conn,$str);
	return $str;

}

function getcartTotalPrice(){
	$cartArr=getUserFullCart();
	$totalPrice=0;
	foreach($cartArr as $list){
		$totalPrice=$totalPrice+($list['qty']*$list['price']);
	}
	return $totalPrice;
}
//today sale, yesterday sale, etc...
function getSale($start, $end) {
    global $conn;
    $query = $conn->prepare("SELECT SUM(grand_total) as grand_total FROM save_orders WHERE created_at BETWEEN ? AND ?");
    $query->bind_param('ss', $start, $end);
    $query->execute();
    $result = $query->get_result();
    $row = $result->fetch_assoc();
    $grand_total = $row['grand_total'] ? $row['grand_total'] : 0;
    return '₹' . round($grand_total);
}

//view sales.php (more sales)
function getAllSales($start_date = null, $end_date = null) {
    global $conn; // Assuming $conn is your database connection
    
    // Base query
    $query = "SELECT id AS order_id, name, mobile, room, instruction, created_at, grand_total
              FROM save_orders";
    
    // Add date filter if both start_date and end_date are provided
    if ($start_date && $end_date) {
        $query .= " WHERE created_at BETWEEN ? AND ?";
    }
    
    $query .= " ORDER BY created_at DESC";
    
    // Prepare statement
    $stmt = $conn->prepare($query);
    
    // Bind parameters if needed
    if ($start_date && $end_date) {
        $stmt->bind_param("ss", $start_date, $end_date);
    }
    
    // Execute query
    $stmt->execute();
    
    // Get result
    $result = $stmt->get_result();
    
    return $result;
}



// Recent 5 Orders
function getRecentOrders() {
    global $conn;
    $query = "SELECT * FROM save_order_items WHERE created_at >= CURDATE() ORDER BY created_at DESC LIMIT 5";
    $result = $conn->query($query);
    $orders = [];
    while ($row = $result->fetch_assoc()) {
        $orders[] = $row;
    }
    return $orders;
}


?>