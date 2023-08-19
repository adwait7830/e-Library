<?php

require_once('env.php') ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Server Down</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            text-align: center;
            padding: 100px;
        }

        .container {
            background-color: #ffffff;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            color: #e74c3c;
        }

        p {
            color: #333333;
            font-size: 18px;
        }

        .support-info {
            margin-top: 30px;
            font-size: 16px;
            color: #555555;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Server Down for Maintenance</h1>
        <p>We apologize for the inconvenience, but our server is currently undergoing maintenance to ensure better performance and reliability.</p>
        <p>Please check back later. Click Here to <a href="#" onclick="reloadSite()">Reload</a></p>
        <div class="support-info">
            If you have any urgent inquiries or need assistance, feel free to contact our support team at <a href="<?php echo $myMail ?>">support</a>.
        </div>
    </div>
</body>
<script>
    function reloadSite(){
        window.location.replace('index.php');
    }
</script>
</html>
