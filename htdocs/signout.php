<?php include_once('includes/header.php');?>
<main>
	<?php
	if ($_SESSION['username']) {
		unset($_SESSION['username']);
	}
	header('Location: index.php');
	exit;
	?>
</main>
<?php include_once('includes/footer.php');?>