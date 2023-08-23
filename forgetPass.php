<script>
    <?php
    $encodedId = $_SERVER['QUERY_STRING'];
    if (empty($encodedId)) {
        echo 'window.location.replace("index.php");';
    }
    require_once('db.php');
    $stmt = $conn->prepare('SELECT name FROM users WHERE uid = ? ');
    $id = base64_decode($encodedId);
    $stmt->bind_param('s', $id);
    $stmt->execute();
    $stmt->bind_result($name);
    if (!($stmt->fetch())) {
        echo 'window.location.replace("index.php");';
    }
    ?>
</script>
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
                <label for="pass">New Password</label>
                <span id="passwordHelpBlock" class="form-text ms-0 text-danger"></span>
            </div>
            <div class='form-floating w-100 '>
                <input class="form-control h-50 w-100" type="password" name="cPassword" id='cPass' placeholder='' required=''>
                <label for="cPass">Confirm Password</label>
                <span id="cPasswordHelpBlock" class="form-text ms-0 text-danger"></span>
            </div>
            <button class='btn btn-outline-success'>Submit</button>
            <p style="font-size: small; ">Jump to <a style="text-decoration:none" href="http://localhost:8080/website/e%20lib/un-logged.php">e Library</a></p>
        </form>
        <div class="spinner-border d-none m-5 text-primary" style="width: 3rem; height: 3rem;" id='loader' role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
        <div class="w-100 d-flex flex-column align-items-center justify-content-center d-none" id='changeSuccess'>
            <i class="fa-solid fa-check text-success" style='font-size:5rem'></i>
            <p>Password Reset Successful</p>
            <p>Redirecting to Login Screen</p>
        </div>
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
    document.getElementById('pass').addEventListener('input', function(event) {
        document.getElementById('cPass').dispatchEvent(new Event('input'));
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
        } else {
            document.getElementById('cPass').addEventListener('input', function() {
                var cPass = document.getElementById('cPass').value;
                var pass = document.getElementById('pass').value;
                const helpBlockText = document.getElementById('cPasswordHelpBlock');
                helpBlockText.textContent = ''
                if (pass !== cPass) {
                    helpBlockText.textContent = 'Password does not match'
                }
            })
            document.getElementById('form').addEventListener('submit', function(event) {
                var cPass = document.getElementById('cPass').value;
                var pass = document.getElementById('pass').value;
                if (pass === cPass) {
                    document.getElementById('form').classList.add('d-none');
                    document.getElementById('loader').classList.remove('d-none');
                    var formData = new FormData(event.target);
                    formData.append('uid', atob(window.location.search.split("?")[1]));
                    fetch('services.php', {
                            body: formData,
                            method: "POST"
                        })
                        .then(res => res.json())
                        .then(server => {
                            setTimeout(() => {
                                switch (server.response) {
                                    case 'changed':
                                        document.getElementById('loader').classList.add('d-none');
                                        document.getElementById('changeSuccess').classList.remove('d-none');
                                        setTimeout(() => {
                                            window.location.replace('index.php');
                                        }, 3000);
                                        break;
                                    case 'pass':
                                        document.getElementById('passwordHelpBlock').textContent = 'Password Already in Use';
                                        document.getElementById('form').classList.remove('d-none');
                                        document.getElementById('loader').classList.add('d-none');

                                }
                            }, 3000);
                        })
                        .catch(err => console.error(err))

                }
            })
        }
    })

    document.getElementById('form').addEventListener('submit', function(event) {
        event.preventDefault();
    })
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://kit.fontawesome.com/8256093c76.js" crossorigin="anonymous"></script>

</html>