<?php

/**
 * Borizqy Dummy Image: Demo
 * 
 * It's a demo file that you can use to make an image by using your 
 * CSS experience.
 * 
 * @package Borizqy
 * @subpackage Dummy Image
 * @author Fiko Borizqy <fiko@dr.com>
 * @license MIT
 * @license https://choosealicense.com/licenses/mit/
 * @see https://github.com/fikoborizqy/dummy-image
 */

/**
 * Loading autoload file
 * @see ./autoload.php
 */
require_once("autoload.php");

/**
 * Creating example image
 * @see ./src/DummyImage
 * @see Borizqy\DummyImage\DummyImage::create
 */
Borizqy\DummyImage\DummyImage::create([
	"height" 			=> "128px",
	"width" 			=> "128px",
	"font-size" 		=> "48pt",
	"background-color" 	=> "#8EC051",
	"color" 			=> "#709c3d",
	"content" 			=> "FB",
	"font-family" 		=> "roboto"
]);