<?php
require_once('db.php');

if ($_SERVER["REQUEST_METHOD"] === "GET") {


    
    if ($_GET['type'] === 'stat') {

        $data = array();

        $stmt = $conn->prepare('SELECT profession, COUNT(*) AS count FROM users GROUP BY profession');
        $stmt->execute();
        $stmt->bind_result($profession, $count);
        while ($stmt->fetch()) {

            $data[$profession] = $count;

        }
        $stmt->close();
        $jsonData = json_encode($data);
        header("Content-Type: application/json");

        echo $jsonData;
    }

}
