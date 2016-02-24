<?php

	require_once('config/database.php');
	session_start();

	header('Content-Type: text/xml'); 
	echo "<?xml version=\"1.0\"?>\n";

	if (isset($_GET['img']) && isset($_GET['like'])) {

		$idimage = (int)$_GET['img'];
		$islike = (int)$_GET['like'];
		try {
			$pdo = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
			$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		} catch (PDOException $e) {
			echo '<error>'. $e->getMessage() .'</error>';
			die();
		}

		try {
			$querylikes = $pdo->prepare('SELECT likes FROM images WHERE id_image like :id_image');
			$querylikes->execute(array(':id_image' => $idimage));
			$result = $querylikes->fetch();
		} catch (PDOException $e) {
			echo '<error>'. $e->getMessage() .'</error>';
			die();
		}

		if (!$result['likes']) {
			echo '<error></error>';
			exit;
		}

		$likes = unserialize($result['likes']);

		if (!$islike) {
			if (in_array($_SESSION['id'], $likes)) {
				echo '<error></error>';
				die();
			}
			$likes[] = $_SESSION['id'];
		} else {
			if (count($likes) == 0 || !in_array($_SESSION['id'], $likes)) {
				echo '<error></error>';
				die();
			}
			$key = array_search($_SESSION['id'], $likes);
			unset($likes[$key]);
		}
		$count = count($likes);
		$likes = serialize($likes);
		try {
			$queryimage = $pdo->prepare('UPDATE images SET likes = :likes WHERE id_image = :id_image');
			$queryimage->bindParam(':likes', $likes);
			$queryimage->bindParam(':id_image', $idimage);
			$queryimage->execute();
		} catch (PDOException $e) {
			echo '<error>'. $e->getMessage() .'</error>';
			die();
		}
		echo '<success>'. $count .'</success>';
	}

?>