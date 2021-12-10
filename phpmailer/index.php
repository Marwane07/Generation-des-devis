<?php

//Include required PHPMailer files
require 'includes/PHPMailer.php';
require 'includes/SMTP.php';
require 'includes/Exception.php';
//Define name spaces
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
//Create instance of PHPMailer
$mail = new PHPMailer();
//Set mailer to use smtp
$mail->isSMTP();
//Define smtp host
$mail->Host = "smtp.gmail.com";
//Enable smtp authentication
$mail->SMTPAuth = true;
//Set smtp encryption type (ssl/tls)
$mail->SMTPSecure = "tls";
//Port to connect smtp
$mail->Port = "587";
//Set gmail username
$mail->Username = "marwanelahfidi@gmail.com";
//Set gmail password
$mail->Password = "00212Marg";
//Email subject
$mail->Subject = "Test email using PHPMailer";
//Set sender email
$mail->setFrom('marwanelahfidi@gmail.com');
//Enable HTML
$mail->isHTML(true);
//Attachment
$mail->addAttachment('img/attachment.png');
//Email body
$mail->Body = "<h1>This is HTML h1 Heading</h1></br><p>This is html paragraph</p>";
//Add recipient
$mail->addAddress('wolftime2017@gmail.com');
//Finally send email
if ( $mail->send() ) {
    echo "Email Sent..!";
}else{
    echo "Message could not be sent. Mailer Error: ".$mail->ErrorInfo;
	}
//Closing smtp connection
$mail->smtpClose();
