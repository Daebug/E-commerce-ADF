<?php
require_once '../connection/connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['paymentid'])) {
    $paymentId = $_POST['paymentid'];

    // Fetch the orderNum associated with the paymentId
    $sqlFetchOrderNum = "SELECT `orderNum` FROM `tblpayment` WHERE `paymentid` = ?";
    $stmtFetchOrderNum = $conn->prepare($sqlFetchOrderNum);
    $stmtFetchOrderNum->bind_param('i', $paymentId);
    $stmtFetchOrderNum->execute();
    $result = $stmtFetchOrderNum->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $orderNum = $row['orderNum'];

        // Prepare the statement to update product quantities
        $sqlUpdateProduct = "UPDATE `tblproduct` p
                             JOIN `tblcart` c ON p.`productid` = c.`productid`
                             SET p.`quantity` = p.`quantity` - c.`quantity`
                             WHERE c.`cartid` IN (SELECT `cartid` FROM `tblpayment` WHERE `orderNum` = ?)";
        $stmtUpdateProduct = $conn->prepare($sqlUpdateProduct);
        $stmtUpdateProduct->bind_param('s', $orderNum);

        if ($stmtUpdateProduct->execute()) {
            // Prepare the statement to update orderStatus in tblpayment
            $sqlPayment = "UPDATE `tblpayment` SET `orderStatus` = 'To Receive', `remark` = 'Your order is on the way.' WHERE `orderNum` = ?";
            $stmtPayment = $conn->prepare($sqlPayment);
            $stmtPayment->bind_param('s', $orderNum);

            if ($stmtPayment->execute()) {
                $message = "Order status updated to 'To Receive' and remark updated for all orders with order number {$orderNum}. Quantities updated successfully.";
            } else {
                $message = "Error updating order status in tblpayment: " . $conn->error;
            }
        } else {
            $message = "Error updating quantity in tblproduct: " . $conn->error;
        }
    } else {
        $message = "No order found with the specified payment ID.";
    }

    // Close the prepared statements
    $stmtFetchOrderNum->close();
    $stmtUpdateProduct->close();
    $stmtPayment->close();
} else {
    $message = "Invalid request";
}

$conn->close();
?>

<!-- Output message and button with styling -->
<div style="margin-top: 20px; display: flex; justify-content: center; align-items: center; height: 100vh; flex-direction: column; text-align: center;">
    <div style="background-color: #f9f9f9; padding: 20px; border-radius: 10px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); max-width: 400px;">
        <p style="font-size: 18px; color: #333;"><?php echo $message; ?></p>
        <button onclick="window.location.href='javascript:history.back()'" style="padding: 10px 20px; font-size: 16px; cursor: pointer; border-radius: 5px; background-color: #4CAF50; color: white; border: none;">Go Back</button>
    </div>
</div>