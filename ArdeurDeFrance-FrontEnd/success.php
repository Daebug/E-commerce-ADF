<?php
include_once '../process/config.php';

// session_start();

if (!isset($_SESSION["userid"])) {
    header("Location: LogInSignUpForm.php");
    exit();
}

$username = $_SESSION['username'];
$userid = $_SESSION['userid'];

require_once('../connection/connection.php');

if (isset($_GET['paymentid'])) {
    $paymentIds = explode(',', $_GET['paymentid']);

    foreach ($paymentIds as $paymentID) {
        // Generate the order number
        date_default_timezone_set('Asia/Manila'); // Set to your timezone
        $orderNum = date('mdYHisA'); // Generate order number in the desired format

        // Update payment status and order number
        $sql_update_status = "UPDATE `tblpayment` SET `status` = 'Paid', `orderNum` = ? WHERE `paymentid` = ?";
        $stmt = $conn->prepare($sql_update_status);
        $stmt->bind_param('ss', $orderNum, $paymentID);
        if ($stmt->execute()) {
            $sqlUpdateProduct = "UPDATE `tblproduct` p
                                 JOIN `tblcart` c ON p.`productid` = c.`productid`
                                 SET p.`quantity` = p.`quantity` - c.`quantity`
                                 WHERE c.`cartid` IN (SELECT `cartid` FROM `tblpayment` WHERE `paymentid` = ?)";
            $stmtUpdateProduct = $conn->prepare($sqlUpdateProduct);
            $stmtUpdateProduct->bind_param('s', $paymentID);
            if ($stmtUpdateProduct->execute()) {
                // Quantity updated successfully
            } else {
                echo "Error updating product quantity: " . $conn->error;
            }
            $stmtUpdateProduct->close();
        } else {
            echo "Error updating payment status for payment ID {$paymentID}: " . $conn->error;
        }
        $stmt->close();
    }
} else {
    // Payment IDs not set
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="css-files/success.css">
</head>
<body>
    <h1>PAYMENT SUCCESS!</h1>
    <div class="back-button">
        <button type="button" onclick="goBack()">Back to Home Page</button>
    </div>
</body>
<script>
    function goBack() {
        window.location.href = 'MainForm.php';
    }
</script>
</html>
