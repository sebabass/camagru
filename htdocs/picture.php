<?php
	if (isset($_GET['mode'])) {

		if ($_GET['mode'] === 'generate' && isset($_GET['srcimg']) && isset($_GET['w']) && isset($_GET['h'])) {
			header('Content-Type: image/png');
			
			list($width, $height) = getimagesize($_GET['srcimg']);
			$newwidth = (int)$_GET['w'];
			$newheight = (int)$_GET['h'];

			$copy = imagecreatetruecolor($newwidth, $newheight);
			$png = imagecreatefrompng($_GET['srcimg']);

			imagecopyresized($copy, $png, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
			imagepng($copy);
		}
	}

?>