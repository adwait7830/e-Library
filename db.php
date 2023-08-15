<?php
session_start();
$serverName = "localhost";
$port = 3307;
$userName = "root";
$password = "";
$database = "e_lib";
$conn = mysqli_connect($serverName, $userName, $password, $database, $port);


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

function getCollection(): array
{
    global $conn;
    $uid = getUserID();

    $stmt = $conn->prepare("SELECT collection FROM users WHERE uid = ?");
    $stmt->bind_param('s', $uid);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
        $listValue = $row['collection'];

        if ($listValue === null) {
            return []; 
        }

        return json_decode($listValue, true);
    }

    return [];
}

function addToCollection($id):void
{
    global $conn; 
    $uid = getUserID();
    
    $collection = getCollection();
    $collection[] = $id;
    
    $updatedCollJSON = json_encode($collection);
    
    $stmt = $conn->prepare("UPDATE users SET collection = ? WHERE uid = ?");
    $stmt->bind_param('ss', $updatedCollJSON, $uid);
    $stmt->execute();
}


function inCollection($id): bool
{
    $collection = getCollection();
    return in_array($id, $collection);
}


function dltFromCollection($id): void
{
    global $conn; 
    $uid = getUserID();
    $collection = getCollection();

    $newColl = array_diff($collection, [$id]);
    $updatedCollJSON = json_encode($newColl);

    $stmt = $conn->prepare("UPDATE users SET collection = ? WHERE uid = ?");
    $stmt->bind_param('ss', $updatedCollJSON, $uid);
    $stmt->execute();
}
