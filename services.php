<?php 
include('db.php');


if ($_SERVER["REQUEST_METHOD"] === "POST"){

    if(isset($_POST['response'])){

        $stmt = $conn->prepare('INSERT INTO feedback(name,email,response) VALUES(?,?,?)');
        $stmt->bind_param('sss',$_POST['name'],$_POST['email'],$_POST['response']);
        $stmt->execute();
    }

}
?>