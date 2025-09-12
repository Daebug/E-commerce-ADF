<?php
require_once "PHPMailer-master/src/PHPMailer.php";
require_once "PHPMailer-master/src/SMTP.php";
require_once "PHPMailer-master/src/Exception.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include_once "../connection/connection.php";

    $fullName = $_POST["full_name"];
    $email = $_POST["email"];
    $contactNumber = $_POST["contact_number"];
    $password = $_POST["password"];


    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

 
    $isVerified = false;

    $sql = "INSERT INTO tbluser (username, email, contact_number, password, isverified) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssi", $fullName, $email, $contactNumber, $hashedPassword, $isVerified);
    $stmt->execute();

  
    $mail = new PHPMailer(true);
    try {
       
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; 
        $mail->SMTPAuth = true;
        $mail->Username = 'jfredlim1303@gmail.com'; 
        $mail->Password = 'uffi rloz sffp zpgu'; 
        $mail->SMTPSecure = 'tls'; 
        $mail->Port = 587; 
      
        $mail->setFrom('ArdeurDefrance@gmail.com', 'ArdeurDefrance');
        $mail->addAddress($email, $fullName); 
        $mail->isHTML(true); 
        $mail->Subject = 'Welcome to our website';
   
        $mail->Body = 'Thank you for signing up! Click <a href="http://localhost:/ELECTRONIC%20COMMERCE/ArdeurDeFrance/process/confirm.php?email='. urlencode($email) . '">here</a> to verify your email.'; 

       
        $mail->send();
    } catch (Exception $e) {
      
        echo 'Message could not be sent.';
        echo 'Mailer Error: ' . $mail->ErrorInfo;
    }

    header("Location: ../ArdeurDeFrance-FrontEnd/LogInSignUpForm.php");
    exit();
}
?>
