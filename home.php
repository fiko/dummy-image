<?php

require_once('src/DummyImage.php');

use Borizqy\DummyImage\DummyImage;

(new DummyImage)->create([
	'height' => 150,
	'width' => 300,
	// 'background-image' => "url('https://avatars.discourse.org/v2/letter/t/bb73d2/45.png')",
	// 'background-color' => "#8EC051",
	// 'color' => "#d9d9d9",
	'content' => "Lorem ipsum",
	'font-family' => "roboto",
]);

// $img = imagecreate(400, 200);
// print_r( imagecolorallocate($img, 100, 100, 100) );