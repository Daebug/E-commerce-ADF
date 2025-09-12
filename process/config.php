<?php
session_start();
$username = $_SESSION['username'];
$userid = $_SESSION['userid'];
include_once '../connection/connection.php';

$sql_pending = "SELECT p.paymentid, p.cartid, p.name, p.address, p.contact_number, p.email, p.status, c.productid, c.quantity, pr.price, pr.productname, pr.brand
                FROM tblpayment p
                JOIN tblcart c ON p.cartid = c.cartid
                JOIN tblproduct pr ON c.productid = pr.productid
                WHERE c.userid = $userid AND p.status = 'Pending'";
$result_pending = $conn->query($sql_pending);

$orderItems = [];
$totalAmountPending = 0;
$paymentIds = [];
$paymentIds = [];
while ($row = $result_pending->fetch_assoc()) {
    $amount = $row['quantity'] * $row['price'];
    $totalAmountPending += $amount; 

    $orderItems[] = [
        'name' => 'Product: '. $row['productname'],
        'unit_amount' => [
            'currency_code' => 'PHP', 
            'value' => $row['price'],
        ],
        'quantity' => $row['quantity'],
        'brand' => $row['brand'],
    ];

    $paymentIds[] = $row['paymentid'];
}

$paypal_return_url = 'http://localhost/ELECTRONIC%20COMMERCE/ArdeurDeFrance/ArdeurDeFrance-FrontEnd/success.php?paymentid='. implode(',', $paymentIds);

define('PAYPAL_ID', 'sb-ftztl29885942@business.example.com');
define('PAYPAL_SANDBOX', TRUE);
define('PAYPAL_RETURN_URL', $paypal_return_url);
define('PAYPAL_CANCEL_URL', 'http://localhost:3000/paypal/cancel.php');
define('PAYPAL_CURRENCY', 'PHP');
define('PAYPAL_URL', (PAYPAL_SANDBOX == true)? "https://www.sandbox.paypal.com/cgi-bin/webscr" : "https://www.paypal.com/cgi-bin/webscr");
?>
<!DOCTYPE html>
<form action="<?php echo $paypal_return_url;?>" method="POST">
    <input type="hidden" name="payment_ids" value="<?php echo implode(',', $paymentIds);?>">
</form>
</html>