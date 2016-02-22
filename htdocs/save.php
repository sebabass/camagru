<?php

require_once('config/database.php');
session_start();

header('Content-Type: text/xml'); 
echo "<?xml version=\"1.0\"?>\n";

if (isset($GLOBALS["HTTP_RAW_POST_DATA"])) {

	try {
		$pdo = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	} catch (PDOException $e) {
		echo '<error>'. $e->getMessage() .'</error>';
		die();
	}
	$id = $_SESSION['id'];
	$src = 'img/' . $_SESSION['username'] . '_' . hash('md5', microtime(TRUE)*100000) . '.png';
	$likes = serialize([]);
	$comments = serialize([]);

	$query = $pdo->prepare('INSERT INTO images(by_user, src, likes, comments) VALUES (:by_user, :src, :likes, :comments)');
	$query->execute(array(
		'by_user' => $id,
		'src' => $src,
		'likes' => $likes,
		'comments' => $comments
		));

	$dataurl = $GLOBALS["HTTP_RAW_POST_DATA"];
	$filterdata = substr($dataurl, strpos($dataurl, ",")+1);
	$decodedata = base64_decode($filterdata);
	$fp = fopen($src, 'w');
    fwrite($fp, $decodedata);
    fclose($fp);
    echo '<success>Image sauvegarder avec succès</success>';
}

?>