<?php
function sanitize_input($value) {
    return htmlspecialchars(trim($value));
}

session_start();
require_once('../connection/connection.php');

// Assuming form data is sent via POST
$userid = $_POST['userid'];
$name = $_POST['name'];
$productid = json_decode($_POST['productid']);
$quantity = json_decode($_POST['quantity']);
$amount = $_POST['amount'];
$address = $_POST['address'];
$contact = $_POST['contact'];
$email = $_POST['email'];

// Sanitize input values
$name = sanitize_input($name);
$address = sanitize_input($address);
$contact = sanitize_input($contact);
$email = sanitize_input($email);

// Insert into tblpayment
$sql = "INSERT INTO tblpayment (userid, name, productid, quantity, amount, address, contact_number, email, payment_date)
        VALUES ($userid, '$name', '$productid', '$quantity', '$amount', '$address', '$contact', '$email', NOW())";

if ($conn->query($sql) === TRUE) {
    echo "Payment successfully processed.";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
