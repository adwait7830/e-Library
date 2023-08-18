<?php
require_once('db.php');

if ($_SERVER["REQUEST_METHOD"] === "GET") {


    if (isset($_GET['type']) && $_GET['type'] === 'isAdmin') {

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

    if (isset($_GET['type']) && $_GET['type'] === 'stat') {

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


    if (isset($_GET['remove'])) {
        $uid = $_GET['remove'];

        $stmt = $conn->prepare('DELETE FROM users WHERE uid = ?');
        $stmt->bind_param('s', $uid);
        $stmt->execute();
    }
    if (isset($_GET['dlt'])) {
        $id = $_GET['dlt'];

        $stmt = $conn->prepare('DELETE FROM feedback WHERE id = ?');
        $stmt->bind_param('i', $id);
        $stmt->execute();
    }

    if (isset($_GET['id'])) {
        $uid = $_GET['id'];

        $stmt = $conn->prepare('
        SELECT 
            name,
            email,
            profession,
            onboard,
            added,
            deleted,
            edited,
            admin
        FROM users WHERE uid = ?
         ');
        $stmt->bind_param('s', $uid);
        $stmt->execute();
        $stmt->bind_result($name, $email, $profession, $onboard, $added, $deleted, $edited, $admin);
        $stmt->fetch();
        $data = array(
            'name' => $name,
            'email' => $email,
            'profession' => $profession,
            'onboard' => $onboard,
            'added' => $added,
            'deleted' => $deleted,
            'edited' => $edited,
            'admin' => $admin === 1
        );
        $jsonData = json_encode($data);
        header("Content-Type: application/json");
        echo $jsonData;
    }
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    if (isset($_POST['reply'])) {

        $id = $_POST['id'];
        $subject = $_POST['subject'];
        $msg = $_POST['msg'];

        $stmt = $conn->prepare('SELECT name,email FROM feedback WHERE id = ?');
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $stmt->bind_result($name, $email);
        $stmt->fetch();
        $stmt->close();
        $body = "
            <html>
				<body>
					<p>Hello, " . $name . "</p>
					<p>We thank you to contacting us.</p>
					<p>".$msg."</p>
					<br>
					<p>Thank you</p>
					<p>Team ColoredCow </p>
				</body>
			</html>
        ";
        require_once('sendMail.php');
        sendReply($subject,$body,$email);
        $jsonData = json_encode(array('response'=>'sent'));
        header("Content-Type: application/json");
        echo $jsonData;
    }
}
