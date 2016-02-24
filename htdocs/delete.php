<?php

	require_once('config/database.php');
	session_start();

	function redirect_gallery($error) {
		$_SESSION['error_delete'] = $error;
		header('Location: gallery.php?page=1');
		exit;
	}

	if (isset($_GET['id'])) {

		$id_image = (int)$_GET['id'];
		try {
			$pdo = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
			$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		} catch (PDOException $e) {
			redirect_gallery($e->getMessage());
		}

		try {
			$query = $pdo->prepare('SELECT by_user FROM images WHERE id_image like :id_image');
			$query->execute(array(':id_image' => $id_image));
			$result = $query->fetch();
		} catch (PDOException $e) {
			redirect_gallery($e->getMessage());
		}

		if (!$result['by_user'] || $result['by_user'] != $_SESSION['id']) {
			redirect_gallery('Impossible de supprimer cette photo');
		}

		try {
			$query = $pdo->prepare('DELETE FROM comments WHERE id_image like :id_image');
			$query->execute(array(':id_image' => $id_image));
		} catch (PDOException $e) {
			redirect_gallery($e->getMessage());
		}

		try {
			$query = $pdo->prepare('DELETE FROM images WHERE id_image like :id_image');
			$query->execute(array(':id_image' => $id_image));
		} catch (PDOException $e) {
			redirect_gallery($e->getMessage());
		}

		$_SESSION['success'] = 'La photo a bien été supprimée';
		header('Location: gallery.php?page=1');
		exit;
	}
?>