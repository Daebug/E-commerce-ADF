    <?php
    session_start();

    if (isset($_SESSION["userid"])) {
        header("Location: MainForm.php");
        exit();
    }
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
        <title>ArdeurDeFrance</title>

        <link rel="icon" type="image/png" href="">
        <link rel="stylesheet" type="text/css" href="CSS-Files/GeneralSheet.css">
        <link rel="stylesheet" type="text/css" href="CSS-Files/LogInSignUpSheet.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"/>
        <script defer type="text/javascript" src=""></script>
    </head>
    <body>
        <div id="LogInDisplayContainer">
            <div id="LogInLeft">
                <img src="Source-Files/Logo1.png" alt="">
                <p>luxury that owns quality</p>
            </div>
            <div id="LogInRight">
                <div id="LogInFormContainer">
                    <p style="font-size: 2.5rem; margin-bottom: 2rem;">LOGIN</p>
                    <?php
                    if(isset($_GET['error']) && $_GET['error'] == "invalid_credentials"){
                        ?>
                        <div class="container-fluid d-flex align-items-center justify-content-center" style="color: red; font-weight: bold;">
                            Either the Credentials are WRONG
                        </div>
                        <div class="container-fluid d-flex align-items-center justify-content-center" style="color: red; font-weight: bold;">
                            OR
                        </div>
                        <div class="container-fluid d-flex align-items-center justify-content-center" style="color: red; font-weight: bold;">
                            Account not Verified! Check your Email.
                        </div>
                        <?php
                    }
                    ?>
                    <form action="../process/login.php" method="post">
                        <div style="display: flex; flex-direction: column; width: 100%;">
                            <input id="UsernameInput" type="text" name="username" placeholder="Username">
                            <input id="PasswordInput" type="password" name="password" placeholder="Password">
                        </div>
                        <div style="display: flex; align-items: center; width: 100%;">
                            <input id="RememberMeCheckbox" type="checkbox">
                            <label id="RememberMeLabel">Remember Me</label>
                        </div>
                        <button type="submit" id="LogInButton">LOGIN</button>
                        <a id="ForgotPassword" href="">Forgot Password</a>
                    </form>
                </div>
                <div id="RegisterLabel"><p>Need an account?</p><a id="RegisterUIButton">Register</a></div>
                <div id="UserAgreement">
                    <a style="margin-right: 1rem;" href="">PRIVACY POLICY</a>
                    <a href="">TERMS AND CONDITIONS</a>
                </div>
            </div>
        </div>
        <div id="RegisterDisplayContainer" style="display: none;">
            <div id="RegisterFormContainer">
                <div id="RegisterFormContainerLeft">
                    <img src="Source-Files/Logo2.png" alt="">
                    <p>People who use our service may have uploaded your
                        contact information to Ardeur de France. <a style="font-weight: bold;" href="">Learn More.</a></p>
                    <p style="font-size: 1.5rem;">Have an account? 
                        <a style="font-size: 1.5rem;" id="LogInUIButton">LogIn.</a></p>
                </div>
                <div id="RegisterFormContainerRight">
                    <form id="RegisterForm" action="../process/register.php" method="post">
                        <input type="text" name="full_name" placeholder="Full Name">
                        <input type="text" name="email" placeholder="Email">
                        <input type="text" name="contact_number" placeholder="Contact Number">
                        <input type="password" name="password" placeholder="Password">
                        <p>By signing up, you agree to our <a href="">Terms</a>, <a href="">Privacy Policy</a> and
                            <a href="">Cookies Policy</a>.</p>
                        <button type="submit" id="SignUpButton">SIGN UP</button>
                    </form>
                </div>
            </div>
        </div>
    <script defer type="text/javascript">
        const LogInButton = document.querySelector('#LogInButton');
        const SignUpButton = document.querySelector('#SignUpButton');
        const RegisterUIButton = document.querySelector('#RegisterUIButton');
        const LogInUIButton = document.querySelector('#LogInUIButton');
        const RegisterDisplayContainer = document.querySelector('#RegisterDisplayContainer');
        const LogInDisplayContainer = document.querySelector('#LogInDisplayContainer');

        LogInButton.addEventListener('click', function() {
            window.location.href = `MainForm.php`;
        });
        SignUpButton.addEventListener('click', function() {
            window.location.href = `MainForm.php`;
        });

        RegisterUIButton.addEventListener('click', function() {
            RegisterDisplayContainer.style.display = 'flex';
            LogInDisplayContainer.style.display = 'none';
        });
        LogInUIButton.addEventListener('click', function() {
            RegisterDisplayContainer.style.display = 'none';
            LogInDisplayContainer.style.display = 'flex';
        });
    </script>
    </body>
    </html>