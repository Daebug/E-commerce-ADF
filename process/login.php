<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Include your database connection file
    include_once "../connection/connection.php";

    $username = $_POST["username"];
    $password = $_POST["password"];


    
    $sql = "SELECT * FROM tbluser WHERE username = ? AND isverified = true";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        // Verify the password using password_verify
        if (password_verify($password, $row["password"])) {
            // Password is correct, set session variables
            $_SESSION["userid"] = $row["userid"];
            $_SESSION["username"] = $row["username"];
            // Redirect to the main page or dashboard
            header("Location: ../ArdeurDeFrance-FrontEnd/MainForm.php");
            exit();
        } else {
            // Password is incorrect
            header("Location: ../ArdeurDeFrance-FrontEnd/LogInSignUpForm.php?error=invalid_credentials");
            exit();
        }
    } else {
        // User not found or not verified
        header("Location: ../ArdeurDeFrance-FrontEnd/LogInSignUpForm.php?error=invalid_credentials");
        exit();
    }
}
?>
