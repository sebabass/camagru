<?php include_once('includes/header.php');?>
<main>
	<?php
	if ($_SESSION['username']) {
		header('Location: index.php');
		exit;
	}
	?>
	<form action='resend_mail.php' method='POST'>
        Email<br /><input type='email' name='email' />
        <br />
        <input type='submit' name='submit' value='envoyer' />
	</form>
	<?php
	if ($_POST['submit']) {
		if (!$_POST['email'] || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) || (strlen($_POST['email']) > 100)) {
			echo 'Email inexistant ou invalide';
		} else {
			try {
				$pdo = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
				$pdo->setAttribute(PDO::ERRMODE_EXCEPTION);
			} catch (PDOException $e) {
				echo 'Connection failed: '. $e->getMessage();
				exit;
			}
			$email = htmlspecialchars($_POST['email']);
			$cle = hash('whirlpool', microtime(TRUE)*100000);
			if (!sendEmail($pdo, $email, $cle, TRUE, '', FALSE)) {
				echo 'Mail invalide ou inexistant, impossible d\'envoyer le mail de validation';
			} else {
				echo 'Vous allez recevoir un mail dans quelques secondes pour valider votre compte.<br />';
				echo '<a href=\'index.php\'>Se connecter</a>';
			}
		}
	}
	?>
</main>
<?php include_once('includes/footer.php');?>