<?php
require_once('db.php');

if ($_SERVER["REQUEST_METHOD"] === "GET") {


    if ($_GET['type'] === 'isAdmin') {

        $uid = getUserID();
        $stmt = $conn->prepare('SELECT admin FROM users WHERE uid = ?');
        $stmt->bind_param('s', $uid);
        $stmt->execute();
        $stmt->bind_result($admin);
        $stmt->fetch();
        $isAdmin = $admin === 1;
        $data = array('isAdmin' => $isAdmin);
        $jsonData = json_encode($data);
        header("Content-Type: application/json");
        echo $jsonData;
    }

    if ($_GET['type'] === 'stat') {

    $professionData = array();
    $onboardData = array();

    // Fetch profession data
    $stmtProfession = $conn->prepare('SELECT profession, COUNT(*) AS count FROM users GROUP BY profession');
    $stmtProfession->execute();
    $stmtProfession->bind_result($profession, $count);
    while ($stmtProfession->fetch()) {
        $professionData[] = array('profession' => $profession, 'count' => $count);
    }
    $stmtProfession->close();

    // Fetch onboard data
    $stmtOnboard = $conn->prepare('SELECT onboard, COUNT(*) AS user_count FROM users GROUP BY onboard');
    $stmtOnboard->execute();
    $stmtOnboard->bind_result($onboard, $user_count);
    while ($stmtOnboard->fetch()) {
        $onboardData[] = array('onboard' => $onboard, 'count' => $user_count);
    }
    $stmtOnboard->close();

    // Combine both sets of data into a final array
    $finalData = array('professions' => $professionData, 'onboard' => $onboardData);

    $jsonData = json_encode($finalData);
    header("Content-Type: application/json");

    echo $jsonData;
}


    if(isset($_GET['remove'])){
        $uid = $_GET['remove'];

        $stmt = $conn->prepare('DELETE FROM users WHERE uid = ?');
        $stmt->bind_param('s',$uid);
        $stmt->execute();

    }
    if(isset($_GET['dlt'])){
        $id = $_GET['dlt'];

        $stmt = $conn->prepare('DELETE FROM feedback WHERE id = ?');
        $stmt->bind_param('i',$id);
        $stmt->execute();
        
    }

}
