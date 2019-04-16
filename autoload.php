<?php

/**
 * Borizqy Dummy Image: Autoload
 * 
 * If you manually installed this library, you just need to load this 
 * file. Once you load this file, all files or classes that are needed
 * will be automatically loaded.
 * 
 * @package Borizqy
 * @subpackage Dummy Image
 * @author Fiko Borizqy <fiko@dr.com>
 * @license MIT
 * @license https://choosealicense.com/licenses/mit/
 * @see https://github.com/fikoborizqy/dummy-image
 */



/**
 * Load all basic methods that are needed. This file 
 * contains all methods that used by main class.
 * @see Borizqy\DummyImage\Controller
 */
require_once(__DIR__ . '/src/Controller.php');

/**
 * Main class that can generate image.
 * @see Borizqy\DummyImage\DummyImage
 */
require_once(__DIR__ . '/src/DummyImage.php');