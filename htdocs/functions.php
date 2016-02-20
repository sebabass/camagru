<?php

	function isOneSelectExist ($pdo, $select, $from, $where) {
		$query = $pdo->prepare('SELECT COUNT(*) FROM '. $from .' WHERE '. $select .'=?');
		$query->execute(array($where));
		return ($query->fetchColumn());
	}

	function addUser ($pdo, $username, $email, $password, $cle) {
		$query = $pdo->prepare('INSERT INTO users(username, email, password, cle) VALUES (:username, :email, :password, :cle)');
		$query->execute(array(
			'username' => $username,
			'email' => $email,
			'password' => $password,
			'cle' => $cle
			));
	}

	function sendEmail ($pdo, $email, $cle, $isRetry, $login, $isPassword) {
		if ($isRetry || $isPassword) {
			$queryLogin = $pdo->prepare('SELECT username FROM users WHERE email like :email');
			$queryLogin->execute(array(':email' => $email));
			$result = $queryLogin->fetch();
			$login = $result['username'];
			$queryCle = $pdo->prepare('UPDATE users SET cle = :cle WHERE email = :email');
			$queryCle->bindParam(':cle', $cle, PDO::PARAM_STR);
			$queryCle->bindParam(':email', $email, PDO::PARAM_STR);
			$queryCle->execute();
		}
		if (!$login) {
			return FALSE;
		}
		if ($isPassword) {
			$subject = 'Changement de mot de passe';
			$header = 'From: changePassword@camagru.com';
			$message = 'Pour changer de mot de passe, veuillez cliquer sur le lien ci-dessous ou copier/coller dans votre navigateur. http://localhost:8080/camagru/change_password.php?log='.urlencode($login).'&cle='.urlencode($cle).' <br />Ceci est un mail automatique merci de ne pas y répondre.';
		} else {
			$subject = 'Activation du compte';
			$header = 'From: signup@camagru.com';
			$message = 'Bienvenue sur Camagru, pour activer votre compte, veuillez cliquer sur le lien ci-dessous ou copier/coller dans votre navigateur.<br />http://localhost:8080/camagru/activation.php?log='.urlencode($login).'&cle='.urlencode($cle).' <br />Ceci est un mail automatique merci de ne pas y répondre.';
		}
		return (mail($email, $subject, $message, $header));
	}
?>