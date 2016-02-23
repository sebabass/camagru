<?php

require_once('config/database.php');
session_start();

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

try {
	$pdo = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$query = $pdo->prepare('SELECT id_image, src, likes, comments FROM images WHERE by_user like :by_user');
	$query->execute(array(':by_user' => $_SESSION['id']));
} catch (PDOException $e) {
	echo '<error>'. $e->getMessage() .'</error>';
	die();
}

$output = '[';

while ($data = $query->fetch()) {
	$likes = count(unserialize($data['likes']));
	if ($output != "[") {$output .= ",";}
	$output .= '{"id": "'. $data['id_image'] .'",';
	$output .= '"src": "'. $data['src'] .'",';
	$output .= '"alt": "'. substr($data['src'], 4, -4) .'",';
	$output .= '"likes": "'. $likes .'",';
	$output .= '"comments": "'. unserialize($data['comments']) .'"}';
}

$output .= ']';

echo ($output);

?>