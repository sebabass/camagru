<?php include_once('includes/header.php'); ?>
<main>
	<div id='view-content'>
	<?php
		if (isset($_GET['id']) && (int)$_GET['id'] > 0) {
			$id_image = (int)$_GET['id'];

			try {
				$pdo = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
				$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			} catch (PDOException $e) {
				echo '<span class="error">'. $e->getMessage() .'</span>';
				die();
			}

			if (($query = $pdo->prepare('SELECT src, likes, date FROM images WHERE id_image like :id_image'))) {
				$query->execute(array(':id_image' => $id_image));
				$result = $query->fetch();

				$src = $result['src'];
				$likes = unserialize($result['likes']);
				echo '<div id="likes-block">';
				echo '<div class="bandeau-like">Qui aime cette photo?</div>';
				echo '<div id="likes-username-block">';
				foreach ($likes as $value) {
					if (($queryLike = $pdo->prepare('SELECT username FROM users WHERE id_user like :id_user'))) {
						$queryLike->execute(array(':id_user' => $value));
						$resultLike = $queryLike->fetch();
						if ($resultLike) {
							echo '<span class="like-username">'. $resultLike['username'] .'</span>';
						}
					}
				}
				echo '</div></div>';
				echo '<div id="view-picture-comments"><img src="'. $src .'" alt="'. substr($src, 4, -4) .'">';
				echo '</div>';
			} else {
				echo 'Cette photo est introuvable';
			}
		} else {
			echo 'Cette photo est introuvable';
		}
	?>
	</div>
</main>
<?php include_once('includes/footer.php');?>