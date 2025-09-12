<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Verification</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #e6cc00;
            text-align: center;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 40%;
            margin: 50px auto;
            background-color: #fdf4dc;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0px 0px 10px 0px rgba(0,0,0,0.1);
        }
        h2 {
            color: #333;
            margin-bottom: 20px;
        }
        .btn {
            background-color: #e6cc00;
            color: white;
            padding: 10px 20px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            margin-top: 20px;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }
        .btn:hover {
            background-color: #45a049;
        }
        .error-msg {
            color: #d9534f;
            margin-top: 20px;
        }
    </style>
</head>
<body>
<?php
require_once "../connection/connection.php";

function verifyEmail($conn, $email) {
    $sql = "UPDATE tbluser SET isverified = true WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();

    return $stmt->affected_rows > 0;
}

session_start();

if (isset($_GET['email'])) {
    $email = urldecode($_GET['email']);

    if (verifyEmail($conn, $email)) {
        echo "<div class='container'>";
        echo "<h2>Email Successfully Verified!</h2>";
        echo "<a class='btn' href='../ArdeurDeFrance-FrontEnd/LogInSignUpForm.php'>Return to Login/SignUp</a>";
        echo "</div>";
    } else {
        echo "<div class='container'>";
        echo "<h2>Failed to Verify Email!</h2>";
        echo "<p class='error-msg'>Please make sure you have entered a valid email address.</p>";
        echo "<a class='btn' href='../ArdeurDeFrance-FrontEnd/LogInSignUpForm.php'>Go Back to Login/SignUp</a>";
        echo "</div>";
    }
} else {
    echo "<div class='container'>";
    echo "<h2>No Email Provided!</h2>";
    echo "<p class='error-msg'>Please provide an email address to verify.</p>";
    echo "</div>";
}
?>
</body>
</html>
