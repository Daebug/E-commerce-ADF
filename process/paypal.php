<?php
// Include your database connection code
require_once('../connection/connection.php');


$data = json_decode(file_get_contents('php://input'), true);
$paymentID = $data['paymentID'];


$stmt = $conn->prepare("UPDATE tblpayment SET status = 'Paid' WHERE paymentid = ?");
$stmt->bind_param("i", $paymentID);
$stmt->execute();
$stmt->close();

// Respond with a success message
http_response_code(200);
echo json_encode(array('message' => 'Status updated to Paid'));
?>
