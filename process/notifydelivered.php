<?php
// Include your database connection file
include('../connection/connection.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the order number from the POST request
    $orderNum = $_POST['orderNum'];

    // Update the remark for the order with the given orderNum
    $sql = "UPDATE tblpayment SET remark = 'The system has detected that your order has been delivered.' WHERE orderNum = ?";

    // Prepare the statement
    if ($stmt = $conn->prepare($sql)) {
        // Bind the order number to the query
        $stmt->bind_param("s", $orderNum);

        // Execute the query
        if ($stmt->execute()) {
            $message = "Remark updated successfully!";
        } else {
            $message = "Error updating remark: " . $conn->error;
        }

        // Close the statement
        $stmt->close();
    } else {
        $message = "Error preparing statement: " . $conn->error;
    }

    // Close the connection
    $conn->close();
}
?>

<!-- Output message and return link with styling -->
<div style="display: flex; justify-content: center; align-items: center; height: 100vh; flex-direction: column; text-align: center;">
    <div style="background-color: #f0f0f0; padding: 20px; border-radius: 10px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); max-width: 400px;">
        <p style="font-size: 18px; color: #333;"><?php echo $message; ?></p>
        <a href="javascript:history.back()" style="display: inline-block; padding: 10px 20px; background-color: #4CAF50; color: white; text-decoration: none; border-radius: 5px; font-size: 16px;">Return</a>
    </div>
</div>