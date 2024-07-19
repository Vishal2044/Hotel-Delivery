<?php
session_start();

// Simulate processing delay (2 seconds)
sleep(2);

// Clear the cart after successful order
unset($_SESSION['cart']);

// Redirect back to the cart page with a success message
header("Location: cart.php?success=1");
exit;
?>
