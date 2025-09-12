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
    <title>Messenger Chat UI</title>
    <link rel="stylesheet" href="CSS-Files/chat.css">
    <link rel="icon" type="image/png" href="">
    <link rel="stylesheet" type="text/css" href="CSS-Files/GeneralSheet.css">
    <link rel="stylesheet" type="text/css" href="CSS-Files/MainFormSheet.css">
    <link rel="stylesheet" type="text/css" href="CSS-Files/nav.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script defer type="text/css" type="text/javascript" src=""></script>
    <style>
        /* Chat container */
        .chat-container {
            display: flex;
            height: 100vh;
            background-color: #f5f5f5;
            /* Light gray background */
        }

        /* Chat content container */
        .chat-content-container {
            margin-top: 60px;
            display: flex;
            width: 100%;
        }

        /* Sidebar */
        .sidebar {
            flex: 1;
            padding: 20px;
            background-color: #f2f2f2;
            /* Light gray background color */
            overflow-y: auto;
            /* Enable scrollbar if content overflows */
        }

        .user-list {
            padding: 10px;
            /* Add padding to the user list */

        }

        .user {
            padding: 8px 12px;
            /* Adjust padding */
            margin-bottom: 8px;
            /* Adjust margin */
            background-color: var(--ColorYellow);
            /* White background */
            cursor: pointer;
            border-radius: 5px;
            transition: background-color 0.3s ease;
            font-size: 20px;
        }

        .user:hover {
            background-color: #e0e0e0;
            /* Light gray background color on hover */
        }

        /* Chat */
        .chat {
            flex: 3;
            display: flex;
            flex-direction: column;
            border-left: 1px solid #ccc;
            /* Gray border on the left */
        }

        /* Chat header */
        .chat-header {
            padding: 20px;
            border-bottom: 1px solid #ccc;
            /* Gray border bottom */
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        #selected-username.username {
            font-size: 30px;
        }

        .username {
            font-weight: bold;
            font-size: 18px;
            /* Adjust font size */
        }

        .header-right button {
            padding: 13px 20px;
            /* Adjust button padding */
            background-color: #007bff;
            /* Blue color */
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .header-right button:hover {
            background-color: #0056b3;
            /* Darker blue color on hover */
        }

        /* Chat messages */
        .chat-messages {
            display: flex;
            flex-direction: column;
            flex: 1;
            padding: 20px;
            overflow-y: auto;
            /* Enable scrollbar if content overflows */
        }

        .message {
            padding: 8px 12px;
            /* Adjust padding */
            margin-bottom: 10px;
            border-radius: 5px;
            color: #fff;
            font-size: 16px;
            /* Adjust font size */
        }

        .message.received {
            background-color: #007bff;
            align-self: flex-start;
            /* Align received messages to the right */
            width: fit-content;
        }

        .message.sent {
            width: fit-content;
            background-color: #c5ae00;
            align-self: flex-end;
            /* Align sent messages to the left */

        }

        /* Input container */
        .input-container {
            padding: 20px;
            display: flex;
            align-items: center;
            border-top: 1px solid #ccc;
            /* Gray border top */
            width: 100%;
        }

        #message-form {
            display: flex;
            width: 100%;
        }

        #message-input {
            flex: 1;
            padding: 10px;
            margin-right: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
            /* Adjust font size */
        }

        #message-input:focus {
            outline: none;
        }

        #message-form button[type="submit"] {
            padding: 8px 16px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        #message-form button[type="submit"]:hover {
            background-color: #0056b3;
        }
    </style>
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
                        <li><a href="ViewCart.php"><i class="fa-solid fa-cart-shopping"></i> Cart </a></li>
                        <li><a href="ViewOrder.php"><i class="fa-solid fa-box"></i> View Orders </a></li>
                        <li><a href="Chat.php"><i class="fa-solid fa-message"></i> Chat </a></li>
                        <li><a href="../process/logout.php"><i class="fa-solid fa-right-from-bracket"></i> Logout</a></li>
                    </ul>
                </div>
            </div>
        </div>

    </header>

    <div class="chat-container">
        <div class="chat-content-container">
            <div class="sidebar">
                <div id="user-list" class="user-list">
                    <?php
                    include '../connection/connection.php';

                    if ($userid != '101') {
                        echo '<div class="user" onclick="updateUsername(\'Admin\')">Admin</div>';
                    } else {
                        $query = "SELECT `userid`, `username` FROM `tbluser` WHERE `userid` <> $userid";
                        $result = mysqli_query($conn, $query);
                        if ($result) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                echo '<div class="user" onclick="updateUsername(\'' . $row['username'] . '\', \'' . $row['userid'] . '\')">' . $row['username'] . '</div>';
                            }
                        }
                    }
                    ?>
                </div>
            </div>
            <div class="chat">
                <div class="chat-header">
                    <div class="header-left">
                        <span id="selected-username" class="username"></span>
                    </div>
                    <div class="header-right">
                        <!-- <button class="btn">Info</button> -->
                    </div>
                </div>
                <div class="chat-messages" id="chat-messages">
                </div>
                <div class="input-container">
                    <div id="selected-username"></div>
                    <form id="message-form">
                        <input type="hidden" id="receiver-id" name="receiver_id" value="">
                        <input type="text" id="message-input" name="message" placeholder="Type a message...">
                        <button type="submit">Send</button>
                    </form>
                </div>
            </div>
        </div>

    </div>
    <footer id="MainFormFooter">
        <div id="UpperFooter">
            <div id="UpperFooterLabelContainer1">
                <p id="FooterLabel1">ARDEUR DE FRANCE</p>
                <p id="FooterLabel2">Luxury that owns quality</p>
            </div>
            <div id="UpperFooterLabelContainer2">
                <p id="FooterLabel3">FOLLOW US</p>
                <div>
                    <i class="fa-brands fa-facebook-f"></i>
                    <i class="fa-brands fa-twitter"></i>
                    <i class="fa-brands fa-instagram"></i>
                </div>
            </div>
        </div>
        <div id="BottomFooter">
            <p>2024 ARDEUR DE FRANCE. Fragrance retailer, zamboanga city. All Rights Reserved. </p>
            <div>
                <a href="">PRIVACY POLICY</a>
                <a href="">TERMS AND CONDITIONS</a>
            </div>
        </div>
    </footer>
    <script defer type="text/javascript" src="JS-Scripts/nav.js"></script>
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
            window.location.href = `WebForm.php`;
        });

        document.addEventListener('DOMContentLoaded', function() {
            const ProductContainerOverlays = document.querySelectorAll('.ProductContainerOverlay');
            ProductContainerOverlays.forEach(overlay => {
                overlay.addEventListener('click', function() {
                    // Get the productid from the hidden p element
                    const productId = this.parentNode.querySelector('.ProductId').textContent;
                    if (productId) {
                        window.location.href = `ProductView.php?productid=${productId}`;
                    }
                });
            });
        });
    </script>
</body>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        startPolling();
    });

    let lastMessageId = null; // Track the last message ID to fetch only new messages

    function updateUsername(name, userid) {
        document.querySelector('.header-left .username').innerText = name;
        let sessionUserId = <?php echo $_SESSION['userid']; ?>;
        let fetchUserId = sessionUserId != '101' ? '101' : userid;
        document.getElementById('receiver-id').value = fetchUserId;
        fetchMessages(fetchUserId); // Fetch messages for the selected user
    }

    function fetchMessages(userid) {
        fetch(`../process/fetchMessage.php?userid=${userid}&lastMessageId=${lastMessageId}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                const chatMessages = document.getElementById('chat-messages');
                if (data.length > 0) {
                    data.forEach(message => {
                        const div = document.createElement('div');
                        div.classList.add('message');
                        div.innerText = message.message;
                        if (message.isSender) {
                            div.classList.add('sent');
                            div.style.textAlign = 'right'; // Align sent messages to the right
                        } else {
                            div.classList.add('received');
                            div.style.textAlign = 'left'; // Align received messages to the left
                        }
                        chatMessages.appendChild(div);
                        lastMessageId = message.id; // Update the lastMessageId to the latest message
                    });
                    chatMessages.scrollTop = chatMessages.scrollHeight; // Auto-scroll to the latest message
                }
            })
            .catch(error => {
                console.error('Error fetching messages:', error);
            });
    }

    document.getElementById('message-form').addEventListener('submit', function(event) {
        event.preventDefault(); // Prevent default form submission
        const messageInput = document.getElementById('message-input');
        const message = messageInput.value.trim();
        if (message !== '') {
            sendMessage(message);
            messageInput.value = ''; // Clear the message input field
        }
    });

    function sendMessage(message) {
        const receiverId = document.getElementById('receiver-id').value;
        fetch('../process/sendMessage.php', {
                method: 'POST',
                body: new URLSearchParams({
                    receiver_id: receiverId,
                    message: message
                })
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Failed to send message');
                }
                return response.text();
            })
            .then(data => {
                console.log(data); // Log the response from the server
                fetchMessages(receiverId); // Fetch messages again after sending
            })
            .catch(error => {
                console.error('Error sending message:', error);
                // Optionally, you can display an error message to the user
            });
    }

    function startPolling() {
        setInterval(() => {
            const receiverId = document.getElementById('receiver-id').value;
            if (receiverId) {
                fetchMessages(receiverId); // Fetch new messages every few seconds
            }
        }, 100); // Poll every 1 second
    }
</script>

</html>