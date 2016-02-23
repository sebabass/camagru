<?php include_once('includes/header.php'); ?>
<main>
	<div id='view-content'>
	<?php
		if (isset($_GET['id'])) {
			echo '<img src=>';
		} else {
			echo 'Cette photo est introuvable';
		}
	?>
	</div>
</main>
<?php include_once('includes/footer.php');?>