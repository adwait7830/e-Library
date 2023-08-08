<?php
include('db.php');
include('sendMail.php');



if ($_SERVER["REQUEST_METHOD"] === "POST") {

    if (isset($_POST['response'])) {

        $stmt = $conn->prepare('INSERT INTO feedback(name,email,response) VALUES(?,?,?)');
        $stmt->bind_param('sss', $_POST['name'], $_POST['email'], $_POST['response']);
        $stmt->execute();
        $stmt->close();
    }

    if (isset($_POST['signIn'])) {

        $username = $_POST['username'];
        $password = $_POST['password'];
        $stmt = $conn->prepare("SELECT password, uid FROM users WHERE username = ?");
        $stmt->bind_param('s', $username);

        $response = array();
        try {
            $stmt->execute();
            $stmt->bind_result($storedPassword, $token);

            if ($stmt->fetch()) {
                if (password_verify($password,$storedPassword)) {
                    session_start();
                    $_SESSION['token'] = $token;
                    $response = array('response' => 'valid');
                } else {
                    $response = array('response' => 'password');
                }
            } else {
                $response = array('response' => 'username');
            }
        } catch (Exception $e) {
            $response = array('response' => 'error');
        }
        $stmt->close();
        $jsonData = json_encode($response);
        header('Content-Type: application/json');
        echo $jsonData;
    }

    if (isset($_POST['signUp'])) {

        $uid = '';
        while (true) {
            $uid = generateSessionToken();
            $stmt = $conn->prepare('SELECT * FROM users WHERE uid = ?');
            $stmt->bind_param('s', $uid);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows === 0) {
                $stmt->close();
                break;
            }
        }

        $name = $_POST['setName'];
        $username = $_POST['setUsername'];
        $email = $_POST['setEmail'];
        $profession = $_POST['setProfession'];
        $password = password_hash($_POST['setPassword'],PASSWORD_BCRYPT);
        $response = array();

        $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows === 0){
            $stmt->close();
            $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
            $stmt->bind_param('s', $email);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows === 0) {
                $stmt->close();
                
                $stmt = $conn->prepare("INSERT INTO users(uid,name,username,email, profession, password) VALUES(?,?,?,?,?,?)");
                $stmt->bind_param('ssssss', $uid, $name, $username, $email, $profession, $password);
                try {
                    $result = $stmt->execute();
                    smtp_mailer($email,$name,$uid);
                    $response = array('response' => 'registered');
                } catch (Exception $e) {
                    $response = array('response'=>'error');
                }
            } else {
                $response = array('response'=>'email');
            }
        } else {
            $response = array('response'=>'username');
        }

        $jsonData = json_encode($response);
        header('Content-Type: application/json');
        echo $jsonData;

    }
}
