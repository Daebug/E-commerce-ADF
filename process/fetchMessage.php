<?php
session_start();
if (!isset($_SESSION["userid"])) {
    header("Location: LogInSignUpForm.php");
    exit();
}

require_once('../connection/connection.php');

if (isset($_GET['userid'])) {
    $userid = $_GET['userid'];
    $currentUserId = $_SESSION['userid'];
    $lastMessageId = isset($_GET['lastMessageId']) ? $_GET['lastMessageId'] : 0;

    // Modified query to include message ID and filter by lastMessageId
    $query = "
        SELECT c.id, c.sender, c.message, u.username 
        FROM tblchat c 
        JOIN tbluser u ON c.sender = u.userid 
        WHERE 
            ((c.receiver = ? AND c.sender = ?) OR (c.receiver = ? AND c.sender = ?))
            AND c.id > ? 
        ORDER BY c.id ASC
    ";

    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 'ssssi', $userid, $currentUserId, $currentUserId, $userid, $lastMessageId);

    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($result) {
        $messages = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $isSender = $row['sender'] == $currentUserId;
            $messages[] = [
                'id' => $row['id'], // Include message ID
                'sender' => $row['username'],
                'message' => $row['message'],
                'isSender' => $isSender
            ];
        }
        echo json_encode($messages);
    } else {
        echo "Error executing query: " . mysqli_error($conn);
    }

    mysqli_stmt_close($stmt);
} else {
    echo "User ID not provided";
}
?>
