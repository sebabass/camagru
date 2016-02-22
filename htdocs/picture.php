<?php

	if (isset($_GET['srcimg']) && isset($_GET['w']) && isset($_GET['h'])) {
		header('Content-Type: image/png');
		
		list($width, $height) = getimagesize($_GET['srcimg']);
		$newwidth = (int)$_GET['w'];
		$newheight = (int)$_GET['h'];

		$copy = imagecreatetruecolor($newwidth, $newheight);
		$image = imagecreatefrompng($_GET['image']);
		$png = imagecreatefrompng($_GET['srcimg']);

		imagecopyresized($copy, $png, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
		imagepng($image);
	}

?>