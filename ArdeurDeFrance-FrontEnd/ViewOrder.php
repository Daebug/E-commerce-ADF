<?php

include_once '../process/config.php';
// session_start();
// if (!isset($_SESSION["userid"])) {
//     header("Location: LogInSignUpForm.php");
//     exit();
// }
// $username = $_SESSION['username'];
// $userid = $_SESSION['userid'];

require_once('../connection/connection.php');

// SQL query to retrieve pending orders
$sql_pending = "SELECT p.`paymentid`, p.`cartid`, p.`name`, p.`address`, p.`contact_number`, p.`email`, p.`status`, c.`productid`, c.`quantity`, pr.`price`, pr.`productname`
                FROM `tblpayment` p
                JOIN `tblcart` c ON p.`cartid` = c.`cartid`
                JOIN `tblproduct` pr ON c.`productid` = pr.`productid`
                WHERE c.`userid` = $userid AND p.`status` = 'Pending'";
$result_pending = $conn->query($sql_pending);


// Initialize arrays to store order items and total amount
$orderItems = [];
$totalAmount = 0;

// Loop through the result set and populate the order items array
while ($row = $result_pending->fetch_assoc()) {
    // Set session variables with the current row's values
    $_SESSION['paymentid'] = $row['paymentid'];
    $_SESSION['cartid'] = $row['cartid'];
    $_SESSION['name'] = $row['name'];
    $_SESSION['address'] = $row['address'];
    $_SESSION['contact_number'] = $row['contact_number'];
    $_SESSION['email'] = $row['email'];
    $_SESSION['status'] = $row['status'];

    // Calculate amount for each product
    $amount = $row['quantity'] * $row['price'];
    $totalAmount += $amount; // Accumulate total amount

    // Populate orderItems array for PayPal
    $orderItems[] = [
        'name' => 'Product ' . $row['productid'],
        'pname' => $row['productname'],
        'unit_amount' => [
            'currency_code' => 'USD',
            'value' => $row['price'],
        ],
        'quantity' => $row['quantity'],
    ];
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
    <title>User Orders</title>
    <link rel="stylesheet" href="CSS-Files/ViewOrders.css">
    <link rel="icon" type="image/png" href="">
    <link rel="stylesheet" type="text/css" href="CSS-Files/GeneralSheet.css">
    <link rel="stylesheet" type="text/css" href="CSS-Files/MainFormSheet.css">
    <link rel="stylesheet" type="text/css" href="CSS-Files/nav.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
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
        <div id="MainHeaderButtons">
            <button id="BrandsButton">BRANDS</button>
            <button id="WomansPerfumeButton">WOMAN'S PERFUME</button>
            <button id="MensCologneButton">MEN'S COLOGNE</button>
            <button id="BestSellerButton">BEST SELLER</button>
            <button id="GiftSetsButton">GIFTSETS</button>
        </div>
    </header>
    <div class="vieworder-content-container">
        <div class="pending-table-container">
            <h2>Pending Orders</h2>
            <div class="vieworder-table-container">
                <table>
                    <thead>
                        <tr>
                            <!-- Existing table headers -->
                            <th>Name</th>
                            <th>Address</th>
                            <th>Contact Number</th>
                            <th>Email</th>
                            <th>Product Name</th>
                            <th>Product Image</th>
                            <th>Quantity</th>
                            <th>Price</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $totalAmount_pending = 0; // Initialize total amount variable for pending orders
                        foreach ($orderItems as $item) {
                            // Calculate amount for each product
                            $amount = $item['quantity'] * $item['unit_amount']['value'];
                            $totalAmount_pending += $amount; // Accumulate total amount

                            // Output details for the current product
                            echo "<tr>";
                            echo "<td style='display:none;'><input type='hidden' value='{$_SESSION['paymentid']}' name='paymentid[]' /></td>";
                            echo "<td style='display:none;'><input type='hidden' value='{$_SESSION['cartid']}' name='cartid[]' /></td>";
                            echo "<td style='text-align: center;'>{$_SESSION['name']}</td>";
                            echo "<td style='text-align: center;'>{$_SESSION['address']}</td>";
                            echo "<td style='text-align: center;'>{$_SESSION['contact_number']}</td>";
                            echo "<td style='text-align: center;'>{$_SESSION['email']}</td>";
                            echo "<td style='text-align: center;'>{$item['pname']}</td>";

                            // Loop through each extension and check if the image file exists
                            $imageExtensions = ['png', 'jpg', 'jpeg']; // List of allowed image extensions
                            foreach ($imageExtensions as $extension) {
                                $imageFilename = strtolower(str_replace(' ', '_', $item['pname'])) . ".$extension";
                                $imagePath = "../image/product-image/{$imageFilename}";
                                if (file_exists($imagePath)) {
                                    // Display the image with the correct extension and limit size to 150px
                                    echo "<td style='text-align: center;'><img src='$imagePath' alt='{$item['pname']}' style='max-width: 100px;'/></td>";
                                    break; // Exit the loop once the image is found
                                }
                            }

                            echo "<td style='text-align: center;'>{$item['quantity']}</td>";
                            echo "<td style='text-align: center;'>₱{$item['unit_amount']['value']}</td>";
                            echo "<td style='text-align: center;'>₱{$amount}</td>";
                            echo "<td id='status-{$_SESSION['paymentid']}'>{$_SESSION['status']}</td>"; // Display status for each payment

                            // Add a Cancel button if the status is 'Pending'
                            echo "<td><button onclick=\"cancelPayment('{$_SESSION['paymentid']}')\">Cancel</button></td>";

                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="8">Total Amount (Pending):</td>
                            <td>₱<?php echo $totalAmount_pending; ?></td>
                            <td></td> <!-- Add an empty cell for the action footer -->
                        </tr>
                    </tfoot>
                </table>
            </div>

        </div>
        <div class="proceed-to-paypal-container">
            <button class="paypal-button" id="confirmPaymentBtn" onclick="proceedToPayPal()">Proceed to PayPal</button>
        </div>
        <!-- <div class="processed-table-container">
            <h2>Processed Orders</h2>
            <div class="vieworder-table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Address</th>
                            <th>Contact Number</th>
                            <th>Email</th>
                            <th>Product Image</th>
                            <th>Product Name</th>
                            <th>Quantity</th>
                            <th>Price</th>
                            <th>Amount</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql_processed = "SELECT p.`paymentid`, p.`cartid`, p.`name`, p.`address`, p.`contact_number`, p.`email`, p.`status`, c.`productid`, c.`quantity`, pr.`price`, pr.`productname`
                                            FROM `tblpayment` p
                                            JOIN `tblcart` c ON p.`cartid` = c.`cartid`
                                            JOIN `tblproduct` pr ON c.`productid` = pr.`productid`
                                            WHERE c.`userid` = $userid AND p.`status` = 'Paid'";
                        $result_processed = $conn->query($sql_processed);

                        $totalAmount_processed = 0;
                        while ($row = $result_processed->fetch_assoc()) {

                            $amount = $row['quantity'] * $row['price'];
                            $totalAmount_processed += $amount;


                            echo "<tr>";
                            echo "<td style='text-align: center;'>{$row['name']}</td>";
                            echo "<td style='text-align: center;'>{$row['address']}</td>";
                            echo "<td style='text-align: center;'>{$row['contact_number']}</td>";
                            echo "<td style='text-align: center;'>{$row['email']}</td>";
                            $imageExtensions = ['png', 'jpg', 'jpeg'];
                            foreach ($imageExtensions as $extension) {
                                $imageFilename = strtolower(str_replace(' ', '_', $row['productname'])) . ".$extension";
                                $imagePath = "../image/product-image/{$imageFilename}";
                                if (file_exists($imagePath)) {

                                    echo "<td style='text-align: center;'><img src='$imagePath' alt='{$row['productname']}' style='max-width: 100px;'/></td>";
                                    break;
                                }
                            }
                            echo "<td style='text-align: center;'>{$row['productname']}</td>";
                            echo "<td style='text-align: center;'>{$row['quantity']}</td>";
                            echo "<td style='text-align: center;'>₱{$row['price']}</td>";
                            echo "<td style='text-align: center;'>₱{$amount}</td>";
                            echo "<td id='status-{$row['paymentid']}'>{$row['status']}</td>";

                            echo "</tr>";
                        }

                        ?>
                        <div class="payment-details" style="display: none;">
                            <h2>Payment Details</h2>
                            <p><strong>Name:</strong> <?php echo $_SESSION['name']; ?></p>
                            <p><strong>Address:</strong> <?php echo $_SESSION['address']; ?></p>

                            <p><strong>Product:</strong> <?php echo $item['name']; ?></p>
                            <p><strong>Quantity:</strong> <?php echo $item['quantity']; ?></p>
                            <p><strong>Price:</strong> <?php echo $item['unit_amount']['value']; ?></p>
                            <p><strong>Total Amount:</strong> <?php echo $amount; ?></p>
                        </div>
                        


                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="8">Total Amount (Processed):</td>
                            <td>₱<?php echo $totalAmount_processed; ?></td>
                        </tr>

                    </tfoot>

                </table> -->

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
    <script defer type="text/javascript" src="JS-Scripts/nav.js"></script>
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

        function proceedToPayPal() {

            var name = "<?php echo $_SESSION['name']; ?>";
            var address = "<?php echo $_SESSION['address']; ?>";
            var productDetails = "";
            <?php foreach ($orderItems as $item): ?>
                productDetails += "<?php echo $item['name'] . ', Quantity: ' . $item['quantity'] . ', Price: ' . $item['unit_amount']['value'] . ' USD'; ?>\n";
            <?php endforeach; ?>
            var amount = "<?php echo $totalAmount; ?>";


            var paymentDetails = encodeURIComponent("Name: " + name + ", Address: " + address + "\nProducts: " + productDetails + "Total Amount: " + amount);

            window.location.href = "confirm_paypal.php?paymentDetails=" + paymentDetails;
        }
    </script>
    <script>
        function cancelPayment(paymentID) {
            // Make an AJAX request to cancel the payment
            fetch('../process/cancelOrder.php', {
                method: 'post',
                headers: {
                    'content-type': 'application/json'
                },
                body: JSON.stringify({
                    paymentID: paymentID
                })
            }).then(function(response) {
                if (response.ok) {
                    // Reload the page to update the order status
                    location.reload();
                } else {
                    console.error('Failed to cancel payment');
                }
            }).catch(function(error) {
                console.error('Error:', error);
            });
        }
    </script>
</body>

</html>