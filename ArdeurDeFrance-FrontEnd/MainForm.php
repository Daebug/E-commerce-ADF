<?php
session_start();
if (!isset($_SESSION["userid"])) {
    header("Location: LogInSignUpForm.php");
    exit();
}
$username = $_SESSION['username'];
$userid = $_SESSION['userid'];
require_once('../connection/connection.php')
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="author" content="">
    <meta name="keywords" content="">
    <meta name="description" content="">
    <meta http-equiv="" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>ArdeurDeFrance</title>

    <link rel="icon" type="image/png" href="">
    <link rel="stylesheet" type="text/css" href="CSS-Files/GeneralSheet.css">
    <link rel="stylesheet" type="text/css" href="CSS-Files/MainFormSheet.css">
    <link rel="stylesheet" type="text/css" href="CSS-Files/nav.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"/>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script defer type="text/css" type="text/javascript" src=""></script>
</head>
<body>
<header id="MainFormHeader">
    <div id="MainFormHeaderTopPart">
        <div style="display: flex; align-items: center;">
            <img id="MainFormHeaderLogo" src="Source-Files/Logo3.png" alt="">
            <p>ARDEUR DE FRANCE</p>
        </div>
        <div style="display: flex; align-items: center;">
            <div id="MainFormHeaderSearchBar" >
                <i id="MainFormHeaderSearchButton" class="fa-solid fa-magnifying-glass"></i>
                <input id="MainFormHeaderSearchInput" type="text" placeholder="Seach">
                <i id="MainFormHeaderDeleteTextButton" class="fa-solid fa-circle-xmark"></i>
            </div>
            <i id="MainFormHeaderMenuButton" class="menu-button fa-solid fa-bars"></i>
            <div class="navbar" id="navbar">                        
                <ul>
                <li><a href="MainForm.php"><i class="fa-solid fa-house-user"></i> Home </a></li>
                    <li><a href="profile-page/profile.php"><i class="fa-solid fa-user"></i> Profile </a></li>
                    <li><a href="ViewCart.php"><i class="fa-solid fa-cart-shopping"></i> Cart </a></li>
                    <li><a href="ViewOrder.php"><i class="fa-solid fa-box"></i> View Orders </a></li>
                    <li><a href="Chat.php"><i class="fa-solid fa-message"></i> Chat </a></li>
                    <li><a href="../process/logout.php"><i class="fa-solid fa-right-from-bracket"></i> Logout</a></li>
                </ul>
            </div>
        </div>
    </div>
    <div id="MainHeaderButtons">
        <button id="BrandsButton">SCENTS</button>
        <button id="WomansPerfumeButton">WOMAN'S PERFUME</button>
        <button id="MensCologneButton">MEN'S COLOGNE</button>
        <button id="BestSellerButton">BEST SELLER</button>
    </div>
</header>
<main id="MainFormMain">
    <div id="AddContainer">
        <img id="AddIMG" src="Source-Files/sale.jpg" alt="">
    </div>
    <div id="CheckBrandsLabelContainer">
        <div id="CheckBrandsLabel">Preferred cologne &#38; perfume labels</div>
        <a id="CheckBrandsButton">VIEW FULL SCENTS LIST &rarr;</a>
    </div>

    <div class="BestSellerContainer">
        <p>SCENTS FOR HER</p>
        <div class="BestProductContainer">
            <div class="BestSellerProductContainer">

                <?php
                // SQL query to retrieve product data with gender value 'Female'
                $sql = "SELECT `productid`, `productname`, `price`, `productimg` FROM `tblproduct` WHERE `gender` = 'Female'";
                $result = $conn->query($sql);

                // Check if there are any results
                if ($result->num_rows > 0) {
                    // Output data of each row
                    while($row = $result->fetch_assoc()) {
                        ?>
                        <div class="Product">
                            <div class="ProductContainerOverlay"></div>
                            <div class="ProductContainer">
                                <?php
                                $imageExtensions = ['png', 'jpg', 'jpeg']; // List of allowed image extensions
                                $productNameLowerCase = strtolower($row['productname']); // Convert product name to lowercase
                                $imageName = str_replace(' ', '_', $productNameLowerCase); // Replace spaces with underscores in the product name
                
                                // Loop through each extension and check if the image file exists
                                foreach ($imageExtensions as $extension) {
                                    $imagePath = "../image/product-image/{$imageName}.$extension";
                                    if (file_exists($imagePath)) {
                                        // Display the image with the correct extension
                                        echo "<div class='ImgProductContainer'><img src='$imagePath' alt=''></div>";
                                        break; // Exit the loop once the image is found
                                    }
                                }
                                ?>
                                <div style="display: flex; flex-direction: column;">
                                    <p class="ProductName"><?php echo $row["productname"]; ?></p>
                                    <p class="ProductPrice">₱<?php echo $row["price"]; ?></p>
                                    <p class="ProductId" hidden><?php echo $row["productid"]; ?></p>
                                    <div style="display: flex;">
                                        <!-- <i class="fa-solid fa-star"></i>
                                        <i class="fa-solid fa-star"></i>
                                        <i class="fa-solid fa-star"></i>
                                        <i class="fa-solid fa-star"></i>
                                        <i class="fa-solid fa-star"></i> -->
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                } else {
                    echo "0 results";
                }

// Close the connection
?>
            </div>
            <!-- <div id="BestSellerForWomenViewMoreButton"> -->
                <!-- <i class="fa-solid fa-chevron-right"></i> -->
            <!-- </div> -->
        </div>
    </div>
    <div class="BestSellerContainer">
    <p>SCENTS FOR HIM</p>
    <div class="BestProductContainer">
        <div class="BestSellerProductContainer">
            <?php
            // SQL query to retrieve product data with gender value 'Male'
            $sql = "SELECT `productid`, `productname`, `price`, `productimg` FROM `tblproduct` WHERE `gender` = 'Male'";
            $result = $conn->query($sql);

            // Check if there are any results
if ($result->num_rows > 0) {
    // Output data of each row
    while($row = $result->fetch_assoc()) {
        ?>
        <div class="Product">
            <div class="ProductContainerOverlay"></div>
            <div class="ProductContainer">
                <?php
                $imageExtensions = ['png', 'jpg', 'jpeg']; // List of allowed image extensions
                $productNameLowerCase = strtolower($row['productname']); // Convert product name to lowercase
                $imageName = str_replace(' ', '_', $productNameLowerCase); // Replace spaces with underscores in the product name

                // Loop through each extension and check if the image file exists
                foreach ($imageExtensions as $extension) {
                    $imagePath = "../image/product-image/{$imageName}.$extension";
                    if (file_exists($imagePath)) {
                        // Display the image with the correct extension
                        echo "<div class='ImgProductContainer'><img src='$imagePath' alt=''></div>";
                        break; // Exit the loop once the image is found
                    }
                }
                ?>
                <div style="display: flex; flex-direction: column;">
                    <p class="ProductName"><?php echo $row["productname"]; ?></p>
                    <p class="ProductPrice">₱<?php echo $row["price"]; ?></p>
                    <p class="ProductId" hidden><?php echo $row["productid"]; ?></p>
                    <div style="display: flex;">
                        <!-- <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i> -->
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
} else {
    echo "0 results";
}

            // Close the connection
            $conn->close();
            ?>
        </div>
        <!-- <div id="BestSellerForMenViewMoreButton"> -->
            <!-- <i class="fa-solid fa-chevron-right"></i> -->
        <!-- </div> -->
    </div>
</div>
        </main>
        <footer id="MainFormFooter">
            <div id="UpperFooter">
                <div id="UpperFooterLabelContainer1">
                    <p id="FooterLabel1">ARDEUR DE FRANCE</p>
                    <p id="FooterLabel2">Luxury that owns quality</p>
                </div>
                <div id="UpperFooterLabelContainer2">
                    <p id="FooterLabel3">FOLLOW US</p>
                    <div>
                        <i class="fa-brands fa-facebook-f"></i>
                        <i class="fa-brands fa-twitter"></i>
                        <i class="fa-brands fa-instagram"></i>
                    </div>
                </div>
            </div>
            <div id="BottomFooter">
                <p>2024 ARDEUR DE FRANCE. Fragrance retailer, zamboanga city. All Rights Reserved. </p>
                <div>
                    <a href="">PRIVACY POLICY</a>
                    <a href="">TERMS AND CONDITIONS</a> 
                </div>
            </div>
        </footer>
        <script defer type="text/javascript" src="JS-Scripts/nav.js"></script>
        <?php 
    // Show the Admin link only if the logged-in user's userid is 101
    if ($_SESSION["userid"] == 101) {
        ?>
        <script defer type="text/javascript">
            const navbar = document.querySelector('.navbar ul');
            const adminLink = document.createElement('li');
            adminLink.innerHTML = '<a href="admin-side/admin.php"><i class="fa-solid fa-desktop"></i> Admin </a>';
            navbar.appendChild(adminLink);
        </script>
        <?php
    }
    ?>
    </body> 
    <script defer type="text/javascript">
        const MainFormHeaderSearchInput = document.querySelector('#MainFormHeaderSearchInput');
        const MainFormHeaderDeleteTextButton = document.querySelector('#MainFormHeaderDeleteTextButton');
        const MainFormHeaderSearchButton = document.querySelector('#MainFormHeaderSearchButton');
        MainFormHeaderSearchButton.addEventListener('click', function() {
            if (MainFormHeaderSearchInput.style.display === 'flex') {
                MainFormHeaderSearchInput.style.display = '';
                MainFormHeaderDeleteTextButton.style.display = '';
            } else {
                MainFormHeaderSearchInput.style.display = 'flex';
                MainFormHeaderDeleteTextButton.style.display = 'flex';
            }
        });

        const CheckBrandsButton = document.querySelector('#CheckBrandsButton');
        CheckBrandsButton.addEventListener('click', function() {
            window.location.href = `WebForm.php`;
        });

        document.addEventListener('DOMContentLoaded', function() {
            const ProductContainerOverlays = document.querySelectorAll('.ProductContainerOverlay');
            ProductContainerOverlays.forEach(overlay => {
                overlay.addEventListener('click', function() {
                    // Get the productid from the hidden p element
                    const productId = this.parentNode.querySelector('.ProductId').textContent;
                    if (productId) {
                        window.location.href = `ProductView.php?productid=${productId}`;
                    }
                });
            });
        });

        
    </script>
</html>
