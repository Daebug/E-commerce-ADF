<?php
session_start();
if (!isset($_SESSION["userid"])) {
    header("Location: LogInSignUpForm.php");
    exit();
}
$username = $_SESSION['username'];
$userid = $_SESSION['userid'];

require_once('../connection/connection.php');

$userid = $_SESSION['userid']; // Assuming the userid is stored in the session
$sql = "SELECT c.cartid, p.productid, p.productname, p.brand, p.price, c.quantity
        FROM tblcart c
        INNER JOIN tblproduct p ON c.productid = p.productid
        WHERE c.userid = $userid AND c.status = 'Pending'";

$result = $conn->query($sql);

if (!$result) {
    echo "Error: " . $conn->error;
    exit();
}

$cartItems = $result->fetch_all(MYSQLI_ASSOC);

if (count($cartItems) == 0) {
}

$disableButton = count($cartItems) == 0;
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
    <title>Checkout</title>
    <link rel="stylesheet" href="CSS-Files/checkout.css">
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
            <div id="MainFormHeaderSearchBar">
                <i id="MainFormHeaderSearchButton" class="fa-solid fa-magnifying-glass"></i>
                <input id="MainFormHeaderSearchInput" type="text" placeholder="Seach">
                <i id="MainFormHeaderDeleteTextButton" class="fa-solid fa-circle-xmark"></i>
            </div>
            <i id="MainFormHeaderMenuButton" class="menu-button fa-solid fa-bars"></i>
            <div class="navbar" id="navbar">                        
                <ul>
                    <li><a href="MainForm.php"><i class="fa-solid fa-house-user"></i> Home </a></li>
                    <li><a href="#"><i class="fa-solid fa-user"></i> Profile </a></li>
                    <li><a href="ViewCart.php"><i class="fa-solid fa-cart-shopping"></i> Cart </a></li>
                    <!-- <li><a href="#"><i class="fa-solid fa-desktop"></i> Admin </a></li> -->
                    <li><a href="../process/logout.php"><i class="fa-solid fa-right-from-bracket"></i> Logout</a></li>
                    <!-- <li><a href="#"><i class="fa-solid fa-cart-shopping"></i> Cart </a></li> -->
                    <!-- <li><a href="#"><i class="fa-solid fa-desktop"></i> View Orders </a></li> -->
                </ul>
            </div>
        </div>
    </div>
    <!-- <div id="MainHeaderButtons">
        <button id="BrandsButton">BRANDS</button>
        <button id="WomansPerfumeButton">WOMAN'S PERFUME</button>
        <button id="MensCologneButton">MEN'S COLOGNE</button>
        <button id="BestSellerButton">BEST SELLER</button>
        <button id="GiftSetsButton">GIFTSETS</button>
    </div> -->  
</header>


    <div class="container2">
        <div class="checkout-contents">
            <div class="contents-container">
                <div class="checkout-form-container">
                    <h1>Checkout</h1>
                    <form id="checkoutForm" method="post" action="../process/placeOrder.php">
                        <label for="name">Name:</label>
                        <input type="text" id="name" name="name" required><br><br>

                        <label for="contact">Contact:</label>
                        <input type="text" id="contact" name="contact" required><br><br>

                        <label for="email">Email:</label>
                        <input type="email" id="email" name="email" required><br><br>

                        <label for="address">Address:</label>
                        <textarea id="address" name="address" required></textarea><br><br>

                        <input type="hidden" id="cartid" name="cartid" value="<?php echo implode(',', array_column($cartItems, 'cartid')); ?>">
                    
                </div>

                <div class="order-summary-container">
                    <h1>Order Summary</h1>
                        <table>
                            <thead>
                                <tr>
                                    <th>Product Image</th>
                                    <th>Product Name</th>
                                    <th>Quantity</th>
                                    <th>Price</th>
                                    <th>Total Price</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                            $total = 0;
                            foreach ($cartItems as $row) {
                                $subtotal = $row['price'] * $row['quantity'];
                                $total += $subtotal;
                                echo "<tr>";
                                echo "<td>";
                                $imageExtensions = ['png', 'jpg', 'jpeg']; // List of allowed image extensions
                                foreach ($imageExtensions as $extension) {
                                    $imageFilename = strtolower(str_replace(' ', '_', $row['productname'])) . ".$extension";
                                    $imagePath = "../image/product-image/{$imageFilename}";
                                    if (file_exists($imagePath)) {
                                        // Display the image with the correct extension and limit size to 150px
                                        echo "<img src='$imagePath' alt='{$row['productname']}' style='max-width: 150px;'/>";
                                        break; // Exit the loop once the image is found
                                    }
                                }
                                echo "</td>";
                                echo "<td>{$row['productname']}</td>";
                                echo "<td>{$row['quantity']}</td>";
                                echo "<td>₱" . number_format($row['price'], 2) . "</td>";
                                echo "<td>₱" . number_format($subtotal, 2) . "</td>";
                                // Add a hidden input field for cartid
                                echo "<td style='display:none;'><input type='hidden' name='cartids[]' value='{$row['cartid']}'></td>";
                                echo "</tr>";
                            }            
                            ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="4">Total</th>
                                    <td>₱<?php echo number_format($total, 2); ?></td>
                                </tr>
                            </tfoot>
                        </table>

                        
                </div>
                
            </div>

            <div class="checkout-button-container">
                <button type="submit" id="placeOrderBtn" class="btn place-order-btn" <?php if ($result->num_rows == 0) echo 'style="display: none;"'; ?>>Place Order</button>
                    <a href="ViewCart.php" class="btn return-to-cart-btn"><i class="fa-solid fa-cart-shopping"></i> Return to Cart</a>
            </div>
        
        </div>
       
    </div>
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
<script defer type="text/javascript" src="JS-Scripts/nav.js"></script>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    // Update hidden input values before form submission
    document.getElementById('checkoutForm').addEventListener('submit', function() {
        document.getElementById('name_hidden').value = document.getElementById('name').value;
        document.getElementById('contact_hidden').value = document.getElementById('contact').value;
        document.getElementById('email_hidden').value = document.getElementById('email').value;
        document.getElementById('address_hidden').value = document.getElementById('address').value;
    });
</script>
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
