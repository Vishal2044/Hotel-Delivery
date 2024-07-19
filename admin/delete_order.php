<?php
include('confi.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $order_id = intval($_POST['order_id']);

    // Begin a transaction
    $conn->begin_transaction();

    try {
        // Delete from order_items
        $stmt1 = $conn->prepare("DELETE FROM new_order_items WHERE order_id = ?");
        $stmt1->bind_param("i", $order_id);
        $stmt1->execute();
        $stmt1->close();

        // Delete from orders
        $stmt2 = $conn->prepare("DELETE FROM new_orders WHERE id = ?");
        $stmt2->bind_param("i", $order_id);
        $stmt2->execute();
        $stmt2->close();

        // Commit the transaction
        $conn->commit();
        echo "success";
    } catch (Exception $e) {
        // Rollback the transaction
        $conn->rollback();
        echo "error: " . $e->getMessage();
    }

    $conn->close();
}
?>
