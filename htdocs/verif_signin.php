<?php include_once('includes/header.php');?>
<main>
	<?php
		if ($_SESSION['username']) {
			header('Location: index.php');
			exit;
		}
		if ($_POST['submit']) {
			$errors = null;

			if (!$_POST['login'] || !$_POST['password']) {
				$_SESSION['errors_signin'] = 'Tous les champs doivent être remplis';
				header('Location: index.php');
				exit;
			}

			try {
				$pdo = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
				$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			} catch (PDOException $e) {
				$_SESSION['errors_signin'] = 'Connection failed: '. $e->getMessage();
				header('Location: index.php');
				exit;
			}

			$login = htmlspecialchars($_POST['login']);
			$password = hash('whirlpool', $_POST['password']);

			try {
				$query = $pdo->prepare("SELECT id_user, validate, password FROM users WHERE username like :username ");
			} catch (PDOException $e) {
				$_SESSION['errors_signin'] = $e->getMessage();
				header('Location: index.php');
				exit;
			}
			if($query->execute(array(':username' => $login))  && $row = $query->fetch())
  			{
  				$passwordBdd = $row['password'];
   				$validate = $row['validate'];
   				$id_user = $row['id_user'];
  			} else {
  				$_SESSION['errors_signin'] = 'Nom d\'utilisateur invalide';
				header('Location: index.php');
				exit;
  			}

			if (!$validate) {
				$_SESSION['errors_signin'] = 'Votre compte n\'est pas activé<br /><a href=\'resend_email.php\'>Activer mon compte</a>';
			} else if ($password != $passwordBdd) {
				$_SESSION['errors_signin'] = 'Mauvais mot de passe';
			} else {
				$_SESSION['username'] = $login;
				$_SESSION['id'] = $id_user;
			}
			header('Location: index.php');
			exit;
		}
	?>
</main>
<?php include_once('includes/footer.php');?>