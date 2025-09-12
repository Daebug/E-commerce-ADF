<?php
session_start();
if (!isset($_SESSION["userid"])) {
    header("Location: LogInSignUpForm.php");
    exit();
}

require_once('../connection/connection.php');

if (isset($_POST['productid'])) {
    $productid = $_POST['productid'];
    
    // Prepare a delete statement
    $sql = "DELETE FROM tblcart WHERE productid = ? AND userid = ?";
    
    if ($stmt = $conn->prepare($sql)) {
        // Bind variables to the prepared statement as parameters
        $stmt->bind_param("ii", $productid, $_SESSION['userid']);
        
        // Attempt to execute the prepared statement
        if ($stmt->execute()) {
            // Row deleted successfully
            echo "Item removed from cart successfully.";
        } else {
            // An error occurred
            echo "Error: Unable to remove item from cart.";
        }

        // Close the statement
        $stmt->close();
    }
}

// Close the connection
$conn->close();
?>
