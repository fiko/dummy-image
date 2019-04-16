<?php

require_once('autoload.php');

use Borizqy\DummyImage\DummyImage;

(new DummyImage)->create([
	'height' => '350px',
	'width' => '350px',
	// 'background-image' => "url('https://www.google.com/images/branding/googlelogo/1x/googlelogo_color_272x92dp.png')",
	'background-color' => "#8EC051",
	// 'color' => "#d9d9d9",
	'content' => "F",
	'font-size' => "30pt",
	'format' => "png",
	'border-radius' => '10px 20px',
	'font-family' => "roboto",
	// 'color' => "#000000",
]);

// $img = imagecreate(400, 200);
// print_r( imagecolorallocate($img, 100, 100, 100) );