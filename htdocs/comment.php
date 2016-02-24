<?php

	require_once('config/database.php');
	session_start();

	header('Content-Type: text/xml'); 
	echo "<?xml version=\"1.0\"?>\n";

	if ($_POST['img'] && $_POST['comment']) {

		$idimage = $_POST['img'];
		$comment = htmlspecialchars($_POST['comment']);

		try {
			$pdo = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
			$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		} catch (PDOException $e) {
			echo '<error>'. $e->getMessage() .'</error>';
			die();
		}

		try {
			$query = $pdo->prepare('INSERT INTO comments(comment, username, id_image) VALUES (:comment, :username, :id_image)');
			$query->execute(array(
				'comment' => $comment,
				'username' => $_SESSION['username'],
				'id_image' => $idimage
			));
		} catch (PDOException $e) {
			echo '<error>'. $e->getMessage() .'</error>';
			die();
		}
		echo '<success>'. $_SESSION['username'] .'</success>';
	}
?>