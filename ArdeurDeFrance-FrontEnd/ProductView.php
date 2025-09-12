<?php
session_start();
if (!isset($_SESSION["userid"])) {
    header("Location: LogInSignUpForm.php");
    exit();
}
$username = $_SESSION['username'];
$userid = $_SESSION['userid'];
require_once('../connection/connection.php');

// Get the productid from the URL
if (isset($_GET['productid'])) {
    $productid = $_GET['productid'];

    // SQL query to retrieve product data with the specified productid
    $sql = "SELECT `productid`, `productname`, `brand`, `description`, `price`, `quantity`, `productimg`, `filename`, `gender` FROM `tblproduct` WHERE `productid` = $productid";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Output data of the row
        $row = $result->fetch_assoc();
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
        <link rel="stylesheet" type="text/css" href="CSS-Files/productview.css">
        </head>
        <body>  
            <header id="MainFormHeader">
                    <div id="MainFormHeaderTopPart">
                        <div style="display: flex; align-items: center;">
                            <img id="MainFormHeaderLogo" src="Source-Files/Logo3.png" alt="">
                            <p>ARDEUR DE FRANCE</p>
                        </div>
                        <div style="display: flex; align-items: center;">
                            <div id="MainFormHeaderSearchBar">
                                <i id="MainFormHeaderSearchButton" class="fa-solid fa-magnifying-glass"></i>
                                <input id="MainFormHeaderSearchInput" type="text" placeholder="Seach">
                                <i id="MainFormHeaderDeleteTextButton" class="fa-solid fa-circle-xmark"></i>
                            </div>
                            <i id="MainFormHeaderMenuButton" class="menu-button fa-solid fa-bars"></i>
                            <div class="navbar" id="navbar">                        
                                <ul>
                                    <li><a href="MainForm.php"><i class="fa-solid fa-house-user"></i> Home </a></li>
                                    <li><a href="ViewCart.php"><i class="fa-solid fa-cart-shopping"></i> Cart </a></li>
                                    <li><a href="ViewOrder.php"><i class="fa-solid fa-box"></i> View Orders </a></li>
                                    <li><a href="Chat.php"><i class="fa-solid fa-message"></i> Chat </a></li>
                                    <li><a href="../process/logout.php"><i class="fa-solid fa-right-from-bracket"></i> Logout</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div id="MainHeaderButtons">
                        <button id="BrandsButton">BRANDS</button>
                        <button id="WomansPerfumeButton">WOMAN'S PERFUME</button>
                        <button id="MensCologneButton">MEN'S COLOGNE</button>
                        <button id="BestSellerButton">BEST SELLER</button>
                        <button id="GiftSetsButton">GIFTSETS</button>
                    </div>
            </header>
            <div class="whole">
                <div class="maincon">
                    <div class="picitem part">
                        <div class="conpic">
                            <?php
                            $imageExtensions = ['png', 'jpg', 'jpeg']; // List of allowed image extensions

                            // Construct the image filename with underscores instead of spaces
                            $imageFilename = strtolower(str_replace(' ', '_', $row['productname']));

                            // Loop through each extension and check if the image file exists
                            foreach ($imageExtensions as $extension) {
                                $imagePath = "../image/product-image/{$imageFilename}.$extension";
                                if (file_exists($imagePath)) {
                                    // Display the image with the correct extension
                                    echo "<img src='$imagePath' alt=''>";
                                    break; // Exit the loop once the image is found
                                }
                            }
                            ?>
                        </div>
                    </div>
                    <div class="add part">
                        <div class="conadd">
                            <div class="proname">
                                <h1><?php echo $row['productname']; ?></h1>
                            </div>
                            <div class="by">
                                <p>By &nbsp;</p><p class="under"><?php echo $row['brand']; ?></p><p>&nbsp; For <?php echo $row['gender']; ?></p>
                            </div>
                            <div class="price">
                                <p>₱</p><p><?php echo $row['price']; ?></p>
                            </div>
                            <div class="sizetit">
                                <p>Size: 100ml</p>
                            </div>
                            <div class="inStock">
                                <p>Stocks: <?php echo $row['quantity']; ?></p>
                            </div>
                            <div class="quantity">
                                <p>QTY:</p>
                                <div class="qtyinp">
                                    <input type="number" id="quantity" value="1">
                                </div>
                                <div class="qtybtn">
                                    <button onclick="addToCart(<?php echo $row['productid']; ?>)">ADD TO CART</button>
                                </div>
                            </div>
                            <div class="deline"></div>
                            <div class="desctit">
                                <p>DESCRIPTION:</p>
                                <p><?php echo $row['description']; ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <script defer type="text/javascript" src="JS-Scripts/nav.js"></script>
            <script>
                function addToCart(productId) {
                    const quantity = document.getElementById('quantity').value;
                    const formData = new FormData();
                    formData.append('userid', <?php echo $userid; ?>);
                    formData.append('productid', productId);
                    formData.append('quantity', quantity);

                    const xhr = new XMLHttpRequest();
                    xhr.open('POST', '../process/addtocart.php', true);
                    xhr.onreadystatechange = function() {
                        if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
                            alert('Product added to cart successfully');
                        }
                    };
                    xhr.send(formData);
                }
            </script>
            <script>
                function goToHome() {
                     window.location.href = 'MainForm.php';
                }
            </script>
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

    // document.addEventListener('DOMContentLoaded', function() {
    //     const ProductContainerOverlays = document.querySelectorAll('.ProductContainerOverlay');
    //     ProductContainerOverlays.forEach(overlay => {
    //         overlay.addEventListener('click', function() {
    //             // Get the productid from the hidden p element
    //             const productId = this.parentNode.querySelector('.ProductId').textContent;
    //             if (productId) {
    //                 window.location.href = `ProductView.php?productid=${productId}`;
    //             }
    //         });
    //     });
    // });

        
    </script>
        
        </html>
        <?php
    } else {
        echo "0 results";
    }
} else {
    echo "No productid specified in the URL";
}
$conn->close();
?>
