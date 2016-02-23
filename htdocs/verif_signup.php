<?php include_once('includes/header.php');?>
<main>
	<?php
		if ($_SESSION['username']) {
			header('Location: index.php');
			exit;
		}
		if ($_POST['submit']) {
			$errors = null;

			if (!$_POST['login'] || !$_POST['email'] || !$_POST['password']) {
				$errors[] = 'Tous les champs doivent être remplis';
			} else {
				if (!preg_match('/^[a-zA-Z0-9]{4,32}/', $_POST['login'])) {
					$errors[] = 'Le nom d\'utilisateur doit contenir entre 4 et 32 caractères (a-z, a-Z, 0-9)';
				}

				if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) || (strlen($_POST['email']) > 100)) {
					$errors[] = 'L\'email est invalide';
				}

				if (!preg_match('/^[a-zA-Z0-9]{8,32}/', $_POST['password'])) {
					$errors[] = 'Le mot de passe doit contenir entre 8 et 32 caractères (a-z, A-Z, 0-9)';
				}
			}

			if (!is_null($errors)) {
				$_SESSION['errors_signup'] = $errors;
				header('Location: index.php');
				exit;
			}

			try {
				$pdo = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
				$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			} catch (PDOException $e) {
				$errors[] = $e->getMessage();
				$_SESSION['errors_signup'] = $errors;
				header('Location: index.php');
				exit;
			}

			$login = htmlspecialchars($_POST['login']);
			$email = htmlspecialchars($_POST['email']);
			$password = hash('whirlpool', $_POST['password']);
			$cle = hash('whirlpool', microtime(TRUE)*100000);

			if (isOneSelectExist($pdo, 'email', 'users', $email)) {
				$errors[] = 'Cet email est déjà utilisé';
			}

			if (isOneSelectExist($pdo, 'username', 'users', $login)) {
				$errors[] = 'Ce nom d\'utilisateur est déjà utilisé';
			}

			if (!is_null($errors)) {
				$_SESSION['errors_signup'] = $errors;
				header('Location: index.php');
				exit;
			}

			try {
				$query = $pdo->prepare('INSERT INTO users(username, email, password, cle) VALUES (:username, :email, :password, :cle)');
				$query->execute(array(
					'username' => $login,
					'email' => $email,
					'password' => $password,
					'cle' => $cle
				));
			} catch (PDOException $e) {
				$errors[] = $e->getMessage();
				$_SESSION['errors_signup'] = $errors;
				header('Location: index.php');
				exit;
			}
			if (!sendEmail($pdo, $email, $cle, FALSE, $login, FALSE)) {
				echo 'Impossible d\'envoyer le mail de validation';
			} else {
				echo 'Compte crée avec succès.<br />';
				echo 'Vous allez recevoir un mail dans quelques secondes pour valider votre compte.<br />';
				echo 'Pas encore reçu ? cliquez <a href=\'resend_mail.php\'>ici</a><br /><br />';
			}
		}
		echo '<a href=\'index.php\'>Se connecter</a>';
	?>
</main>
<?php include_once('includes/footer.php');?>