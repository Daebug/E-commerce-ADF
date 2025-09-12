<?php
require_once('../connection/connection.php');

// Get the paymentID from the POST request
$data = json_decode(file_get_contents('php://input'), true);
$paymentID = $data['paymentID'];

// Update the status to 'Paid'
$sql = "UPDATE tblpayment SET status = 'Paid' WHERE paymentid = $paymentID";
$result = $conn->query($sql);

// Return a success response
if ($result) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false]);
}
?>
