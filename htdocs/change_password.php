<?php include_once('includes/header.php');?>
<main>
	<?php
	if ($_SESSION['username']) {
		header('Location: index.php');
		exit;
	}
	if (isset($_GET['log']) && isset($_GET['cle'])) {
		try {
			$pdo = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
			$pdo->setAttribute(PDO::ERRMODE_EXCEPTION);
		} catch (PDOException $e) {
			echo 'Connection failed: '. $e->getMessage();
			exit;
		}
		$username = $_GET['log'];
		$cle = $_GET['cle'];
		$query = $pdo->prepare('SELECT cle FROM users WHERE username like :username');
		if($query->execute(array(':username' => $username)) && $row = $query->fetch()) {
		    $clebdd = $row['cle'];
		}
		if ($cle === $clebdd) {
			if ($_POST['submit'] && $_POST['password']) {
				$password = hash('whirlpool', htmlspecialchars($_POST['password']));
				$queryPassword = $pdo->prepare('UPDATE users SET password = :password WHERE username = :username');
				$queryPassword->bindParam(':password', $password, PDO::PARAM_STR);
				$queryPassword->bindParam(':username', $username, PDO::PARAM_STR);
				$queryPassword->execute();
				echo 'Le mot de passe a été changé';
			} else {
				echo "<form action='change_password.php?log=". urlencode($_GET['log']) ."&cle=". urlencode($_GET['cle']) ."' method='POST'>";
		        echo "mot de passe<br /><input type='password' name='password' /><br />";
		        echo "<input type='submit' name='submit' value='envoyer' /></form>";
		    }
		} else {
			echo 'Impossible de changer le mot de passe (une erreur est survenue)';
		}
	} else {
		echo 'Impossible de changer le mot de passe (une erreur est survenue)';
	}
	?>
</main>
<?php include_once('includes/footer.php');?>