<?php
session_start();
if (!isset($_SESSION["userid"])) {
    header("Location: LogInSignUpForm.php");
    exit();
}

require_once('../connection/connection.php');

if (isset($_POST['receiver_id']) && isset($_POST['message'])) {
    $receiverId = $_POST['receiver_id'];
    $message = $_POST['message'];
    $senderId = $_SESSION['userid']; // Get the sender's user ID

    // Process the message (e.g., save it to the database)
    $query = "INSERT INTO `tblchat` (`sender`, `message`, `receiver`) VALUES (?, ?, ?)";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 'sss', $senderId, $message, $receiverId);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    // Redirect back to the chat page
    header("Location: ../ArdeurDeFrance-FrontEnd/Chat.php");
    exit();
} else {
    // Redirect back to the chat page with an error message (optional)
    header("Location: ../ArdeurDeFrance-FrontEnd/Chat.php?error=1");
    exit();
}
?>
