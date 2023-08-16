<?php
session_start();
$serverName = "localhost";
$port = 3307;
$userName = "root";
$password = "";
$database = "e_lib";
$init = new mysqli($serverName, $userName, $password, $database, $port);
$conn = mysqli_connect($serverName, $userName, $password, $database, $port);

$sql = '
    CREATE TABLE IF NOT EXISTS all_books(
        id INT(11) PRIMARY KEY AUTO_INCREMENT,
        cover VARCHAR(50),
        title VARCHAR(50),
        author VARCHAR(50),
        description TEXT,
        views INT DEFAULT 0 
    );
    CREATE TABLE IF NOT EXISTS users(
        uid VARCHAR(20) PRIMARY KEY,
        username VARCHAR(20),
        name VARCHAR(30),
        email VARCHAR(30),
        PROFESSION VARCHAR(20),
        password VARCHAR(60),
        verified TINYINT(2) DEFAULT 0
    );
    CREATE TABLE IF NOT EXISTS sessions(
        token_id INT(11) PRIMARY KEY AUTO_INCREMENT,
        uid VARCHAR(20),
        token VARCHAR(20),
        expiration_time TIMESTAMP DEFAULT current_timestamp()
    );
    CREATE TABLE IF NOT EXISTS feedback(
        id INT(11) PRIMARY KEY AUTO_INCREMENT,
        name VARCHAR(30),
        email VARCHAR(30),
        response TEXT,
        date DATE DEFAULT current_timestamp()
    );
';
$init->multi_query($sql);
$init->close();
function setSessionToken($id)
{
    global $conn;

    $token = '';
    for ($i = 0; $i < 10; $i++) {
        $token = generateSessionToken(8);
        $stmt = $conn->prepare("SELECT 1 FROM sessions WHERE token = ?");
        $stmt->bind_param('s', $token);
        if ($stmt->fetch()) {
            $stmt->close();
            continue;
        } else {
            $stmt->close();
            break;
        }
    }
    $expiration_time = date('Y-m-d H:i:s', time() + 172800);

    $stmt = $conn->prepare("INSERT INTO sessions(uid,token,expiration_time) VALUES(?,?,?)");
    $stmt->bind_param('sss', $id, $token, $expiration_time);
    $stmt->execute();
    $_SESSION['token'] = $token;
}

function dltSessionToken()
{
    global $conn;  // Assuming $conn is a valid database connection

    if (isset($_SESSION['token'])) {
        $token = $_SESSION['token'];
        $stmt = $conn->prepare("DELETE FROM sessions WHERE token = ?");
        $stmt->bind_param('s', $token);
        $stmt->execute();
        $stmt->close();  // Close the statement

        // Clear the session token
        unset($_SESSION['token']);
    }
}




function verifySessionToken(): bool
{
    global $conn;  // Assuming $conn is a valid database connection

    if (isset($_SESSION['token'])) {
        $expiration_time = '';
        $stmt = $conn->prepare("SELECT expiration_time FROM sessions WHERE token = ?");
        $stmt->bind_param('s', $_SESSION['token']);
        $stmt->execute();
        $stmt->bind_result($expiration_time);

        if ($stmt->fetch()) {
            $stmt->close();  // Close the statement to free resources

            if (time() > strtotime($expiration_time)) {
                // Token has expired
                return false;
            } else {
                // Token is valid
                return true;
            }
        } else {
            $stmt->close();  // Close the statement even if fetch fails
            return false;
        }
    } else {
        return false;
    }
}


function getUserID(): string
{
    global $conn;
    $uid = 'not set';
    $stmt = $conn->prepare("SELECT uid FROM sessions WHERE token = ?");
    $stmt->bind_param('s', $_SESSION['token']);
    $stmt->execute();
    $stmt->bind_result($uid);
    $stmt->fetch();
    return $uid;
}


function generateSessionToken($length = 4): string
{
    $token = bin2hex(random_bytes($length));
    return $token;
}
