<?php 
include_once('includes/header.php');
if (!$_SESSION['username']) {
	header('Location: index.php');
	exit;
}
?>
<main>
	<div id='gallery-block'>
		<?php
			if ($_SESSION['error_delete']) {
				echo '<span class="error">'. $_SESSION['error_delete'] .'</span>';
				unset($_SESSION['error_delete']);
			}
			if ($_SESSION['success']) {
				echo '<span class="success">'. $_SESSION['success'] .'</span>';
				unset($_SESSION['success']);
			}
		?>
		<div class='list-photo'>
			<?php
				if (isset($_GET['page'])) {
					$page = (int)$_GET['page'];
					$beg = ($page - 1) * 5;

					if ($page <= 0) {
						echo '<span class="error">page introuvable</span>';
						exit;
					}
					try {
						$pdo = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
						$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
					} catch (PDOException $e) {
						echo '<span class="error">'. $e->getMessage() .'</span>';
						die();
					}

					try {
						$query = $pdo->query('SELECT id_image, by_user, src, likes FROM images ORDER BY date DESC LIMIT '. $beg .', 5');
					} catch (PDOException $e) {
						echo '<span class="error">'. $e->getMessage() .'</span>';
						die();
					}

					echo '<ul>';
						while ($result = $query->fetch()) {
							$likes = count(unserialize($result['likes']));
							echo '<li><a href="view_picture.php?id='. $result['id_image'] .'">';
							echo '<img src="'. $result['src'] .'" alt="'. substr($result['src'], 0, -4) .'" width=100 height=100 ></a>';
							echo '<span class="likes">'. $likes .'<span></li>';
						}
					echo '</ul>';
				} else {
					echo '<span class="error">page introuvable</span>';
					exit;
				}
			?>
		</div>
		<div id='pagination'>
			<?php
				try {
					$query = $pdo->query('SELECT COUNT(*) AS nb FROM images');
				} catch (PDOException $e) {
					echo '<span class="error">'. $e->getMessage() .'</span>';
					die();
				}
				$columns = $query->fetch();
				$count = $columns['nb'];
				$nblink = floor($count / 5 + 1);
				$lastpage = 4;
				$nextpage = 1;
				if (($page - 5) >= 1) {
					echo '<a class="link" href="gallery.php?page=1">...</a>';
				}
				while ($lastpage > 0) {
					$tmp = $page - $lastpage;
					if (($page - $lastpage) > 0) {
						echo '<a class="link" href="gallery.php?page='. $tmp .'">'. $tmp .'</a>';
					}
					$lastpage--;
				}
				echo '<a class="link current" href="gallery.php?page='. $page .'">'. $page .'</a>';
				while (($page + $nextpage) <= $nblink && $nextpage <= 4) {
					$tmp = $page + $nextpage;
					echo '<a class="link" href="gallery.php?page='. $tmp .'">'. $tmp .'</a>';
					$nextpage++;
				}
				if (($page + $nextpage) <= $nblink) {
					echo '<a class="link" href="gallery.php?page='. $nblink .'">...</a>';
				}
			?>
		</div>
	</div>
</main>
<?php include_once('includes/footer.php');?>