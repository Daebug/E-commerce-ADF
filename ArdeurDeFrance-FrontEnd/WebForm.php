<?php
session_start();
if (!isset($_SESSION["userid"])) {
    header("Location: LogInSignUpForm.php");
    exit();
}
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
        <link rel="stylesheet" type="text/css" href="CSS-Files/WebFormSheet.css">
        <link rel="stylesheet" href="CSS-Files/nav.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"/>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script defer type="text/javascript" src=""></script>
    </head>
    <body>
        <header id="WebFormHeader">
            <div id="WebFormHeaderTopPart">
                <div style="display: flex; align-items: center;">
                    <img id="WebFormHeaderLogo" src="Source-Files/Logo3.png" alt="">
                    <p>ARDEUR DE FRANCE</p>
                </div>
                <div style="display: flex; align-items: center;">
                   
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
            <div id="WebHeaderButtons">
                <button id="BrandsButton">BRANDS</button>
                <button id="WomansPerfumeButton">WOMAN'S PERFUME</button>
                <button id="MensCologneButton">MEN'S COLOGNE</button>
                <button id="BestSellerButton">BEST SELLER</button>
            </div>
        </header>
        <main id="WebFormMainUI">
            <div id="WebFormMainUIContainer">
                <div id="WebFormMainUIContainerLeft">
                    
                    <!-- <p id="WebFormMainUIContainerLeftLabel">SORT BY</p>
                    <div id="FilterCategoryContainer">
                        <div class="FilterCategory">
                            <p class="CategoryLabel">Gender</p>
                            <div class="FilterCheckboxContainer">
                                <div style="display: flex; align-items: center;"><input type="checkbox"><p>Men</p></div>
                                <p style="margin-right: 0.5rem;">269</p>
                            </div>
                            <div class="FilterCheckboxContainer">
                                <div style="display: flex; align-items: center;"><input type="checkbox"><p>Women</p></div>
                                <p style="margin-right: 0.5rem;">69</p>
                            </div>
                            <div class="FilterCheckboxContainer">
                                <div style="display: flex; align-items: center;"><input type="checkbox"><p>Unisex</p></div>
                                <p style="margin-right: 0.5rem;">1,255</p>
                            </div>
                        </div>
                        <div class="FilterCategory">
                            <p class="CategoryLabel">Stock</p>
                            <div class="FilterCheckboxContainer">
                                <div style="display: flex; align-items: center;"><input type="checkbox"><p>In Stock Only</p></div>
                                <p style="margin-right: 0.5rem;">2,269</p>
                            </div>
                            <div class="FilterCheckboxContainer">
                                <div style="display: flex; align-items: center;"><input type="checkbox"><p>Best Seller</p></div>
                                <p style="margin-right: 0.5rem;">69</p>
                            </div>
                            <div class="FilterCheckboxContainer">
                                <div style="display: flex; align-items: center;"><input type="checkbox"><p>Gifsets</p></div>
                                <p style="margin-right: 0.5rem;">126</p>
                            </div>
                        </div>
                        <div class="FilterCategory">
                            <p class="CategoryLabel">Brand</p>
                            <div class="FilterCheckboxContainer">
                                <div style="display: flex; align-items: center;"><input type="checkbox">
                                <p>Versace</p></div>
                            </div>
                            <div class="FilterCheckboxContainer">
                                <div style="display: flex; align-items: center;"><input type="checkbox">
                                <p>Elizabeth Arden</p></div>
                            </div>
                            <div class="FilterCheckboxContainer">
                                <div style="display: flex; align-items: center;"><input type="checkbox">
                                <p>Lattafa</p></div>
                            </div>
                            <div class="FilterCheckboxContainer">
                                <div style="display: flex; align-items: center;"><input type="checkbox">
                                <p>Jimmy Choo</p></div>
                            </div>
                        </div>
                        <div class="FilterCategory">
                            <p class="CategoryLabel">Price</p>
                            <div class="FilterCheckboxContainer">
                                <div style="display: flex; align-items: center;"><input type="checkbox"><p>$100 Below</p></div>
                            </div>
                            <div class="FilterCheckboxContainer">
                                <div style="display: flex; align-items: center;"><input type="checkbox"><p>$101 - $500</p></div>
                            </div>
                            <div class="FilterCheckboxContainer">
                                <div style="display: flex; align-items: center;"><input type="checkbox"><p>$501 - $1000</p></div>
                            </div>
                            <div class="FilterCheckboxContainer">
                                <div style="display: flex; align-items: center;"><input type="checkbox"><p>$1000 Above</p></div>
                            </div>
                        </div>
                    </div> -->
                </div>
                <div id="WebFormMainUIContainerRight">
                    <!-- <div id="SearchResultIndicator">
                        <div style="display: flex; align-items: center;">
                            <p id="ResultCount">690 products</p>
                            <div id="FilterContainer">
                                <div class="FilterLabel">
                                   <p>Best Seller</p> 
                                   <i class="fa-solid fa-xmark"></i>
                                </div>
                                <div class="FilterLabel">
                                    <p>Woman</p> 
                                    <i class="fa-solid fa-xmark"></i>
                                 </div>
                            </div>
                        </div>
                        <button id="ResetFilterButton">Reset</button>
                    </div> -->
                    <div id="HeaderSearchBar">
                        <i id="WebFormHeaderSearchButton" class="fa-solid fa-magnifying-glass"></i>
                        <input id="WebFormHeaderSearchInputButton" type="text" placeholder="Search">
                    </div>
                    <div id="ResultsProductContainer">
                        <?php
                        include_once '../connection/connection.php';

                        $sql = "SELECT `productid`, `productname`, `brand`, `description`, `price`, `quantity`, `productimg`, `filename`, `gender` FROM `tblproduct`";
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
                    </div>
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
    </body>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script defer type="text/javascript" src="JS-Scripts/nav.js"></script>
    <script defer type="text/javascript">
        const WebFormHeaderLogo = document.querySelector('#WebFormHeaderLogo');
        WebFormHeaderLogo.addEventListener('click', function() {
            window.location.href = `MainForm.php`;
        });
    </script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const SearchedProductContainerOverlays = document.querySelectorAll('.SearchedProductContainerOverlay');
        SearchedProductContainerOverlays.forEach(overlay => {
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
<script>
    $(document).ready(function() {
        console.log('Document is ready');

        // Event delegation to handle click events on .SearchedProductContainerOverlay
        $(document).on('click', '.SearchedProductContainerOverlay', function() {
            // Get the productid from the hidden p element
            const productId = $(this).siblings('.SearchedProductContainer').find('.ProductId').text();
            if (productId) {
                window.location.href = `ProductView.php?productid=${productId}`;
            }
        });

        $('#WebFormHeaderSearchInputButton').on('input', function() {
            console.log('Input changed');
            let searchText = $(this).val().trim();
            if (searchText.length > 0) {
                $.ajax({
                    url: '../process/search.php',
                    method: 'POST',
                    data: { searchText: searchText },
                    success: function(response) {
                        $('#ResultsProductContainer').html(response);
                    }
                });
            } else {
                // If the search text is empty, send an AJAX request with an empty string to retrieve all products
                $.ajax({
                    url: '../process/search.php',
                    method: 'POST',
                    data: { searchText: '' },
                    success: function(response) {
                        $('#ResultsProductContainer').html(response);
                    }
                });
            }
        });
    });
</script>
</html>