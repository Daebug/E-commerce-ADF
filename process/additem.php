<?php
// Include your database connection file
include_once '../connection/connection.php';

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the form data
    $itemName = $_POST["itemName"];
    $itemBrand = $_POST["itemBrand"];
    $itemDescription = $_POST["itemDescription"];
    $itemPrice = $_POST["itemPrice"];
    $itemQuantity = $_POST["itemQuantity"];
    $itemGender = $_POST["itemGender"];

    // Check if an image was uploaded
    if (isset($_FILES['imageUpload']) && $_FILES['imageUpload']['error'] == 0) {
        // Save image to directory
        $imageFileName = strtolower(str_replace(' ', '_', $itemName)); // Replace spaces with underscores and convert to lowercase
        $imageFileName = preg_replace('/[^A-Za-z0-9_]/', '', $imageFileName); // Remove any non-alphanumeric characters
        $imagePath = "../image/product-image/" . $imageFileName . '.' . pathinfo($_FILES['imageUpload']['name'], PATHINFO_EXTENSION);
        move_uploaded_file($_FILES['imageUpload']['tmp_name'], $imagePath);

        // Read the image file and convert it to binary data
        $imageData = file_get_contents($imagePath);

        // Insert data into the database
        $sql = "INSERT INTO tblproduct (productname, brand, description, price, quantity, productimg, filename, gender) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssdisss", $itemName, $itemBrand, $itemDescription, $itemPrice, $itemQuantity, $imageData, $imageFileName, $itemGender);
        $stmt->execute();

        // Check if the insertion was successful
        if ($stmt->affected_rows > 0) {
            header("Location: ../ArdeurDeFrance-FrontEnd/crud.php");
            exit();
        } else {
            echo "Error adding new item: " . $conn->error;
        }

        // Close statement
        $stmt->close();
    } else {
        echo "Error uploading image.";
    }

    // Close connection
    $conn->close();
}
?>
