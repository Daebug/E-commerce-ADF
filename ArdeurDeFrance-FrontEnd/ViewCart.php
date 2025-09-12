<?php
session_start();
if (!isset($_SESSION["userid"])) {
    header("Location: LogInSignUpForm.php");
    exit();
}
$username = $_SESSION['username'];
$userid = $_SESSION['userid'];
require_once('../connection/connection.php');

$sql = "SELECT p.productid, p.productname, p.brand, p.price, c.quantity
        FROM tblcart c
        INNER JOIN tblproduct p ON c.productid = p.productid
        WHERE c.userid = $userid AND c.status = 'Pending'";
$result = $conn->query($sql);

$total = 0;
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
            <title>ViewCart</title>
            <link rel="icon" type="image/png" href="">
            <link rel="stylesheet" type="text/css" href="CSS-Files/GeneralSheet.css">
            <link rel="stylesheet" type="text/css" href="CSS-Files/MainFormSheet.css">
            <link rel="stylesheet" type="text/css" href="CSS-Files/nav.css">
            <link rel="stylesheet" href="CSS-Files/ViewCart.css">
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
                                <li><a href="ViewCart.php"><i class="fa-solid fa-cart-shopping"></i> Cart </a></li>
                                <li><a href="ViewOrder.php"><i class="fa-solid fa-box"></i> View Orders </a></li>
                                <li><a href="Chat.php"><i class="fa-solid fa-message"></i> Chat </a></li>
                                <li><a href="../process/logout.php"><i class="fa-solid fa-right-from-bracket"></i> Logout</a></li>
                            </ul>
                        </div>
                    </div>
                </div>

            </header>
            <div class="container1">
                <div class="table-cart-container">
                    <h1>Shopping Cart</h1>
                    <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>Product Image</th>
                                <th>Product Name</th>
                                <th>Brand</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th>Total</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                        $rowsExist = false;
                        while ($row = $result->fetch_assoc()) {
                            $rowsExist = true;
                            $subtotal = $row['price'] * $row['quantity'];
                            $total += $subtotal;
                            echo "<tr>";
                            echo "<td>";
                            // Loop through each extension and check if the image file exists
                            $imageExtensions = ['png', 'jpg', 'jpeg']; // List of allowed image extensions
                            foreach ($imageExtensions as $extension) {
                                $imageFilename = strtolower(str_replace(' ', '_', $row['productname'])) . ".$extension";
                                $imagePath = "../image/product-image/{$imageFilename}";
                                if (file_exists($imagePath)) {
                                    // Display the image with the correct extension and limit size to 120px
                                    echo "<img src='$imagePath' alt='{$row['productname']}' style='max-width: 150px;'/>";
                                    break; // Exit the loop once the image is found
                                }
                            }
                            echo "</td>";
                            echo "<td>{$row['productname']}</td>";
                            echo "<td>{$row['brand']}</td>";
                            echo "<td>₱" . str_replace(' ', '', $row['price']) . "</td>";
                            echo "<td><input type='number' value='{$row['quantity']}'></td>";
                            echo "<td><span style='display: inline-block; max-width: 80px; overflow: hidden; text-overflow: ellipsis;'>₱" . str_replace(' ', '', $subtotal) . "</span></td>";
                            echo "<td><button class='remove-btn' onclick='removeCartItem({$row['productid']})'>Remove</button></td>";
                            echo "</tr>";
                        }
                        ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="5">Total</th>
                                <td>₱<?php echo $total; ?></td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>

                    </div>
                    <?php if ($rowsExist): ?>
                    <button class="btn checkout-btn" onclick="goToCheckout()">Checkout</button>
                    <?php endif; ?>

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
        <script>
            function removeCartItem(productid) {
                if (confirm("Are you sure you want to remove this item from your cart?")) {
                    var xhr = new XMLHttpRequest();
                    xhr.open('POST', '../process/removeCartItem.php', true);
                    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
                    xhr.onreadystatechange = function() {
                        if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
                            location.reload();
                        }
                    };
                    xhr.send('productid=' + productid);
                }
            }
        </script>
        <script>
            function goToHome() {
                window.location.href = 'MainForm.php';
            }
        </script>
        <script>
            function goToCheckout() {
                window.location.href = 'Checkout.php';
            }
        </script>
    </html>