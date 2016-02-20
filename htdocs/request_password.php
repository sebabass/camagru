<?php include_once('includes/header.php');?>
<main>
	<?php
	if ($_SESSION['username']) {
		header('Location: index.php');
		exit;
	}
	?>
	<div class='block-form'>
		<div class='title-form'>Mot de passe oublié</div>
		<div class='mt10 mb10'>
			Renseigner votre email pour réinitialiser votre mot de passe
		</div>
		<form action='request_password.php' method='POST'>
	        Email<br /><input type='email' name='email' />
	        <br />
	        <input type='submit' name='submit' value='envoyer' />
		</form>
		<?php
		if ($_POST['submit']) {
			if (!$_POST['email'] || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) || (strlen($_POST['email']) > 100)) {
				echo "<span class='error'>adresse email invalide ou inexistant</span>";
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
				if (!sendEmail($pdo, $email, $cle, FALSE, '', TRUE)) {
					echo "<span class='error'>adresse email invalide ou inexistant</span>";
				} else {
					echo "<span class='success'>Vous allez recevoir un mail dans quelques secondes pour changer de mot de passe</span>";
				}
			}
		}
		?>
	</div>	
</main>
<?php include_once('includes/footer.php');?>