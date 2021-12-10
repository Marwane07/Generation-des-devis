
<?php
session_start();
// Change this to your connection info.
$DATABASE_HOST = 'localhost';
$DATABASE_USER = 'root';
$DATABASE_PASS = '';
$DATABASE_NAME = 'stock';

if ( !isset($_POST['username'], $_POST['password']) ) {

	exit('Please fill both the username and password fields!');
}


$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
if ( mysqli_connect_errno() ) {

	exit('Échec de la connexion à MySQL : ' . mysqli_connect_error());
}





if ($stmt = $con->prepare('SELECT id, password, code_client FROM accounts WHERE username = ?')) {
	// Bind parameters (s = string, i = int, b = blob, etc), in our case the username is a string so we use "s"
	$stmt->bind_param('s', $_POST['username']);
	$stmt->execute();
	// Store the result so we can check if the account exists in the database.
	$stmt->store_result();
if ($stmt->num_rows > 0) {
	$stmt->bind_result($id, $password, $codeClient);
	$stmt->fetch();

	if ($_POST['password'] === $password) {

		session_regenerate_id();
		$_SESSION['loggedin'] = TRUE;
		$_SESSION['name'] = $_POST['username'];
		$_SESSION['id'] = $id;
        $_SESSION['codeClient'] = $codeClient;
		header('Location: home2.php');
	} else {
        
		echo '<script type="text/JavaScript"> 
            alert("GeeksForGeeks");
            return false;
            </script>';
	}
} else {
	echo 'Nom d\'utilisateur et/ou mot de passe incorrects !';
}
    
    
    
    
	$stmt->close();
}
