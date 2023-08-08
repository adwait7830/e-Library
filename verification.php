<?php
require_once('db.php');

$id = $_SERVER['QUERY_STRING'];

if (!empty($id)) {
    $stmt = $conn->prepare('SELECT verified FROM users WHERE uid = ?');
    $stmt->bind_param('s', $id);
    $stmt->execute();
    $stmt->bind_result($verified);
    if ($stmt->fetch()) {
        if ($verified === 0) {
            $stmt->close();
            $stmt = $conn->prepare('UPDATE users SET verified = 1 WHERE uid = ?');
            $stmt->bind_param('s', $id);
            $stmt->execute();
            session_start();
            $_SESSION['token'] = $id;
            header('Location: index.php');
        }else{
            session_start();
            $_SESSION['token'] = $id;
            header('Location: index.php');
        }
        die();
    } else {
        header('Location: index.php');
        die();
    }
} else {
    header('Location: index.php');
    die();
}
