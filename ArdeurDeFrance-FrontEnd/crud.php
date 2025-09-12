<?php
session_start();
if (!isset($_SESSION["userid"]) || $_SESSION["userid"] != 101) {
    header("Location: MainForm.php");
    exit();
}
$username = $_SESSION['username'];
$userid = $_SESSION['userid'];
require_once('../connection/connection.php');
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
    <title>CRUD Operations</title>
    <link rel="stylesheet" href="CSS-Files/crud.css">

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
                    <!-- <li><a href="#"><i class="fa-solid fa-desktop"></i> Admin </a></li> -->
                    <li><a href="../process/logout.php"><i class="fa-solid fa-right-from-bracket"></i> Logout</a></li>
                    <!-- <li><a href="#"><i class="fa-solid fa-cart-shopping"></i> Cart </a></li> -->
                    <li><a href="#"><i class="fa-solid fa-desktop"></i> View Orders </a></li>
                </ul>
            </div>
        </div>
    </div>
</header>
    <div class="admin-contents-container">
        <div class="admin-view">
        <div class="table-container">
            <h1>CRUD Operations</h1>
            <table class="crud">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Brand</th>
                        <th>Description</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Image</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                // Include your database connection file
                include_once '../connection/connection.php';

                // Retrieve data from the database
                $sql = "SELECT `productid`, `productname`, `description`, `price`, `quantity`, `brand` FROM `tblproduct`";
                $result = $conn->query($sql);
                
                // Check if there are any rows returned
                if ($result->num_rows > 0) {
                    // Loop through the rows and display data in table rows
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr id='row{$row['productid']}'>";
                        echo "<td>" . $row["productid"] . "</td>";
                        echo "<td><span>{$row['productname']}</span><input type='text' style='display:none'></td>";
                        echo "<td><span>{$row['brand']}</span><input type='text' style='display:none'></td>";
                        echo "<td><span>{$row['description']}</span><input type='text' style='display:none'></td>";
                        echo "<td><span>{$row['price']}</span><input type='text' style='display:none'></td>";
                        echo "<td><span>{$row['quantity']}</span><input type='text' style='display:none'></td>";
                
                        // Check if the image file exists
                        $imagePathJPG = "../image/product-image/" . strtolower(str_replace(' ', '_', $row['productname'])) . ".jpg";
                        $imagePathPNG = "../image/product-image/" . strtolower(str_replace(' ', '_', $row['productname'])) . ".png";
                        if (file_exists($imagePathJPG)) {
                            echo "<td><img src='$imagePathJPG' alt='" . $row["productname"] . " Image' style='max-width: 100px; max-height: 100px;'></td>";
                        } elseif (file_exists($imagePathPNG)) {
                            echo "<td><img src='$imagePathPNG' alt='" . $row["productname"] . " Image' style='max-width: 100px; max-height: 100px;'></td>";
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
            <h2>User Orders</h2>
            <table>
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Account Name</th>
                        <th>Recipient</th>
                        <th>Address</th>
                        <th>Contact Number</th>
                        <th>Email</th>
                        <th>Product Name</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $totalAmount = 0; // Initialize total amount variable
                    $sql = "SELECT p.`orderNum`, u.`username`, p.`paymentid`, p.`name`, p.`address`, p.`contact_number`, 
                                    p.`email`, p.`status`, 
                                    GROUP_CONCAT(CONCAT(pr.`brand`, ' ', pr.`productname`) SEPARATOR '<br><br>') AS products, 
                                    GROUP_CONCAT(c.`quantity` SEPARATOR '<br><br>') AS quantities, 
                                    GROUP_CONCAT(pr.`price` SEPARATOR '<br><br>') AS prices, 
                                    SUM(c.`quantity` * pr.`price`) AS total_amount 
                            FROM `tblpayment` p 
                            JOIN `tblcart` c ON p.`cartid` = c.`cartid` 
                            JOIN `tblproduct` pr ON c.`productid` = pr.`productid` 
                            JOIN `tbluser` u ON c.`userid` = u.`userid` 
                            GROUP BY p.`orderNum`
                            ORDER BY p.`paymentid` ASC;";
     

                    $result = $conn->query($sql);

                    // Check if there are any rows returned
                    if ($result->num_rows > 0) {
                        // Loop through the rows and display data in table rows
                        while ($row = $result->fetch_assoc()) {
                            // Accumulate total amount only for rows with status "Pending"
                            if ($row['status'] === 'Pending') {
                                $totalAmount += $row['total_amount'];
                            }

                            // Output details for the current order
                            echo "<tr>";
                            echo "<td style='text-align: center;'>{$row['orderNum']}</td>";
                            echo "<td style='text-align: center;'>{$row['username']}</td>";
                            echo "<td style='text-align: center;'>{$row['name']}</td>";
                            echo "<td style='text-align: center;'>{$row['address']}</td>";
                            echo "<td style='text-align: center;'>{$row['contact_number']}</td>";
                            echo "<td style='text-align: center;'>{$row['email']}</td>";
                            echo "<td style='text-align: center;'>{$row['products']}</td>";
                            echo "<td style='text-align: center;'>{$row['quantities']}</td>";
                            echo "<td style='text-align: center;'>{$row['prices']}</td>";
                            echo "<td style='text-align: center;'>{$row['total_amount']}</td>";
                            echo "<td id='status-{$row['paymentid']}'>{$row['status']}</td>"; // Display status for each payment
                            echo "<td><button onclick='processOrder({$row['paymentid']})'>Process Order</button></td>"; // Process order button
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='12'>No orders found</td></tr>";
                    }

                    // Close connection
                    $conn->close();
                    ?>
                </tbody>
            </table>

        </div>
        <div class="form-container">
        <h2>Add New Item</h2>
        <form action="../process/additem.php" method="post" enctype="multipart/form-data">
            <label for="itemName">Name:</label>
            <input type="text" id="itemName" name="itemName" required><br>
            <label for="itemBrand">Brand:</label>
            <input type="text" id="itemBrand" name="itemBrand" required><br>
            <label for="itemDescription">Description:</label>
            <input type="text" id="itemDescription" name="itemDescription" required><br>
            <label for="itemPrice">Price:</label>
            <input type="text" id="itemPrice" name="itemPrice" required><br>
            <label for="itemQuantity">Quantity:</label>
            <input type="number" id="itemQuantity" name="itemQuantity" min="1" required><br>
            <label for="itemGender">Gender:</label>
            <select id="itemGender" name="itemGender" required>
                <option value="Male">Male</option>
                <option value="Female">Female</option>
            </select><br>
            <label for="imageUpload">Choose Image:</label>
            <input type="file" id="imageUpload" name="imageUpload" accept="image/*" onchange="previewImage(event)" required><br>
            <img id="imagePreview" src="#" alt="Image Preview">
            <button id="removeImageButton" type="button" onclick="removeImage()">Remove Image</button>
            <button type="submit">Add Item</button>
        </form>
        </div>

        </div>
        
    
    </div>
    

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

    // Find the quantity input field
    const quantityInput = row.querySelector('td:nth-child(6) input');
    if (quantityInput) {
        updatedValues[5] = quantityInput.value; // Update quantity value
    } else {
        console.error(`Quantity input not found in row ${id}`);
        return;
    }

    // Send the updatedValues to your PHP script for updating the database
    const formData = new FormData();
    formData.append('id', id);
    formData.append('productname', updatedValues[1]);
    formData.append('brand', updatedValues[2]);
    formData.append('description', updatedValues[3]);
    formData.append('price', updatedValues[4]);
    formData.append('quantity', updatedValues[5]); // Use updated quantity value

    const xhr = new XMLHttpRequest();
    xhr.open('POST', '../process/updateitem.php', true);
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
            xhr.open('POST', '../process/deleteitem.php', true);
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
<script>
    function processOrder(paymentId) {
        if (confirm("Are you sure you want to process this order?")) {
            const xhr = new XMLHttpRequest();
            xhr.open('POST', '../process/updatestatus.php', true);
            xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = function () {
                if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
                    const statusCell = document.getElementById(`status-${paymentId}`);
                    statusCell.innerText = 'Paid';
                }
            };
            xhr.send(`paymentId=${paymentId}&status=Paid`);
        }
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
        <script defer type="text/javascript" src="JS-Scripts/nav.js"></script>
</body>
</html>
