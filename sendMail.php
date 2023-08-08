<?php
use PHPMailer\PHPMailer\PHPMailer;

require("PHPMailer/Exception.php");
require("PHPMailer/PHPMailer.php");
require("PHPMailer/SMTP.php");


function smtp_mailer($to,$name,$id){
	$mail = new PHPMailer(true); 
	$mail->IsSMTP(); 

	$mail->SMTPAuth = true; 
	$mail->SMTPSecure = 'tls'; 
	$mail->Host = "smtp.gmail.com";
	$mail->Port = 587; 
	$mail->IsHTML(true);
	$mail->CharSet = 'UTF-8';
	require_once('env.php');
	$mail->Username = $myMail;
	$mail->Password = $myPass;
	$mail->SetFrom($myMail);
	$mail->Subject = 'Email Verification';
	$mail->Body = "
    <p>Hello, ".$name."</p>
    <p>Please click the link below to verify your email address.</p>
    <a href='http://localhost:8080/website/e%20lib/verification.php?".$id."'>Verification Link</a>
    <p>If you didn't request this verification, you can ignore this email.</p>
    <br>
    <p>Thank you,</p>
    <p>Your Company Name</p>
    ";
	$mail->AddAddress($to);
	$mail->SMTPOptions=array('ssl'=>array(
		'verify_peer'=>false,
		'verify_peer_name'=>false,
		'allow_self_signed'=>false
	));
	$mail->Send();
}
?>
