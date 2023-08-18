<?php

use PHPMailer\PHPMailer\PHPMailer;

require("PHPMailer/Exception.php");
require("PHPMailer/PHPMailer.php");
require("PHPMailer/SMTP.php");


function smtp_mailer($to, $name, $id='', $for = 0)
{
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
	$mail->Subject = 'Welcome to e Library by ColoredCow!';
			$mail->Body = "
    			<html>
    				<body>
        				<p>Hello " . $name . ",</p>
        				<p>Welcome to e Library by ColoredCow! We're excited to have you join our online library community.</p>
        				<p>With your new account, you can explore our extensive collection of books:</p>
        				<ul>
            				<li>Browse through various genres and topics</li>
							<li>Read book summaries, reviews, and author information</li>
							<li>Stay updated on new arrivals and special offers</li>
        				</ul>
						<p>If you're passionate about reading, you've come to the right place!</p>
						<p>If you have any questions or need assistance, our friendly support team is here to help.</p>
						<p>Thank you for choosing e Library by ColoredCow. Happy reading!</p>
						<br>
						<p>Best regards</p>
						<p>Team ColoredCow</p>
    				</body>
    			</html>
			";
	switch ($for) {
		case 1:
			$mail->Subject = 'Email Verification';
			$mail->Body = "
    			<p>Hello, " . $name . "</p>
    			<p>Please click the link below to verify your email address.</p>
    			<a href='http://localhost:8080/website/e%20lib/verification.php?" . $id . "'>Verification Link</a>
    			<p>If you didn't request this verification, you can ignore this email.</p>
    			<br>
    			<p>Thank you</p>
    			<p>Team ColoredCow </p>
    		";
			break;
		case 2:
			$mail->Subject = 'Password Reset';
			$mail->Body = "
				<html>
				<body>
					<p>Hello, " . $name . "</p>
					<p>We received a request to reset your password for your account at e Library.</p>
					<p>Please click the link below to reset your password:</p>
					<a href='http://localhost:8080/website/e%20lib/forgetPass.php?" . $id . "'>Reset Password</a>
					<p>If you didn't initiate this request, you can ignore this email.</p>
					<p>This password reset link is valid for 1 hour.</p>
					<br>
					<p>Thank you</p>
					<p>Team ColoredCow </p>
				</body>
				</html>
			";
			break;
	}
	$mail->AddAddress($to);
	$mail->SMTPOptions = array('ssl' => array(
		'verify_peer' => false,
		'verify_peer_name' => false,
		'allow_self_signed' => false
	));
	$mail->Send();
}

//sendReply('demo subject','demo body','dishunaugai@outlook.com');
function sendReply($subject,$body,$to){
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
	$mail->Subject = $subject;
	$mail->Body = $body;

	$mail->AddAddress($to);
	$mail->SMTPOptions = array('ssl' => array(
		'verify_peer' => false,
		'verify_peer_name' => false,
		'allow_self_signed' => false
	));
	$mail->Send();
}
