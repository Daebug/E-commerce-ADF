<?php
session_start();
if (!isset($_SESSION["userid"]) || $_SESSION["userid"] != 101) {
    header("Location: MainForm.php");
    exit();
}
$username = $_SESSION['username'];
$userid = $_SESSION['userid'];
require_once('../../connection/connection.php');
try {
    $sqlCountUsers = "SELECT COUNT(*) FROM tbluser WHERE userid <> 101";
    $resultCount = $conn->query($sqlCountUsers);
    $totalUsers = $resultCount->fetch_row()[0];
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
    exit();
}

try {
    $sqlCountOrders = "SELECT COUNT(DISTINCT orderNum) FROM tblpayment";
    $resultCount = $conn->query($sqlCountOrders);
    $totalOrders = $resultCount->fetch_row()[0];
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
    exit();
}

try {
    $sqlCountPendingOrders = "
    SELECT COUNT(DISTINCT orderNum) 
    FROM tblpayment 
    WHERE orderStatus IS NULL OR orderStatus = 'To Receive'";

    $resultCountPending = $conn->query($sqlCountPendingOrders);
    $totalPendingOrders = $resultCountPending->fetch_row()[0];
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
    exit();
}

try {
    $sqlBestProducts = "
    SELECT p.brand, p.productname, COALESCE(SUM(c.quantity), 0) AS order_count, 
        COALESCE(SUM(c.quantity * p.price), 0) AS total_price
    FROM tblproduct p
    LEFT JOIN tblcart c ON p.productid = c.productid
    GROUP BY p.brand, p.productname
    ORDER BY order_count DESC
    LIMIT 3";

    $resultBestProducts = $conn->query($sqlBestProducts);
    $bestProducts = [];

    while ($row = $resultBestProducts->fetch_assoc()) {
        $bestProducts[] = [
            'product_name' => htmlspecialchars($row['brand'] . ' ' . $row['productname']),
            'order_count' => htmlspecialchars($row['order_count']),
            'total_price' => number_format($row['total_price'], 2)
        ];
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
    exit();
}

try {
    $sqlRecentOrders = "
    SELECT pay.orderNum, 
           GROUP_CONCAT(DISTINCT CONCAT(pr.brand, ' ', pr.productname, ' ', 
                                        CASE 
                                            WHEN c.quantity = 1 THEN '(1pc)' 
                                            ELSE CONCAT('(', c.quantity, 'pcs)') 
                                        END)
                        ORDER BY pr.productname SEPARATOR ', ') AS products,
           SUM(c.quantity * pr.price) AS total_price
    FROM tblpayment pay
    JOIN tblcart c ON pay.cartid = c.cartid
    JOIN tblproduct pr ON c.productid = pr.productid
    GROUP BY pay.orderNum 
    ORDER BY MAX(pay.paymentid) DESC LIMIT 3";

    $resultRecentOrders = $conn->query($sqlRecentOrders);
    $recentOrders = [];

    while ($row = $resultRecentOrders->fetch_assoc()) {
        $recentOrders[] = [
            'orderNum' => htmlspecialchars($row['orderNum']),
            'products' => htmlspecialchars($row['products']),
            'total_price' => number_format($row['total_price'], 2)
        ];
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
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
    <title>ArdeurDeFrance - Admin</title>

    <link rel="icon" type="image/png" href="../Source-Files/Logo3.png">
    <link rel="stylesheet" type="text/css" href="../CSS-Files/GeneralSheet.css">
    <link rel="stylesheet" type="text/css" href="../CSS-Files/admin-css/header-footer-navbar.css">
    <link rel="stylesheet" type="text/css" href="../CSS-Files/admin-css/admin.css">
    <link rel="stylesheet" type="text/css" href="../CSS-Files/profile.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <script defer type="text/javascript" src=""></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


</head>

<body>
    <header id="MainFormHeader">
        <div id="MainFormHeaderTopPart">
            <div id='gert' style="display: flex; align-items: center;">
                <img id="MainFormHeaderLogo" src="../Source-Files/Logo3.png" alt="">
                <p>ARDEUR DE FRANCE</p>
            </div>

            <i id="MainFormHeaderMenuButton" class="menu-button fa-solid fa-bars"></i>
            <div class="navbar" id="navbar">
                <ul>
                    <li><a href="../MainForm.php"><i class="fa-solid fa-house-user"></i> Home </a></li>
                    <li><a href="../Chat.php"><i class="fa-solid fa-message"></i> Chat </a></li>
                    <li><a href="../process/logout.php"><i class="fa-solid fa-right-from-bracket"></i> Logout</a></li>
                </ul>
            </div>
        </div>
    </header>

    <main id="MainFormMain">
        <div class="main-content-container">
            <div class="navbar-container">

                <div class="table-title-container">
                    <h2>Ardeur De France</h2>
                    <p>Luxury that owns quality</p>
                </div>
                <div class="table-container" id="table-container">
                    <div class="total-users-table" id="total-users-table">
                        <?php
                        include('../../connection/connection.php');
                        $sql = "SELECT userid, username, email, contact_number, CASE WHEN userid = 101 THEN 'Admin' ELSE 'User' END AS role FROM tbluser";
                        $result = $conn->query($sql);
                        ?>
                        <button id="print-users-table">Print users table</button>

                        <table>
                            <thead>
                                <tr>
                                    <th>User ID</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Role</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if ($result->num_rows > 0) {
                                    while ($row = $result->fetch_assoc()) {
                                        echo "<tr>";
                                        echo "<td>" . $row['userid'] . "</td>";
                                        echo "<td>" . htmlspecialchars($row['username']) . "</td>";
                                        echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                                        echo "<td>" . htmlspecialchars($row['contact_number']) . "</td>";
                                        echo "<td>" . $row['role'] . "</td>";
                                        echo "</tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='5'>No users found</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                    <?php

                    include '../../connection/connection.php';
                    $query = "
                        SELECT p.productname, p.brand, p.price, COALESCE(SUM(c.quantity), 0) AS sales 
                        FROM tblproduct p
                        LEFT JOIN tblcart c ON p.productid = c.productid
                        GROUP BY p.productid, p.productname, p.brand, p.price
                    ";
                    $result = mysqli_query($conn, $query);
                    if (!$result) {
                        die("Query failed: " . mysqli_error($conn));
                    }
                    $totalSales = 0;
                    ?>

                    <div class="sales-summary-table" id="sales-summary-table">
                        <button id="print-sales-summary-button">Print Sales Summary</button>
                        <table>
                            <thead>
                                <tr>
                                    <th>Product Name</th>
                                    <th>Price</th>
                                    <th>Sales</th>
                                    <th>Total Price</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                                    <?php
                                    $totalPrice = $row['price'] * $row['sales'];
                                    $totalSales += $totalPrice;
                                    ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($row['brand'] . ' ' . $row['productname']); ?></td>
                                        <td><?php echo '₱' . number_format($row['price'], 2); ?></td>
                                        <td><?php echo $row['sales']; ?></td>
                                        <td><?php echo '₱' . number_format($totalPrice, 2); ?></td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="3" style="text-align:right;">Total Sales:</td>
                                    <td><?php echo '₱' . number_format($totalSales, 2); ?></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <div class="manage-inventory-table" id="manage-inventory">
                        <table>
                            <thead>
                                <tr>
                                    <th style="text-align: center;">ID</th>
                                    <th style="text-align: center;">Name</th>
                                    <th style="text-align: center;">Brand</th>
                                    <th style="text-align: center;">Description</th>
                                    <th style="text-align: center;">Price</th>
                                    <th style="text-align: center;">Quantity</th>
                                    <th style="text-align: center;">Image</th>
                                    <th style="text-align: center;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                include_once '../../connection/connection.php';
                                $sql = "SELECT `productid`, `productname`, `description`, `price`, `quantity`, `brand` FROM `tblproduct`";
                                $result = $conn->query($sql);
                                if ($result->num_rows > 0) {
                                    while ($row = $result->fetch_assoc()) {
                                        echo "<tr id='row{$row['productid']}'>";
                                        echo "<td>" . $row["productid"] . "</td>";
                                        echo "<td style='text-align: center;'><span>{$row['productname']}</span><input type='text' style='display:none'></td>";
                                        echo "<td style='text-align: center;'><span>{$row['brand']}</span><input type='text' style='display:none'></td>";
                                        echo "<td style='text-align: center;'><span>{$row['description']}</span><input type='text' style='display:none'></td>";
                                        echo "<td style='text-align: center;'><span>₱" . number_format($row['price'], 2) . "</span><input type='text' style='display:none'></td>";
                                        echo "<td style='text-align: center;'><span>{$row['quantity']}</span><input type='text' style='display:none'></td>";

                                        $imagePathJPG = "../../image/product-image/" . strtolower(str_replace(' ', '_', $row['productname'])) . ".jpg";
                                        $imagePathPNG = "../../image/product-image/" . strtolower(str_replace(' ', '_', $row['productname'])) . ".png";
                                        if (file_exists($imagePathJPG)) {
                                            echo "<td style='text-align: center;'><img src='$imagePathJPG' alt='" . $row["productname"] . " Image' style='max-width: 100px; max-height: 100px;'></td>";
                                        } elseif (file_exists($imagePathPNG)) {
                                            echo "<td style='text-align: center;'><img src='$imagePathPNG' alt='" . $row["productname"] . " Image' style='max-width: 100px; max-height: 100px;'></td>";
                                        } else {
                                            echo "<td>No Image</td>";
                                        }

                                        echo "<td>
                                                <button onclick='editRow({$row['productid']})'>Edit</button>
                                                <button onclick='deleteRow({$row['productid']})'>Delete</button>
                                            </td>";
                                        echo "</tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='8'>No items found</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                        <div class="add-button-container">
                            <button id="add-new-item-button">Add New Item</button>
                        </div>

                        <div id="add-modal" class="modal">
                            <div class="modal-content">
                                <span class="close">&times;</span>
                                <form id="add-item-form" action="../../process/additem.php" method="POST" enctype="multipart/form-data">
                                    <label class="modal-label" for="item-name">Name:</label>
                                    <input type="text" id="item-name" name="itemName" required><br><br>

                                    <label class="modal-label" for="brand">Brand:</label>
                                    <input type="text" id="brand" name="itemBrand" required><br><br>

                                    <label class="modal-label" for="description">Description:</label>
                                    <input type="text" id="description" name="itemDescription" required><br><br>

                                    <label class="modal-label" for="price">Price:</label>
                                    <input type="number" id="price" name="itemPrice" min="0" step="0.01" required><br><br>

                                    <label class="modal-label" for="quantity">Quantity:</label>
                                    <input type="number" id="quantity" name="itemQuantity" min="1" required><br><br>

                                    <label class="modal-label" for="gender">Gender:</label>
                                    <select id="gender" name="itemGender" required>
                                        <option value="Male">Male</option>
                                        <option value="Female">Female</option>
                                    </select><br><br>

                                    <label for="imageUpload">Choose Image:</label>
                                    <input type="file" id="imageUpload" name="imageUpload" accept="image/*" onchange="previewImage(event)" required><br>
                                    <img id="imagePreview" src="#" alt="Image Preview">
                                    <button id="removeImageButton" type="button" onclick="removeImage()">Remove Image</button>

                                    <button type="submit">Add</button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="recent-orders-table" id="recent-orders-table">
                        <button id="print-users-table">Print recent orders table</button>

                        <table>
                            <thead>
                                <tr>
                                    <th>Order ID</th>
                                    <th>Product</th>
                                    <th>Total Price</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                try {
                                    $sqlRecentOrders = "
                                    SELECT pay.orderNum, 
                                        GROUP_CONCAT(DISTINCT CONCAT(pr.brand, ' ', pr.productname, ' ', 
                                                                    CASE 
                                                                        WHEN c.quantity = 1 THEN '(1pc)' 
                                                                        ELSE CONCAT('(', c.quantity, 'pcs)') 
                                                                    END)
                                            ORDER BY pr.productname SEPARATOR ', ') AS products,
                                        SUM(c.quantity * pr.price) AS total_price
                                    FROM tblpayment pay
                                    JOIN tblcart c ON pay.cartid = c.cartid
                                    JOIN tblproduct pr ON c.productid = pr.productid
                                    GROUP BY pay.orderNum 
                                    ORDER BY MAX(pay.paymentid) DESC LIMIT 3";

                                    $resultRecentOrders = $conn->query($sqlRecentOrders);

                                    while ($row = $resultRecentOrders->fetch_assoc()) {
                                        $orderNum = htmlspecialchars($row['orderNum']);
                                        $products = htmlspecialchars($row['products']);
                                        $totalPrice = '₱' . number_format($row['total_price'], 2);
                                        echo "<tr>
                                                <td>{$orderNum}</td>
                                                <td>{$products}</td>
                                                <td>{$totalPrice}</td>
                                            </tr>";
                                    }
                                } catch (Exception $e) {
                                    echo "<tr><td colspan='4'>Error: " . htmlspecialchars($e->getMessage()) . "</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="manage-orders-table" id="manage-orders-table">

                        <table>
                            <table>
                                <thead>
                                    <tr>
                                        <th style="text-align: center;">Delivery Info</th>
                                        <th style="text-align: center;">Product Name</th>
                                        <th style="text-align: center;">Quantity</th>
                                        <th style="text-align: center;">Price</th>
                                        <th style="text-align: center;">Amount</th>
                                        <th style="text-align: center;">Status</th>
                                        <th style="text-align: center;">Order Status</th>
                                        <th style="text-align: center;">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $totalAmount = 0;
                                    $sql = "SELECT p.`orderNum`, u.`username`, p.`paymentid`, p.`name`, p.`address`, p.`contact_number`, 
                                                    p.`email`, p.`status`, p.`orderStatus`, p.`remark`,
                                                    GROUP_CONCAT(CONCAT(pr.`brand`, ' ', pr.`productname`) SEPARATOR '<br><br>') AS products, 
                                                    GROUP_CONCAT(c.`quantity` SEPARATOR '<br><br>') AS quantities, 
                                                    GROUP_CONCAT(CONCAT('₱', FORMAT(pr.`price`, 2)) SEPARATOR '<br><br>') AS prices, 
                                                    SUM(c.`quantity` * pr.`price`) AS total_amount 
                                            FROM `tblpayment` p 
                                            JOIN `tblcart` c ON p.`cartid` = c.`cartid` 
                                            JOIN `tblproduct` pr ON c.`productid` = pr.`productid` 
                                            JOIN `tbluser` u ON c.`userid` = u.`userid` 
                                            GROUP BY p.`orderNum`
                                            ORDER BY 
                                                FIELD(p.`orderStatus`, NULL, 'To Receive', 'Completed'), 
                                                p.`paymentid` ASC;";

                                    $result = $conn->query($sql);
                                    if ($result->num_rows > 0) {
                                        while ($row = $result->fetch_assoc()) {
                                            if ($row['status'] === 'Pending') {
                                                $totalAmount += $row['total_amount'];
                                            }
                                            // Concatenate delivery info into one cell
                                            $deliveryInfo = "Name: {$row['name']}<br>" .
                                                "Address: {$row['address']}<br>" .
                                                "Contact No: {$row['contact_number']}<br>" .
                                                "Email: {$row['email']}";

                                            echo "<tr>";
                                            echo "<td style='text-align: left;'>{$deliveryInfo}</td>";
                                            echo "<td style='text-align: center;'>{$row['products']}</td>";
                                            echo "<td style='text-align: center;'>{$row['quantities']}</td>";
                                            echo "<td style='text-align: center;'>{$row['prices']}</td>";
                                            echo "<td style='text-align: center;'>₱" . number_format($row['total_amount'], 2) . "</td>";
                                            echo "<td id='status-{$row['paymentid']}' style='text-align: center;'>{$row['status']}</td>";
                                            echo "<td id='order-status-{$row['paymentid']}'>{$row['orderStatus']}</td>";
                                            echo "<td>";
                                            if (is_null($row['orderStatus'])) {
                                                echo "<form action='../../process/updatestatus.php' method='post'>
                                                        <input type='hidden' name='paymentid' value='{$row['paymentid']}'>
                                                        <input type='hidden' name='status' value='Processed'>
                                                        <input type='submit' value='Process Order'>
                                                    </form>";
                                            } elseif ($row['orderStatus'] === 'To Receive' && $row['remark'] === "The system has detected that your order has been delivered.") {
                                                echo "<button disabled>Buyer has been notified</button>";
                                            } elseif ($row['orderStatus'] === 'To Receive') {
                                                echo "<form action='../../process/notifydelivered.php' method='post'>
                                                        <input type='hidden' name='orderNum' value='{$row['orderNum']}'>
                                                        <input type='submit' value='Parcel Delivered'>
                                                    </form>";
                                            }
                                            echo "</td>";
                                            echo "</tr>";
                                        }
                                    } else {
                                        echo "<tr><td colspan='8'>No orders found</td></tr>";
                                    }
                                    $conn->close();
                                    ?>
                                </tbody>
                            </table>

                    </div>
                </div>

            </div>
            <div class="admin-interface-container">
                <div class="dashboard-container">
                    <div class="dashboard total-users" data-target="total-users-table">
                        <h1>Total Users</h1>
                        <p><?php echo $totalUsers; ?></p>
                    </div>
                    <div class="dashboard sales-summary" data-target="sales-summary-table" style="grid-column: span 2;">
                        <h1>Sales Summary</h1>
                        <p>Click to view sales for each product</p>
                    </div>
                    <div class="dashboard best-products" style="grid-column: span 2; grid-row: span 3;">
                        <h1>Best Products</h1>
                        <ul>
                            <?php foreach ($bestProducts as $product): ?>
                                <li>
                                    <span class="product-name"><?php echo $product['product_name']; ?></span>
                                    <span class="order-count">= <?php echo $product['order_count']; ?> orders</span>
                                    <span class="product-price">₱<?php echo $product['total_price']; ?></span>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    <div class="dashboard manage-inventory" data-target="manage-inventory">
                        <h1>Manage Inventory</h1>
                        <p>Click here to manage your inventory</p>
                    </div>
                    <div class="dashboard recent-orders" data-target="recent-orders-table" style="grid-column: span 2; grid-row: span 2;">
                        <h1>Recent Orders</h1>
                        <ul>
                            <?php foreach ($recentOrders as $order): ?>
                                <li>Order #<?php echo $order['orderNum']; ?> - <?php echo $order['products']; ?> - ₱<?php echo $order['total_price']; ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    <div class="dashboard manage-orders" data-target="manage-orders-table">
                        <h1>Manage Orders</h1>
                        <p>View Orders Here</p>
                    </div>
                </div>
            </div>

    </main>
    <footer></footer>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script src="../JS-Scripts/admin-js/admin.js"></script>
    <script src="../JS-Scripts/nav.js"></script>
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
    const gert = document.querySelector('#gert')
    gert.addEventListener = () => {
        Window.location.href = 'MainForm.php'
    }
</script>
<!-- image previde and remove image scripts -->
<script>
    function previewImage(event) {
        const image = document.getElementById('imagePreview');
        image.src = URL.createObjectURL(event.target.files[0]);
        document.getElementById('removeImageButton').style.display = 'inline-block';
    }

    function removeImage() {
        const image = document.getElementById('imagePreview');
        image.src = '';
        document.getElementById('removeImageButton').style.display = 'none';
        document.getElementById('imageUpload').value = '';
    }
</script>
<!-- for edit and delete button -->
<script>
    function editRow(id) {
        const row = document.getElementById(`row${id}`);
        if (!row) {
            console.error(`Row ${id} not found`);
            return;
        }

        const spans = row.getElementsByTagName('span');
        const inputs = row.getElementsByTagName('input');

        for (let i = 0; i < spans.length; i++) {
            spans[i].style.display = 'none';
            inputs[i].style.display = 'inline-block';
            inputs[i].value = spans[i].innerText;
        }

        // Show and enable the quantity input field
        const quantitySpan = row.querySelector('td:nth-child(6) span');
        const quantityInput = row.querySelector('td:nth-child(6) input');
        if (quantitySpan && quantityInput) {
            quantitySpan.style.display = 'none';
            quantityInput.style.display = 'inline-block';
            quantityInput.value = quantitySpan.innerText;
        } else {
            console.error(`Quantity span or input not found in row ${id}`);
        }

        const editButton = row.querySelector('button');
        editButton.innerText = 'Save';
        editButton.setAttribute('onclick', `saveRow(${id})`);
    }

    function saveRow(id) {
        const row = document.getElementById(`row${id}`);
        const spans = row.getElementsByTagName('span');
        const inputs = row.getElementsByTagName('input');
        let updatedValues = {};

        for (let i = 0; i < spans.length; i++) {
            spans[i].style.display = 'inline-block';
            inputs[i].style.display = 'none';
            spans[i].innerText = inputs[i].value;
            updatedValues[spans[i].parentNode.cellIndex] = inputs[i].value;
        }

        // Remove currency symbol from price value
        const priceValue = updatedValues[4].replace(/[^0-9.]/g, '');
        updatedValues[4] = priceValue;

        // Send updated values to PHP script
        const formData = new FormData();
        formData.append('id', id);
        formData.append('productname', updatedValues[1]);
        formData.append('brand', updatedValues[2]);
        formData.append('description', updatedValues[3]);
        formData.append('price', updatedValues[4]);
        formData.append('quantity', updatedValues[5]);

        const xhr = new XMLHttpRequest();
        xhr.open('POST', '../../process/updateitem.php', true);
        xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
                const editButton = row.querySelector('button');
                editButton.innerText = 'Edit';
                editButton.setAttribute('onclick', `editRow(${id})`);
            }
        };
        xhr.send(formData);
    }
</script>
<script>
    function deleteRow(id) {
        if (confirm("Are you sure you want to delete this item?")) {
            const xhr = new XMLHttpRequest();
            xhr.open('POST', '../../process/deleteitem.php', true);
            xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
                    const row = document.getElementById(`row${id}`);
                    row.parentNode.removeChild(row);
                }
            };
            xhr.send(`productId=${id}`);
        }
    }
</script>
<!-- for process order -->
<script>
    function processOrder(paymentId) {
        if (confirm("Are you sure you want to process this order?")) {
            const xhr = new XMLHttpRequest();
            xhr.open('POST', '../../process/updatestatus.php', true);
            xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
                    const statusCell = document.getElementById(`status-${paymentId}`);
                    statusCell.innerText = 'Paid';
                }
            };
            xhr.send(`paymentId=${paymentId}&status=Paid`);
        }
    }
</script>

</html>