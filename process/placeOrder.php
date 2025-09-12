<?php
session_start();
require_once('../connection/connection.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve data from the form
    $userid = $_SESSION['userid'];
    $name = $_POST['name'];
    $contact = $_POST['contact'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $cartids = explode(',', $_POST['cartid']); // Split cartids into an array

    // Prepare and bind the statement for inserting into tblpayment
    $sqlInsertPayment = "INSERT INTO tblpayment (cartid, name, address, contact_number, email) VALUES (?, ?, ?, ?, ?)";
    $stmtInsertPayment = $conn->prepare($sqlInsertPayment);
    $stmtInsertPayment->bind_param("issss", $cartid, $name, $address, $contact, $email);

    // Prepare and bind the statement for updating status in tblcart
    $sqlUpdateCart = "UPDATE tblcart SET status = 'Processed' WHERE cartid = ?";
    $stmtUpdateCart = $conn->prepare($sqlUpdateCart);
    $stmtUpdateCart->bind_param("i", $cartidToUpdate);

    // Execute the statement for each cartid
    foreach ($cartids as $cartid) {
        $cartid = intval($cartid); // Convert to integer for security
        $stmtInsertPayment->execute();

        // Update status in tblcart
        $cartidToUpdate = $cartid;
        $stmtUpdateCart->execute();
    }

    $stmtInsertPayment->close();
    $stmtUpdateCart->close();
    $conn->close();

    // Redirect to a success page
    header("Location: ../ArdeurDeFrance-FrontEnd/ViewOrder.php");
    exit();
} else {
    // If the request method is not POST, redirect to an error page
    header("Location: ../ArdeurDeFrance-FrontEnd/Checkout.php");
    exit();
}
?>