<?php include_once('includes/header.php');?>
<main>
	<?php
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
		$query = $pdo->prepare('SELECT cle, validate FROM users WHERE username like :username');
		if($query->execute(array(':username' => $username)) && $row = $query->fetch()) {
		    $clebdd = $row['cle'];
		    $validate = $row['validate'];
		}

		if ($validate) {
			echo 'Votre compte est déjà actif !';
		} else {
			if ($cle == $clebdd) {
				$query = $pdo->prepare('UPDATE users SET validate = 1 WHERE username like :username');
            	$query->bindParam(':username', $username);
            	$query->execute();
            	echo "Votre compte a bien été activé !";
            	echo '<a href=\'index.php\'>Se connecter</a>';
			} else {
				echo "Erreur ! Votre compte ne peut être activé ...<br />";
				echo '<a href=\'resend_mail.php\'>Renvoyer moi le mail de validation</a>';
			}
		}
	} else {
		echo "Erreur ! Votre compte ne peut être activé ...<br />";
		echo '<a href=\'resend_mail.php\'>Renvoyer moi le mail de validation</a>';
	}
	?>
</main>
<?php include_once('includes/footer.php');?>