<?php
$id = $_SERVER['QUERY_STRING'];
if (empty($id)) {
    header('Location: index.php');
}
require_once('db.php');
$stmt = $conn->prepare('SELECT name, password FROM users WHERE uid = ? ');
$stmt->bind_param('s', $id);
$stmt->execute();
$stmt->bind_result($name, $storedPass);
if (!($stmt->fetch())) {
    header('Location: index.php');
}

$response = array();
if (isset($_POST['changePass'])) {

    if ($storedPass !== $_POST['pass']) {
        $stmt->close();
        $newPass = password_hash($_POST['pass'], PASSWORD_BCRYPT);
        $stmt = $conn->prepare("UPDATE users SET pass = ? WHERE uid = ?");
        $stmt->bind_param('ss', $newPass, $id);
        $stmt->execute();
        $response = array('response' => 'changed');
    } else {
        $response = array('response' => 'pass');
    }
    $jsonData = json_encode($response);
    header('Content-Type: application/json');
    echo $jsonData;
}


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="images/title.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>e Library</title>
</head>

<body>



    <div id="bg" class="d-flex flex-column align-items-center p-3 rounded-3">
        <div>
            <i class="fas fa-user" style='font-size:5rem'></i>
        </div>
        <div class='mt-2'>
            <p><?php echo $name ?></p>
        </div>
        <form class="d-flex flex-column align-items-center gap-3 w-100" id='form'>
            <input type="hidden" name='changePass'>
            <div class='form-floating w-100'>
                <input class="form-control h-50 w-100" type="password" name="password" id='pass' placeholder="" required=''>
                <label for="pass">Password</label>
                <span id="passwordHelpBlock" class="form-text ms-0 text-danger"></span>
            </div>
            <div class='form-floating w-100 '>
                <input class="form-control h-50 w-100 is-invalid" type="password" name="cPassword" id='cPass' placeholder='' required=''>
                <label for="cPass">Confirm Password</label>
                <span id="cPasswordHelpBlock" class="form-text ms-0 text-danger"></span>
            </div>
            <button class='btn btn-outline-success'>Submit</button>
            <p style="font-size: small; ">Jump to <a style="text-decoration:none" href="http://localhost:8080/website/e%20lib/un-logged.php">e Library</a></p>
        </form>
    </div>
    <style>
        #bg {
            background-color: white;
            width: 325px;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            box-shadow: 5px 5px 5px rgba(0, 0, 0, 0.5);

        }

        body {
            background-color: whitesmoke;
        }
    </style>
</body>

<script>
    document.getElementById('form').addEventListener('submit', function(event) {
        event.preventDefault();
        if (!document.getElementById('form').checkValidity()) {
            event.stopPropagation()
            console.log("invalid");
        } else {
            var formData = new FormData(event.target);
            fetch('forgetPass.php', {
                    body: formData,
                    method: "POST"
                })
                .then(res => res.json())
                .then(server => console.log(server.response))
                .catch(err => console.error(err))
        }
    })
    document.getElementById('pass').addEventListener('input', function(event) {
        var pass = event.target.value;
        const helpBlockText = document.getElementById('passwordHelpBlock');
        helpBlockText.textContent = ''
        if (pass === '') {
            helpBlockText.textContent = 'Password field can not be empty'
        } else if (/^.{0,7}$|^.{21,}$/.test(pass)) {
            helpBlockText.textContent = 'Length should be between 8-20';
        } else if (/^[a-zA-Z\d]*$/.test(pass)) {
            helpBlockText.textContent = 'There should be at least one special character';
        } else if (/^[^A-Z]*$/.test(pass)) {
            helpBlockText.textContent = 'At least one capital letter';
        } else if (/^[^\d]*$/.test(pass)) {
            helpBlockText.textContent = 'There should be at least one number';
        }
    })
    document.getElementById('cPass').addEventListener('input', function(event) {
        var cPass = event.target.value;
        var pass = document.getElementById('pass').value;
        const helpBlockText = document.getElementById('cPasswordHelpBlock');
        helpBlockText.textContent = ''
        if (!(pass === cPass && pass !== '')) {
            helpBlockText.textContent = 'Password does not match'
        } else {



        }
    })
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://kit.fontawesome.com/8256093c76.js" crossorigin="anonymous"></script>

</html>