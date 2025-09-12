<?php
require_once('../connection/connection.php');

// Get the payment ID from the request
$data = json_decode(file_get_contents("php://input"));
$paymentID = $data->paymentID;

// SQL query to update the status in tblcart to 'Pending'
$sql_update_cart = "UPDATE `tblcart` SET `status` = 'Pending' WHERE `cartid` = (SELECT `cartid` FROM `tblpayment` WHERE `paymentid` = '$paymentID')";

// SQL query to delete the payment record
$sql_delete_payment = "DELETE FROM `tblpayment` WHERE `paymentid` = '$paymentID'";

// Perform the update and delete operations
if ($conn->query($sql_update_cart) === TRUE && $conn->query($sql_delete_payment) === TRUE) {
    echo "Payment cancelled successfully";
} else {
    echo "Error cancelling payment: " . $conn->error;
}

$conn->close();
?>
