<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>e Library</title>
    <link rel="stylesheet" href="style.css">
    <link rel="icon" href="images/title.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

    <div class="splash-screen" id="splashScreen">
        <div class="splash-content text-center">
            <p class="display-2" style='font-family:samarkan;'>E Library</p>
        </div>
    </div>
    <style>
        

        .splash-screen {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: #ffffff;
            /* Set the background color you prefer */
            display: flex;
            justify-content: center;
            align-items: center;
            animation: zoomOut 2s ease-in-out forwards;
            /* Add the zoom-out animation */
        }

        .loaded .splash-screen {
            display: none;
        }

        @keyframes zoomOut {
            0% {
                transform: scale(1);
                opacity: 1;
            }

            100% {
                transform: scale(1.5);
                opacity: 0;
            }
        }
    </style>
    <script>
        window.addEventListener("load", function() {

            setTimeout(function() {
                const splashScreen = document.getElementById("splashScreen");
                splashScreen.style.display = "none";

                <?php
                if (isset($_SESSION['uid'])) {
                    echo 'window.location.replace("logged.php");';
                } else {
                    
                    echo 'window.location.replace("un-logged.php");';
                }
                ?>
            }, 2000);
        });
    </script>
    <script src="js/jquery.js" type="text/javascript"></script>
    <script src="js/script.js" type="text/javascript"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://kit.fontawesome.com/8256093c76.js" crossorigin="anonymous"></script>
</body>

</html>