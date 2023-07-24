<?php
$serverName = "localhost";
$port = 3307;
$userName = "root";
$password = "";
$database = "e_lib";
$conn = mysqli_connect($serverName, $userName, $password, $database, $port);

function generateSessionToken($length = 4)
{
    $token = bin2hex(random_bytes($length)); // Using PHP's random_bytes() for secure random data
    return $token;
}
