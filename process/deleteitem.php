<?php
// Include your database connection file
include_once '../connection/connection.php';

// Check if the request is sent via POST method
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the product ID from the POST data
    $productId = $_POST["productId"];

    // Get the product name from the database
    $sql = "SELECT `productname` FROM `tblproduct` WHERE `productid` = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $productId);
    $stmt->execute();
    $stmt->bind_result($productName);
    $stmt->fetch();
    $stmt->close();

    // Delete the row from the database
    $sql = "DELETE FROM `tblproduct` WHERE `productid` = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $productId);
    $stmt->execute();

    // Check if the deletion was successful
    if ($stmt->affected_rows > 0) {
        // Delete the image file
        $imagePath = "../image/product-image/" . $productName . ".*";
        $files = glob($imagePath);
        foreach ($files as $file) {
            unlink($file);
        }

        // Return a success response
        echo "Delete successful";
    } else {
        // Return an error response
        echo "Error deleting item: " . $conn->error;
    }

    // Close statement
    $stmt->close();
}

// Close connection
$conn->close();
?>
