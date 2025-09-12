<!-- //add button process tsaka additional notes para sa completed order
//add notification red light tsaka number para sa profile
//fix redirections
//add profile button sa menu bar
///retrieve mga value sa admin yung sa total user etc. -->
<?php
// Initialize session and check for user authentication
session_start();
if (!isset($_SESSION["userid"])) {
    header("Location: LogInSignUpForm.php");
    exit();
}

$username = $_SESSION['username'];
$userid = $_SESSION['userid'];

require_once('../../connection/connection.php');

try {
    // Retrieve user details
    $sql = "SELECT `username`, `email`, `contact_number` FROM `tbluser` WHERE `userid` = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $userid);
    $stmt->execute();
    $stmt->bind_result($uusername, $uemail, $ucontact_number);
    $stmt->fetch();
    $stmt->close();

    // Fetch "Orders" that have no specific order status (pending)
    $sqlOrders = "
    SELECT DISTINCT p.orderNum, p.status, p.name, p.address, p.contact_number, p.email, p.remark 
    FROM tblpayment p
    JOIN tblcart c ON p.cartid = c.cartid
    WHERE c.userid = ? AND p.orderStatus IS NULL";

    $stmtOrders = $conn->prepare($sqlOrders);
    $stmtOrders->bind_param("i", $userid);
    $stmtOrders->execute();
    $stmtOrders->bind_result($orderNum, $status, $name, $address, $contact_number, $email, $remark);

    $orders = [];
    while ($stmtOrders->fetch()) {
        $orders[] = [
            'orderNum' => htmlspecialchars($orderNum),
            'status' => htmlspecialchars($status),
            'name' => htmlspecialchars($name),
            'address' => htmlspecialchars($address),
            'contact_number' => htmlspecialchars($contact_number),
            'email' => htmlspecialchars($email),
            'remark' => htmlspecialchars($remark),
        ];
    }
    $stmtOrders->close();

    // Fetch order details for each "Orders"
    $orderDetails = [];
    foreach ($orders as $order) {
        $sqlOrderDetails = "
        SELECT pr.productimg, pr.brand, pr.productname, c.quantity, pr.price 
        FROM tblpayment p 
        JOIN tblcart c ON p.cartid = c.cartid 
        JOIN tblproduct pr ON c.productid = pr.productid 
        WHERE c.userid = ? AND p.orderNum = ?";

        $stmtOrderDetails = $conn->prepare($sqlOrderDetails);
        $stmtOrderDetails->bind_param("is", $userid, $order['orderNum']);
        $stmtOrderDetails->execute();
        $stmtOrderDetails->store_result();
        $stmtOrderDetails->bind_result($productImg, $brand, $productName, $quantity, $price);

        $items = [];
        while ($stmtOrderDetails->fetch()) {
            $items[] = [
                'productImg' => htmlspecialchars($productImg),
                'brand' => htmlspecialchars($brand),
                'productName' => htmlspecialchars($productName),
                'quantity' => htmlspecialchars($quantity),
                'price' => htmlspecialchars($price),
            ];
        }
        $orderDetails[$order['orderNum']] = $items;
        $stmtOrderDetails->close();
    }

    // Fetch "To Receive" orders
    $sqlToReceive = "
    SELECT DISTINCT p.orderNum, p.status, p.name, p.address, p.contact_number, p.email, p.remark 
    FROM tblpayment p
    JOIN tblcart c ON p.cartid = c.cartid
    WHERE c.userid = ? AND p.orderStatus = 'To Receive'";

    $stmtToReceive = $conn->prepare($sqlToReceive);
    $stmtToReceive->bind_param("i", $userid);
    $stmtToReceive->execute();
    $stmtToReceive->bind_result($orderNum, $status, $name, $address, $contact_number, $email, $remark);
    $toReceiveOrders = [];
    while ($stmtToReceive->fetch()) {
        $toReceiveOrders[] = [
            'orderNum' => htmlspecialchars($orderNum),
            'status' => htmlspecialchars($status),
            'name' => htmlspecialchars($name),
            'address' => htmlspecialchars($address),
            'contact_number' => htmlspecialchars($contact_number),
            'email' => htmlspecialchars($email),
            'remark' => htmlspecialchars($remark),
        ];
    }
    $stmtToReceive->close();

    // Fetch order details for "To Receive" orders
    $toReceiveOrderDetails = [];
    foreach ($toReceiveOrders as $order) {
        $sqlOrderDetails = "
        SELECT pr.productimg, pr.brand, pr.productname, c.quantity, pr.price 
        FROM tblpayment p 
        JOIN tblcart c ON p.cartid = c.cartid 
        JOIN tblproduct pr ON c.productid = pr.productid 
        WHERE c.userid = ? AND p.orderNum = ?";

        $stmtOrderDetails = $conn->prepare($sqlOrderDetails);
        $stmtOrderDetails->bind_param("is", $userid, $order['orderNum']);
        $stmtOrderDetails->execute();
        $stmtOrderDetails->store_result();
        $stmtOrderDetails->bind_result($productImg, $brand, $productName, $quantity, $price);

        $items = [];
        while ($stmtOrderDetails->fetch()) {
            $items[] = [
                'productImg' => htmlspecialchars($productImg),
                'brand' => htmlspecialchars($brand),
                'productName' => htmlspecialchars($productName),
                'quantity' => htmlspecialchars($quantity),
                'price' => htmlspecialchars($price),
            ];
        }
        $toReceiveOrderDetails[$order['orderNum']] = $items;
        $stmtOrderDetails->close();
    }

    // Fetch "Completed" orders
    $sqlCompleted = "
    SELECT DISTINCT p.orderNum, p.orderStatus, p.status, p.name, p.address, p.contact_number, p.email, p.remark 
    FROM tblpayment p
    JOIN tblcart c ON p.cartid = c.cartid
    WHERE c.userid = ? AND p.orderStatus = 'Completed'";

    $stmtCompleted = $conn->prepare($sqlCompleted);
    $stmtCompleted->bind_param("i", $userid);
    $stmtCompleted->execute();
    $stmtCompleted->bind_result($orderNum, $orderStatus, $status, $name, $address, $contact_number, $email, $remark);

    $completedOrders = [];
    while ($stmtCompleted->fetch()) {
        $completedOrders[] = [
            'orderNum' => htmlspecialchars($orderNum),
            'orderStatus' => htmlspecialchars($orderStatus),
            'status' => htmlspecialchars($status),
            'name' => htmlspecialchars($name),
            'address' => htmlspecialchars($address),
            'contact_number' => htmlspecialchars($contact_number),
            'email' => htmlspecialchars($email),
            'remark' => htmlspecialchars($remark),
        ];
    }
    $stmtCompleted->close();

    // Fetch order details for "Completed" orders
    $completedOrderDetails = [];
    foreach ($completedOrders as $order) {
        $sqlOrderDetails = "
        SELECT pr.productimg, pr.brand, pr.productname, c.quantity, pr.price 
        FROM tblpayment p 
        JOIN tblcart c ON p.cartid = c.cartid 
        JOIN tblproduct pr ON c.productid = pr.productid 
        WHERE c.userid = ? AND p.orderNum = ?";

        $stmtOrderDetails = $conn->prepare($sqlOrderDetails);
        $stmtOrderDetails->bind_param("is", $userid, $order['orderNum']);
        $stmtOrderDetails->execute();
        $stmtOrderDetails->store_result();
        $stmtOrderDetails->bind_result($productImg, $brand, $productName, $quantity, $price);

        $items = [];
        while ($stmtOrderDetails->fetch()) {
            $items[] = [
                'productImg' => htmlspecialchars($productImg),
                'brand' => htmlspecialchars($brand),
                'productName' => htmlspecialchars($productName),
                'quantity' => htmlspecialchars($quantity),
                'price' => htmlspecialchars($price),
            ];
        }
        $completedOrderDetails[$order['orderNum']] = $items;
        $stmtOrderDetails->close();
    }

    // Count the number of orders for each tab
    $orderedCount = count($orders);
    $toReceiveCount = count($toReceiveOrders);
    $completedCount = count($completedOrders);
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
    exit();
}

$conn->close();
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
    <title>Profile</title>

    <link rel="icon" type="image/png" href="">
    <link rel="stylesheet" type="text/css" href="../CSS-Files/GeneralSheet.css">
    <link rel="stylesheet" type="text/css" href="../CSS-Files/MainFormSheet.css">
    <link rel="stylesheet" type="text/css" href="../CSS-Files/nav.css">
    <link rel="stylesheet" type="text/css" href="../CSS-Files/profile.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <style>
        .notification-dot {
            background-color: red;
            color: red;
            border-radius: 50%;
            padding: 2px 6px;
            font-size: 20px;
            position: relative;
            top: -10px;
            left: 5px;
        }
    </style>
</head>

<body>
    <header id="MainFormHeader">
        <div id="MainFormHeaderTopPart">
            <div id='gert' style="display: flex; align-items: center;">
                <img id="MainFormHeaderLogo" src="../Source-Files/Logo3.png" alt="">
                <p>ARDEUR DE FRANCE</p>
            </div>
            <div style="display: flex; align-items: center;">
                <div id="MainFormHeaderSearchBar">
                    <i id="MainFormHeaderSearchButton" class="fa-solid fa-magnifying-glass"></i>
                    <input id="MainFormHeaderSearchInput" type="text" placeholder="Search">
                    <i id="MainFormHeaderDeleteTextButton" class="fa-solid fa-circle-xmark"></i>
                </div>
                <i id="MainFormHeaderMenuButton" class="menu-button fa-solid fa-bars"></i>
                <div class="navbar" id="navbar">
                    <ul>
                        <li><a href="../../ArdeurDeFrance-FrontEnd/MainForm.php"><i class="fa-solid fa-house-user"></i> Home </a></li>
                        <li><a href="../../ViewCart.php"><i class="fa-solid fa-cart-shopping"></i> Cart </a></li>
                        <li><a href="../../ViewOrder.php"><i class="fa-solid fa-box"></i> View Orders </a></li>
                        <li><a href="../../Chat.php"><i class="fa-solid fa-message"></i> Chat </a></li>
                        <li><a href="../../process/logout.php"><i class="fa-solid fa-right-from-bracket"></i> Logout</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </header>
    <main id="MainFormMain">
        <div class="main-container">
            <div class="content-container top">
                <div class="name">
                    <h1 id="name">Welcome, <?php echo htmlspecialchars($uusername); ?>!</h1>
                </div>

                <div id="myModal" class="modal">
                    <div class="modal-content">
                        <span class="close">&times;</span>
                        <h2>Edit Information</h2>
                        <input type="text" id="editName" placeholder="Name" value="<?php echo htmlspecialchars($uusername); ?>">
                        <input type="text" id="editPhone" placeholder="Phone" value="<?php echo htmlspecialchars($ucontact_number); ?>">
                        <input type="text" id="editAddress" placeholder="Address">
                        <button id="saveChanges">Save Changes</button>
                    </div>
                </div>

                <div class="bio">
                    <div class="email">Email: <?php echo htmlspecialchars($uemail); ?></div>
                    <div class="phone">Phone: <?php echo htmlspecialchars($ucontact_number); ?></div>
                </div>

                <div class="tabs">
                    <input type="radio" id="tab-ordered" name="tab" class="tab-radio" checked>
                    <label for="tab-ordered" class="tab ordered" data-target="content-ordered">
                        <i class="fa-solid fa-box"> Orders</i>
                        <?php if ($orderedCount > 0): ?>
                            <span class="notification-dot"><?php echo $orderedCount; ?></span>
                        <?php endif; ?>
                    </label>

                    <input type="radio" id="tab-to-receive" name="tab" class="tab-radio">
                    <label for="tab-to-receive" class="tab to-receive" data-target="content-to-receive">
                        <i class="fa-solid fa-truck-field"> To Receive</i>
                        <?php if ($toReceiveCount > 0): ?>
                            <span class="notification-dot"><?php echo $toReceiveCount; ?></span>
                        <?php endif; ?>
                    </label>

                    <input type="radio" id="tab-completed" name="tab" class="tab-radio">
                    <label for="tab-completed" class="tab completed" data-target="content-completed">
                        <i class="fa-solid fa-square-check"> Completed</i>
                        <?php if ($completedCount > 0): ?>
                            <span class="notification-dot"><?php echo $completedCount; ?></span>
                        <?php endif; ?>
                    </label>
                </div>
            </div>
            <div class="content-container bottom">
                <div class="tab-content" id="content-ordered" style="display: block;">
                    <?php if (empty($orders)): ?>
                        <p>No pending orders found for this user.</p>
                    <?php else: ?>
                        <?php foreach ($orders as $order): ?>
                            <div class="order-container">
                                <div class="order-summary" data-order-id="order1">
                                    <label class="order-id">Order ID: <?php echo $order['orderNum']; ?></label>
                                    <label class="order-status">Status: <?php echo $order['status']; ?></label>
                                </div>
                                <p>Name: <?php echo $order['name']; ?></p>
                                <p>Address: <?php echo $order['address']; ?></p>
                                <p>Contact No: <?php echo $order['contact_number']; ?></p>
                                <p>Email: <?php echo $order['email']; ?></p>
                                <p style="color: black;">Note:
                                    <span style="color: #26aa99;">
                                        <?php echo isset($order['remark']) ? $order['remark'] : 'No remarks available.'; ?>
                                    </span>
                                </p>

                                <table class="orders-table">
                                    <thead>
                                        <tr>
                                            <th>Item Image</th>
                                            <th>Brand</th>
                                            <th>Item Name</th>
                                            <th>Quantity</th>
                                            <th>Price</th>
                                        </tr>
                                    </thead>
                                    <tbody class="order-details" data-order-id="<?php echo $order['orderNum']; ?>">
                                        <?php if (isset($orderDetails[$order['orderNum']])): ?>
                                            <?php
                                            $totalPrice = 0;
                                            foreach ($orderDetails[$order['orderNum']] as $item):
                                                $itemTotalPrice = $item['price'] * $item['quantity'];
                                                $totalPrice += $itemTotalPrice;
                                            ?>
                                                <tr>
                                                    <?php
                                                    $imagePathJPG = "../../image/product-image/" . strtolower(str_replace(' ', '_', $item['productName'])) . ".jpg";
                                                    $imagePathPNG = "../../image/product-image/" . strtolower(str_replace(' ', '_', $item['productName'])) . ".png";
                                                    if (file_exists($imagePathJPG)) {
                                                        echo "<td><img src='$imagePathJPG' alt='" . htmlspecialchars($item['productName']) . " Image' style='max-width: 100px; max-height: 100px;'></td>";
                                                    } elseif (file_exists($imagePathPNG)) {
                                                        echo "<td><img src='$imagePathPNG' alt='" . htmlspecialchars($item['productName']) . " Image' style='max-width: 100px; max-height: 100px;'></td>";
                                                    } else {
                                                        echo "<td>No Image</td>";
                                                    }
                                                    ?>
                                                    <td><?php echo htmlspecialchars(html_entity_decode($item['brand'])); ?></td>
                                                    <td><?php echo htmlspecialchars(html_entity_decode($item['productName'])); ?></td>
                                                    <td><?php echo htmlspecialchars($item['quantity']); ?></td>
                                                    <td>₱<?php echo number_format($itemTotalPrice, 2); ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                            <tr>
                                                <td colspan="4" style="text-align: right;"><strong>Total:</strong></td>
                                                <td>₱<?php echo number_format($totalPrice, 2); ?></td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
                <!-- for to receive tab -->
                <div class="tab-content" id="content-to-receive" style="display: none;">
                    <?php if (empty($toReceiveOrders)): ?>
                        <p>No orders to receive found for this user.</p>
                    <?php else: ?>
                        <?php foreach ($toReceiveOrders as $order): ?>
                            <div class="order-info">
                                <label>Order Information:</label>
                                <div class="sample-order">
                                    <p>Order ID: <?php echo $order['orderNum']; ?></p>
                                    <p>Name: <?php echo $order['name']; ?></p>
                                    <p>Contact No: <?php echo $order['contact_number']; ?></p>
                                    <p>Email: <?php echo $order['email']; ?></p>
                                    <p style="color: black;">Note:
                                        <span style="color: #26aa99;">
                                            <?php echo isset($order['remark']) ? $order['remark'] : 'No remarks available.'; ?>
                                        </span>
                                    </p>
                                </div>
                            </div>
                            <table class="to-receive-table">
                                <thead>
                                    <tr>
                                        <th>Item Image</th>
                                        <th>Brand</th>
                                        <th>Item Name</th>
                                        <th>Quantity</th>
                                        <th>Price</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (isset($toReceiveOrderDetails[$order['orderNum']])): ?>
                                        <?php
                                        $totalPrice = 0;
                                        foreach ($toReceiveOrderDetails[$order['orderNum']] as $item):
                                            $itemTotalPrice = $item['price'] * $item['quantity'];
                                            $totalPrice += $itemTotalPrice;
                                        ?>
                                            <tr>
                                                <?php
                                                $imagePathJPG = "../../image/product-image/" . strtolower(str_replace(' ', '_', $item['productName'])) . ".jpg";
                                                $imagePathPNG = "../../image/product-image/" . strtolower(str_replace(' ', '_', $item['productName'])) . ".png";

                                                if (file_exists($imagePathJPG)) {
                                                    echo "<td><img src='$imagePathJPG' alt='" . htmlspecialchars($item['productName']) . " Image' style='max-width: 100px; max-height: 100px;'></td>";
                                                } elseif (file_exists($imagePathPNG)) {
                                                    echo "<td><img src='$imagePathPNG' alt='" . htmlspecialchars($item['productName']) . " Image' style='max-width: 100px; max-height: 100px;'></td>";
                                                } else {
                                                    echo "<td>No Image</td>";
                                                }
                                                ?>
                                                <td><?php echo htmlspecialchars($item['brand']); ?></td>
                                                <td><?php echo htmlspecialchars($item['productName']); ?></td>
                                                <td><?php echo htmlspecialchars($item['quantity']); ?></td>
                                                <td>₱<?php echo number_format($itemTotalPrice, 2); ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                        <tr>
                                            <td colspan="4" style="text-align: right;"><strong>Total:</strong></td>
                                            <td>₱<?php echo number_format($totalPrice, 2); ?></td>
                                        </tr>
                                        <?php if ($order['remark'] === 'The system has detected that your order has been delivered.'): ?>
                                            <tr>
                                                <td colspan="5" style="text-align: right;">
                                                    <form action="../../process/orderreceived.php" method="post">
                                                        <input type="hidden" name="orderNum" value="<?php echo $order['orderNum']; ?>">
                                                        <input style="padding: 10px; border-radius:5px; background-color:greenyellow;" type="submit" value="Order Received">
                                                    </form>
                                                </td>
                                            </tr>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
                <!-- for completed orders tab -->
                <div class="tab-content" id="content-completed" style="display: none;">
                    <?php if (empty($completedOrders)): ?>
                        <p>No completed orders found for this user.</p>
                    <?php else: ?>
                        <?php foreach ($completedOrders as $order): ?>
                            <div class="order-container">
                                <div class="order-summary" data-order-id="<?php echo $order['orderNum']; ?>">
                                    <label class="order-id">Order ID: <?php echo $order['orderNum']; ?></label>
                                    <label class="order-status">Status: <?php echo htmlspecialchars($order['orderStatus']); ?></label>
                                </div>
                                <p>Name: <?php echo $order['name']; ?></p>
                                <p>Address: <?php echo $order['address']; ?></p>
                                <p>Contact No: <?php echo $order['contact_number']; ?></p>
                                <p>Email: <?php echo $order['email']; ?></p>
                                <p style="color: black;">Note:
                                    <span style="color: #26aa99;">
                                        <?php echo isset($order['remark']) ? $order['remark'] : 'No remarks available.'; ?>
                                    </span>
                                </p>

                                <table class="orders-table">
                                    <thead>
                                        <tr>
                                            <th>Item Image</th>
                                            <th>Brand</th>
                                            <th>Item Name</th>
                                            <th>Quantity</th>
                                            <th>Price</th>
                                        </tr>
                                    </thead>
                                    <tbody class="order-details" data-order-id="<?php echo $order['orderNum']; ?>">
                                        <?php if (isset($completedOrderDetails[$order['orderNum']])): ?>
                                            <?php
                                            $totalPrice = 0;
                                            foreach ($completedOrderDetails[$order['orderNum']] as $item):
                                                $itemTotalPrice = $item['price'] * $item['quantity'];
                                                $totalPrice += $itemTotalPrice;
                                            ?>
                                                <tr>
                                                    <?php
                                                    $imagePathJPG = "../../image/product-image/" . strtolower(str_replace(' ', '_', $item['productName'])) . ".jpg";
                                                    $imagePathPNG = "../../image/product-image/" . strtolower(str_replace(' ', '_', $item['productName'])) . ".png";
                                                    if (file_exists($imagePathJPG)) {
                                                        echo "<td><img src='$imagePathJPG' alt='" . htmlspecialchars($item['productName']) . " Image' style='max-width: 100px; max-height: 100px;'></td>";
                                                    } elseif (file_exists($imagePathPNG)) {
                                                        echo "<td><img src='$imagePathPNG' alt='" . htmlspecialchars($item['productName']) . " Image' style='max-width: 100px; max-height: 100px;'></td>";
                                                    } else {
                                                        echo "<td>No Image</td>";
                                                    }
                                                    ?>
                                                    <td><?php echo htmlspecialchars(html_entity_decode($item['brand'])); ?></td>
                                                    <td><?php echo htmlspecialchars(html_entity_decode($item['productName'])); ?></td>
                                                    <td><?php echo htmlspecialchars($item['quantity']); ?></td>
                                                    <td>₱<?php echo number_format($itemTotalPrice, 2); ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                            <tr>
                                                <td colspan="4" style="text-align: right;"><strong>Total:</strong></td>
                                                <td>₱<?php echo number_format($totalPrice, 2); ?></td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        </div>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="../JS-Scripts/profile.js"></script>
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
    <script defer type="text/javascript" src="../JS-Scripts/nav.js"></script>
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
        const gert = document.querySelector('#gert')
        gert.addEventListener = () => {
            Window.location.href = '../MainForm.php'
        }
    </script>
</body>


</html>