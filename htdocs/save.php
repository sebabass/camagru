<?php
	session_start();
	header('Content-Type: text/xml');
	include_once('config/database.php');
	try {
		$pdo = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
		$pdo->setAttribute(PDO::ERRMODE_EXCEPTION);
	} catch (PDOException $e) {
		echo '<data>Connection failed: '. $e->getMessage() .'</data>';
		exit;
	}

    // Decode Base64 data
    $encodedData = base64_decode($_POST['data']);
	file_put_contents('img/testImage.png', $encodedData);


	/*$query = $pdo->prepare('INSERT INTO images(name, id_user) VALUES (:name, :id_user)');
	$query->execute(array(
		'name' => 'testImage.png',
		'id_user' => $_SESSION['id'];
	));*/

	echo '<data><img src='. $encodeData .' /></data>';
?>