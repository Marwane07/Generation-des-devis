<?php
// Change this to your connection info.
$DATABASE_HOST = 'localhost';
$DATABASE_USER = 'root';
$DATABASE_PASS = '';
$DATABASE_NAME = 'stock';
// Try and connect using the info above.
$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
if (mysqli_connect_errno()) {
	// If there is an error with the connection, stop the script and display the error.
	exit('Failed to connect to MySQL: ' . mysqli_connect_error());
}


//PHPMAILER

//Include required PHPMailer files
require 'phpmailer/includes/PHPMailer.php';
require 'phpmailer/includes/SMTP.php';
require 'phpmailer/includes/Exception.php';
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





// Now we check if the data was submitted, isset() function will check if the data exists.
if (!isset($_POST['username'], $_POST['password'], $_POST['email'])) {
	// Could not get the data that should have been sent.
	exit('Please complete the registration form!');
}
// Make sure the submitted registration values are not empty.
if (empty($_POST['username']) || empty($_POST['password']) || empty($_POST['email'])) {
	// One or more values are empty.
	exit('Please complete the registration form');
}



// We need to check if the account with that username exists.
if ($stmt = $con->prepare('SELECT id, password FROM accounts WHERE username = ?')) {
	// Bind parameters (s = string, i = int, b = blob, etc), hash the password using the PHP password_hash function.
	$stmt->bind_param('s', $_POST['username']);
	$stmt->execute();
	$stmt->store_result();
	// Store the result so we can check if the account exists in the database.
	if ($stmt->num_rows > 0) {
		// Username already exists
		echo 'Username exists, please choose another!';
	} else {
        $stmt_activation = $con->prepare('SELECT cod_Client FROM orders WHERE client_contact = ?');
        $stmt_activation->bind_param('s', $_POST['email']);
        $stmt_activation->execute();
        $stmt_activation->bind_result($codeClient);
        $stmt_activation->fetch();
        if ($codeClient == null) {
            echo"Desolé vous n'etes pas un client chez notre entreprise";
        }else{
            $stmt_activation->close();
		// Username doesnt exists, insert new account
        if ($stmt = $con->prepare('INSERT INTO accounts (username, password, email, activation_code, code_client) VALUES (?, ?, ?, ?,?)')) {
	    // We do not want to expose passwords in our database, so hash the password and use password_verify when a user logs in.


	$uniqid = uniqid();
    $stmt->bind_param('sssss', $_POST['username'], $_POST['password'], $_POST['email'], $codeClient, $codeClient);
	$stmt->execute();
    
    
    //Set gmail username
    $mail->Username = "wolftime2017@gmail.com";
    //Set gmail password
    $mail->Password = "00212Marg";
    //Email subject
    $mail->Subject = "Activation de votre compte";
    //Set sender email
    $mail->setFrom('wolftime2017@gmail.com');
    //Enable HTML
    $mail->isHTML(true);
    
    //$mail->headers = 'From: ' . $from . "\r\n" . 'Reply-To: ' . $from . "\r\n" . 'X-Mailer: PHP/' . phpversion() . "\r\n" . 'MIME-Version: 1.0' . "\r\n" . 'Content-Type: text/html; charset=UTF-8' . "\r\n";
    
    
    
    // Update the activation variable below
    $activate_link = 'http://127.0.0.1/phplogin/activate.php?email=' . $_POST['email'] . '&code=' . $codeClient;
    $message = '<p>Veuillez cliquer sur le lien suivant pour activer votre compte: <a href="' . $activate_link . '">' . $activate_link . '</a></p>';
    
    $mail->Body = $message;
    
    $mail->addAddress($_POST['email']);
    
    
    if ( $mail->send() ) {
        echo "Email d'activation a été envoyé avec succés..!";
    }else{
        echo "Le message n'a pas pu être envoyé. Mailer Error: ".$mail->ErrorInfo;
	}

    }
    
 else {
	// Something is wrong with the sql statement, check to make sure accounts table exists with all 3 fields.
	echo 'Erreur statement!';
    }
	}
	$stmt->close();
} }else {
	// Something is wrong with the sql statement, check to make sure accounts table exists with all 3 fields.
	echo 'Erreur statement!';
}

$con->close();
?>