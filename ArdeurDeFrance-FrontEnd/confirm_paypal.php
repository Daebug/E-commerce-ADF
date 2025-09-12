<?php
// session_start();

include_once '../connection/connection.php'; 
include_once '../process/config.php'; 


if (!isset($_SESSION["userid"])) {
    header("Location: LogInSignUpForm.php");
    exit();
}


$username = $_SESSION['username'];
$userid = $_SESSION['userid'];

date_default_timezone_set('Asia/Manila');
$orderNum = date('mdYhisA');
$sql_pending = "SELECT p.paymentid, p.cartid, p.name, p.address, p.contact_number, p.email, p.status, c.productid, c.quantity, pr.price, pr.productname, pr.brand
                FROM tblpayment p
                JOIN tblcart c ON p.cartid = c.cartid
                JOIN tblproduct pr ON c.productid = pr.productid
                WHERE c.userid = $userid AND p.status = 'Pending'";
$result_pending = $conn->query($sql_pending);

$orderItems = [];
$totalAmountPending = 0;
$paymentIds = []; // Array to store multiple payment IDs

while ($row = $result_pending->fetch_assoc()) {
    // Calculate amount for each product
    $amount = $row['quantity'] * $row['price'];
    $totalAmountPending += $amount; 

    $orderItems[] = [
        'name' => 'Product: ' . $row['productname'],
        'unit_amount' => [
            'currency_code' => 'PHP', 
            'value' => $row['price'],
        ],
        'quantity' => $row['quantity'],
        'brand' => $row['brand'],
    ];

    $paymentIds[] = $row['paymentid']; // Add payment ID to the array
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirm PayPal Payment</title>
    <link rel="stylesheet" href="css-files/payment.css"> 
</head>
<body>
    <form action="<?php echo PAYPAL_URL; ?>" method="post" id="checkout_form">
        <div class="container">
            <h2>Your Pending Orders</h2>
            <p>Order Number: <?php echo $orderNum; ?></p>

            <?php if (!empty($orderItems)): ?>
                <?php foreach ($orderItems as $item): ?>
                    <div>
                        <p><?php echo $item['name']; ?> - ₱<?php echo number_format($item['unit_amount']['value'], 2); ?></p>
                        <p>Brand: <?php echo $item['brand'];?></p>
                        <p>Quantity: <?php echo $item['quantity'];?></p>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div>
                    <p>No pending orders.</p>
                </div>
            <?php endif; ?>

            <div class="total-price">
                <p>Total Amount (Pending): ₱<?php echo number_format($totalAmountPending, 2); ?></p>
            </div>

            <div class="back-button">
                <button type="button" onclick="goBack()">Back to Orders</button>
            </div>
        </div>

        <div class="checkout-button">
            <input type="hidden" name="business" value="<?php echo PAYPAL_ID; ?>">
            <input type="hidden" name="currency_code" value="<?php echo PAYPAL_CURRENCY; ?>">
            <input type="hidden" name="return" value="<?php echo PAYPAL_RETURN_URL; ?>">
            <input type="hidden" name="cancel_return" value="<?php echo PAYPAL_CANCEL_URL; ?>">
            <input type="hidden" name="cmd" value="_xclick">
            <input type="hidden" name="amount" value="<?php echo $totalAmountPending; ?>">
            <input type="hidden" name="item_name" value="<?php echo htmlspecialchars(json_encode(array_column($orderItems, 'name'))); ?>">
            <input type="hidden" name="item_price" value="<?php echo htmlspecialchars(json_encode(array_column($orderItems, 'unit_amount'))); ?>">
            <input type="hidden" name="payment_ids" value="<?php echo implode(',', $paymentIds); ?>">
            <button type="submit" id="checkout_btn">Proceed to PayPal</button>
        </div>
    </form>

    <script>
        function goBack() {
            window.history.back();
        }
    </script>
</body>
</html>
