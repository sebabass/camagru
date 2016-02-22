<?php
	session_start();
	require_once('config/database.php');
	require_once('functions.php');
?>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
	<head>
		<meta charset="utf-8" />
		<title>Camagru</title>
		<link type='text/css' rel='stylesheet' href='css/style.css'>
	</head>
	<body>
		<header>
			<div id='menu'>
				<div class='bandeau'>
					<?php
						if ($_SESSION['username']) {
							echo "<span class='left ml10'><span class='right mr10'><a href='index.php'>Montage</a></span></span>";
							echo "<span class='right mr10'><a href='signout.php'>se deconnecter</a></span>";
						} else {
							echo "<span class='left ml10'><span class='right mr10'><a href='index.php'>Accueil</a></span></span>";
							echo "<span class='right mr10'><a href='index.php'>connexion</a></span>";
						}
					?>
				</div>
				<div class='title'>
					CAMAGRU
				</div>
			</div>
		</header>