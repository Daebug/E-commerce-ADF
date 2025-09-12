<?php
session_start();
require_once('../connection/connection.php');

// Validate and sanitize inputs
if (!isset($_SESSION['userid']) || empty($_SESSION['userid'])) {
    die("User not logged in");
}

$userid = $_SESSION['userid'];
$productid = mysqli_real_escape_string($conn, $_POST['productid'] ?? '');
$quantity = mysqli_real_escape_string($conn, $_POST['quantity'] ?? '');

if (empty($productid) || empty($quantity)) {
    die("Invalid product or quantity");
}

// Check if the product already exists in the cart for the user
$checkCartQuery = "SELECT * FROM tblcart WHERE userid = '$userid' AND productid = '$productid'";
$checkCartResult = $conn->query($checkCartQuery);

if ($checkCartResult->num_rows > 0) {
    // Check if any existing entry has status "Processed"
    $hasProcessed = false;
    while ($row = $checkCartResult->fetch_assoc()) {
        if ($row['status'] == 'Processed') {
            $hasProcessed = true;
            break;
        }
    }

    if ($hasProcessed) {
        // Insert a new entry with status "Pending"
        $insertCartQuery = "INSERT INTO tblcart (userid, productid, quantity, status) VALUES ('$userid', '$productid', '$quantity', 'Pending')";
        if ($conn->query($insertCartQuery) === TRUE) {
            echo json_encode(array("message" => "Product added to cart successfully"));
        } else {
            echo json_encode(array("error" => "Error adding product to cart: " . $conn->error));
        }
    } else {
        // Update the quantity of the existing entry
        $row = $checkCartResult->fetch_assoc();
        $newQuantity = $row['quantity'] + $quantity;
        $updateCartQuery = "UPDATE tblcart SET quantity = '$newQuantity' WHERE userid = '$userid' AND productid = '$productid'";
        if ($conn->query($updateCartQuery) === TRUE) {
            echo json_encode(array("message" => "Product quantity updated in cart successfully"));
        } else {
            echo json_encode(array("error" => "Error updating product quantity in cart: " . $conn->error));
        }
    }
} else {
    // Product does not exist in the cart, insert new record with status "Pending"
    $insertCartQuery = "INSERT INTO tblcart (userid, productid, quantity, status) VALUES ('$userid', '$productid', '$quantity', 'Pending')";
    if ($conn->query($insertCartQuery) === TRUE) {
        echo json_encode(array("message" => "Product added to cart successfully"));
    } else {
        echo json_encode(array("error" => "Error adding product to cart: " . $conn->error));
    }
}

$conn->close();
?>
