<?php
include_once '../connection/connection.php';

$searchText = $_POST['searchText'];
$searchText = mysqli_real_escape_string($conn, $searchText);

if (empty($searchText)) {
    // If search text is empty, retrieve all products
    $sql = "SELECT `productid`, `productname`, `brand`, `description`, `price`, `quantity`, `productimg`, `filename`, `gender` FROM `tblproduct`";
} else {
    // If search text is not empty, filter products based on the search text
    $sql = "SELECT `productid`, `productname`, `brand`, `description`, `price`, `quantity`, `productimg`, `filename`, `gender` 
            FROM `tblproduct` 
            WHERE `productname` LIKE '%$searchText%'";
}

$result = mysqli_query($conn, $sql);

if ($result->num_rows > 0) {
    // Output data of each row
    while($row = $result->fetch_assoc()) {
        ?>
        <div class="SearchedProduct">
            <div class="SearchedProductContainerOverlay"></div>
            <div class="SearchedProductContainer">
            <?php
            $imageExtensions = ['png', 'jpg', 'jpeg']; // List of allowed image extensions

            // Loop through each extension and check if the image file exists
            foreach ($imageExtensions as $extension) {
                $imageName = str_replace(' ', '_', $row['productname']); // Replace spaces with underscores
                $imagePath = "../image/product-image/" . strtolower($imageName) . ".$extension";
                if (file_exists($imagePath)) {
                    // Display the image with the correct extension
                    echo "<div class='SearchedImgProductContainer'><img src='$imagePath' alt=''></div>";
                    break; // Exit the loop once the image is found
                }
            }
            ?>
                <div style="display: flex; flex-direction: column;">
                    <p class="SearchedProductName"><?php echo $row["productname"]; ?></p>
                    <p class="SearchedProductPrice">₱<?php echo $row["price"]; ?></p>
                    <p class="ProductId" hidden><?php echo $row["productid"]; ?></p>
                    <div style="display: flex;">
                        <?php
                        // For simplicity, I'm adding static stars. You can modify this part to dynamically generate stars based on a rating field in your tblproduct table.
                        for ($i = 0; $i < 5; $i++) {
                            // echo '<i class="fa-solid fa-star"></i>';
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
} else {
    echo "No products found";
}

mysqli_close($conn);
?>
