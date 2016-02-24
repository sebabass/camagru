<?php include_once('includes/header.php');?>
<main>
	<?php
	if ($_SESSION['username']) {
		session_unset();
	}
	header('Location: index.php');
	exit;
	?>
</main>
<?php include_once('includes/footer.php');?>