<?php
require_once('db.php');
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
        $stmt = $conn->prepare("SELECT verified, password, uid FROM users WHERE username = ?");
        $stmt->bind_param('s', $username);

        $response = array();
        $stmt->execute();
        $stmt->bind_result($verified, $storedPassword, $uid);

        if ($stmt->fetch()) {
            $stmt->close();
            if (password_verify($password, $storedPassword)) {
                if ($verified === 1) {
                    setSessionToken(id: $uid);
                    $response = array('response' => 'valid');
                } else {
                    $response = array('response' => 'unverified');
                }
            } else {
                $response = array('response' => 'password');
            }
        } else {
            $response = array('response' => 'username');
        }

        $jsonData = json_encode($response);
        header('Content-Type: application/json');
        echo $jsonData;
    }

    if (isset($_POST['signUp'])) {

        $uid = '';
        for ($i = 0; $i < 10; $i++) {
            $uid = generateSessionToken();
            $stmt = $conn->prepare("SELECT 1 FROM users WHERE uid = ?");
            $stmt->bind_param('s', $uid);
            if ($stmt->fetch()) {
                continue;
            } else {
                break;
            }
        }
        $stmt->close();

        $name = $_POST['setName'];
        $username = $_POST['setUsername'];
        $email = $_POST['setEmail'];
        $profession = $_POST['setProfession'];
        $password = password_hash($_POST['setPassword'], PASSWORD_BCRYPT);
        $response = array();

        $stmt = $conn->prepare("SELECT 1 FROM users WHERE username = ?");
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows === 0) {
            $stmt->close();
            $stmt = $conn->prepare("SELECT 1 FROM users WHERE email = ?");
            $stmt->bind_param('s', $email);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows === 0) {
                $stmt->close();

                $stmt = $conn->prepare("INSERT INTO users(uid,name,username,email, profession, password) VALUES(?,?,?,?,?,?)");
                $stmt->bind_param('ssssss', $uid, $name, $username, $email, $profession, $password);
                try {
                    $result = $stmt->execute();
                    smtp_mailer($email, $name, $uid, 1);
                    $response = array('response' => 'registered');
                } catch (Exception $e) {
                    $response = array('response' => 'error');
                }
            } else {
                $response = array('response' => 'email');
            }
        } else {
            $response = array('response' => 'username');
        }

        $jsonData = json_encode($response);
        header('Content-Type: application/json');
        echo $jsonData;
    }

    if (isset($_POST['forgetPass'])) {
        $mail = $_POST['sendLinkMail'];

        $stmt = $conn->prepare('SELECT uid,name FROM users WHERE email = ?');
        $stmt->bind_param('s', $mail);
        $stmt->execute();
        $stmt->bind_result($id, $name);
        $response = array();
        if ($stmt->fetch()) {
            smtp_mailer($mail, $name, $id, 2);
            $response = array('response' => 'sent');
        } else {
            $response = array('response' => 'email');
        }

        $jsonData = json_encode($response);
        header('Content-Type: application/json');
        echo $jsonData;
    }

    if (isset($_POST['changePass'])) {
        $uid = $_POST['uid'];
        $password = $_POST['password'];
        $stmt = $conn->prepare("SELECT password FROM users WHERE uid = ?");
        $stmt->bind_param('s', $uid);
        $stmt->execute();
        $stmt->bind_result($hash);
        $stmt->fetch();
        $stmt->close();
        $response = array();
        if (password_verify($password, $hash)) {
            $response = array('response' => 'pass');
        } else {
            $newHash = password_hash($password, PASSWORD_BCRYPT);
            $stmt = $conn->prepare("UPDATE users SET password = ? WHERE uid = ?");
            $stmt->bind_param('ss', $newHash, $uid);
            $stmt->execute();
            $response = array('response' => 'changed');
        }
        $jsonData = json_encode($response);
        header('Content-Type: application/json');
        echo $jsonData;
    }
}else{
    header('Location: index.php');
    exit;
}
