<?php
// Include your database connection file
include_once '../connection/connection.php';

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the form data
    $id = $_POST["id"];
    $productName = $_POST["productname"];
    $brand = $_POST["brand"];
    $description = $_POST["description"];
    $price = $_POST["price"];
    $quantity = $_POST["quantity"];

    // Check if the product name is changed
    $sql = "SELECT `productname` FROM `tblproduct` WHERE `productid` = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->bind_result($currentProductName);
    $stmt->fetch();
    $stmt->close();

    if ($currentProductName != $productName) {
        // Rename the image file if it exists
        $currentImageFileName = strtolower(str_replace(' ', '_', $currentProductName));
        $newImageFileName = strtolower(str_replace(' ', '_', $productName));
        $currentImagePathJPG = "../image/product-image/" . $currentImageFileName . ".jpg";
        $currentImagePathPNG = "../image/product-image/" . $currentImageFileName . ".png";
        $newImagePathJPG = "../image/product-image/" . $newImageFileName . ".jpg";
        $newImagePathPNG = "../image/product-image/" . $newImageFileName . ".png";

        if (file_exists($currentImagePathJPG)) {
            rename($currentImagePathJPG, $newImagePathJPG);
        } elseif (file_exists($currentImagePathPNG)) {
            rename($currentImagePathPNG, $newImagePathPNG);
        }
    }

    // Update the database
    $sql = "UPDATE `tblproduct` SET `productname` = ?, `brand` = ?, `description` = ?, `price` = ?, `quantity` = ? WHERE `productid` = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssiii", $productName, $brand, $description, $price, $quantity, $id);
    $stmt->execute();

    // Check if the update was successful
    if ($stmt->affected_rows > 0) {
        // Return a success response
        echo "Update successful";
    } else {
        // Return an error response
        echo "Error updating item: " . $conn->error;
    }

    // Close statement
    $stmt->close();
}

// Close connection
$conn->close();
?>
