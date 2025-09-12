<?php
session_start();
if (!isset($_SESSION["userid"])) {
    header("Location: LogInSignUpForm.php");
    exit();
}
$username = $_SESSION['username'];
$userid = $_SESSION['userid'];
require_once('../connection/connection.php')
?>
<!DOCTYPE html>
<html lang="en">
<head>
        <meta charset="UTF-8">
        <meta name="author" content="">
        <meta name="keywords" content="">
        <meta name="description" content="">
        <meta http-equiv="" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>View Product</title>

        <link rel="icon" type="image/png" href="">
        <link rel="stylesheet" type="text/css" href="CSS-Files/GeneralSheet.css">
        <link rel="stylesheet" type="text/css" href="CSS-Files/MainFormSheet.css">
        <link rel="stylesheet" type="text/css" href="CSS-Files/nav.css">
        <link rel="stylesheet" href="CSS-Files/ViewProduct.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"/>
        <script defer type="text/javascript" src=""></script>
</head>
<body>
    <header id="MainFormHeader">
        <div id="MainFormHeaderTopPart">
            <div style="display: flex; align-items: center;">
                <img id="MainFormHeaderLogo" src="Source-Files/Logo3.png" alt="">
                <p>ARDEUR DE FRANCE</p>
            </div>
            <div style="display: flex; align-items: center;">
                <div id="MainFormHeaderSearchBar">
                    <i id="MainFormHeaderSearchButton" class="fa-solid fa-magnifying-glass"></i>
                    <input id="MainFormHeaderSearchInput" type="text" placeholder="Seach">
                    <i id="MainFormHeaderDeleteTextButton" class="fa-solid fa-circle-xmark"></i>
                </div>
                <i id="MainFormHeaderMenuButton" class="menu-button fa-solid fa-bars"></i>
                <div class="navbar" id="navbar">                        
                    <ul>
                      <li><a href="MainForm.php"><i class="fa-solid fa-house-user"></i> Home </a></li>
                      <li><a href="#"><i class="fa-solid fa-user"></i> Signup </a></li>
                      <li><a href="#"><i class="fa-solid fa-desktop"></i> Login </a></li>
                      <!-- <li><a href="#"><i class="fa-solid fa-right-from-bracket"></i> Logout</a></li> -->
                      <!-- <li><a href="#"><i class="fa-solid fa-cart-shopping"></i> Cart </a></li> -->
                      <!-- <li><a href="#"><i class="fa-solid fa-desktop"></i> View Orders </a></li> -->
                    </ul>
                </div>
            </div>
        </div>
        <div id="MainHeaderButtons">
            <button id="BrandsButton">BRANDS</button>
            <button id="WomansPerfumeButton">WOMAN'S PERFUME</button>
            <button id="MensCologneButton">MEN'S COLOGNE</button>
            <button id="BestSellerButton">BEST SELLER</button>
            <button id="GiftSetsButton">GIFTSETS</button>
        </div>
    </header>   
    <main id="MainFormMain">
        <div class="content-container">
            <div class="product-container">
                <div class="img-side"></div>
                <div class="info-side"></div>

            </div>
            <div class="reviews-container">
                <div class="left-side"></div>
                <div class="right-side"></div>
            </div>
            <div class="comments-container">

            </div>

        </div>
    </main>
    
    <footer></footer>
    <script defer type="text/javascript" src="JS-Scripts/nav.js"></script>

</body>
<script defer type="text/javascript">
    const MainFormHeaderSearchInput = document.querySelector('#MainFormHeaderSearchInput');
    const MainFormHeaderDeleteTextButton = document.querySelector('#MainFormHeaderDeleteTextButton');
    const MainFormHeaderSearchButton = document.querySelector('#MainFormHeaderSearchButton');
    MainFormHeaderSearchButton.addEventListener('click', function() {
        if (MainFormHeaderSearchInput.style.display === 'flex') {
            MainFormHeaderSearchInput.style.display = '';
            MainFormHeaderDeleteTextButton.style.display = '';
        } else {
            MainFormHeaderSearchInput.style.display = 'flex';
            MainFormHeaderDeleteTextButton.style.display = 'flex';
        }
    });

    const CheckBrandsButton = document.querySelector('#CheckBrandsButton');
    CheckBrandsButton.addEventListener('click', function() {
        window.location.href = `WebForm.html`;
    });

    
</script>
</html>