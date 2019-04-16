<?php

/**
 * Borizqy Dummy Image: Main
 * 
 * (This is a main class used to generate image) - This library 
 * builded for developers or graphic designers to generate dummy 
 * image easier by using CSS experience.
 * 
 * @package Borizqy
 * @subpackage Dummy Image
 * @author Fiko Borizqy <fiko@dr.com>
 * @license MIT
 * @license https://choosealicense.com/licenses/mit/
 * @see https://github.com/fikoborizqy/dummy-image
 */

namespace Borizqy\DummyImage;

use Borizqy\DummyImage\Controller;

/**
 * Dummy Image Base Class
 * 
 * Basic Class of Borizqy Dummy Image.
 * @access public
 */
class DummyImage extends Controller {

	/**
	 * Image Stylesheet
	 * 
	 * Controlling details of the image by CSS basic. You can control
	 * details or style of image by basic of CSS. But not all CSS 
	 * attributes can be applied, only few of attributes can be applied.
	 * Those few attributes that can be applied are:
	 * - content 			(Default: null) Text that will be showed on image.
	 * 						If null, then it will show "Width x Height".
	 * - width 				(Default: 400) Image width in pixel
	 * - height 			(Default: 200) Image height in pixel
	 * - font-size 			(Default: 20) Text font-size in point
	 * - font-family 		(Default: lato) Font family that will choose on 
	 * 						"inc/fonts/" directory. (Default available fonts:
	 * 						lato, roboto, xenotron).
	 * - text-align 		(default: center) Text horizontal alignment 
	 * 						(Availables: left, center, right).
	 * - vertical-align		(Default: middle) Text vertical alignment
	 * 						(Availables: top, middle, bottom).
	 * - color				(Default: #565756) Text color in hex color.
	 * - background-color	(Default: #DCDDE1) Background color in hex color.
	 * - background-image	(Default: none) Backgroung image. If you don't 
	 * 						decide it, Dummy-Image will show only background-color
	 * - extension 			(Default: png) Extra attribute that does not exists on 
	 * 						real CSS. This attribute will set the image extension. 
	 * 						(Availables: png, jpg, jpeg, gif)
	 * 
	 * @var $control
	 */
	protected $control = [
		"content" => null,
		"font-size" => "20pt",
		"text-align" => "center",
		"vertical-align" => "middle",
		"width" => "400px",
		"height" => "200px",
		"background-color" => "#DCDDE1",
		"color" => "#565756",
		"extension" => "png",
		"font-family" => "lato",
		// 'background-image' => 'https://www.google.com/images/branding/googlelogo/1x/googlelogo_color_272x92dp.png',
	];

	/**
	 * Image object, this will be printed.
	 * 
	 * @var $image
	 */
	protected $image;

	/**
	 * Fonts directory, default directory is on "./inc/fonts/". If you'd
	 * like to add more fonts, copy those fonts in this directory. You can 
	 * only add font on *.ttf extension, and saving them by lowercase 
	 * filename.
	 * 
	 * @var $font_dir
	 */
	protected $font_dir = __DIR__ . '/../inc/fonts/';

	/**
	 * Preparing text, this will get sizes of the text (width, height)
	 * 
	 * @var $text
	 */
	protected $text = [];



	/**
	 * Generating Image
	 * 
	 * This is basic method, you can generating image by calling this
	 * method. This method created by self::create_original() method
	 * to make it can be called by static or non-static way.
	 * 
	 * @param Array 	$control 	(Default: null) Controller parameter, it can 
	 * 								be decided by using basic CSS experience.
	 * @method self::create_original()
	 */
	public static function create($control = null) {
		return (new self)->create_original($control);
	}



	/**
	 * Generating Image Original Method
	 * 
	 * When you call self::create() method, that method will calling 
	 * and returning this method instead.
	 * 
	 * @param Array 	$control 	(Default: null) Controller parameter, it can 
	 * 								be decided by using basic CSS experience.
	 * @method self::create()
	 */
	public function create_original($control = null) {
		/**
		 * if param $control didn't set, then use base controller self::$control
		 * @see self::$control
		 */
		$control = is_null($control)? $this->control: $control;
		
		// if $control is not an array, then return false
		if (!is_array($control)) return false;

		/**
		 * merging custom controller to default controller, and then 
		 * changing to lowercase
		 * @see $this->control
		 */
		$this->control = array_change_key_case(array_merge($this->control, $control));

		/**
		 * Setting up controllers's value
		 * @see Controller::setupController
		 */
		$this->setupController();

		/**
		 * setting image filename
		 * @see $this->control['width'] Image width
		 * @see $this->control['height'] Image height
		 * @see $this->control['extension'] Image extension
		 */
		header("Content-Disposition: inline; filename=\"bdi-{$this->control['width']}x{$this->control['height']}.{$this->control['extension']}\"");
		
		/**
		 * setting page as an image file
		 * @see string $this->control['extension'] Image extension
		 */
		header("Content-type: image/{$this->control['extension']}");

		/**
		 * Setting up image by width and height.
		 * @see $this->control['width'] Image width
		 * @see $this->control['height'] Image height
		 */
		$this->image = imagecreatetruecolor($this->control['width'], $this->control['height']);
		
		/**
		 * Setting up background color
		 * @see $this->image 					(Result) Image updated
		 * @see Controller::backgroundColor()	(Required) Getting background color
		 */
		imagefill($this->image, 0, 0, $this->backgroundColor());
		
		/**
		 * if background-image controller exists, then background-image setup 
		 * will be called.
		 * @see Controller::setupBackgroundImage()
		 */
		$this->setupBackgroundImage();

		/**
		 * Setting up what string will be showed at the image
		 * @see $this->control['content'] (Result) Image text
		 * @see $this->control['width'] (Required) Image width
		 * @see $this->control['height'] (Required) Image height
		 */
		$this->control['content'] = is_integer($this->control['content']) || is_string($this->control['content'])? $this->control['content']: "{$this->control['width']} x {$this->control['height']}";

		/**
		 * Preparing Each Letter - Getting width and height of for each letter
		 * @see $this->text['letter'] 		(Result) Array of size information of 
		 * 									one letter.
		 * @see $this->control['font-size']	(Required) font size in points
		 * @see Controller::fontFamily() 	(Required) getting font family on 
		 * 									"./inc/fonts/" directory.
		 */
		$this->text['letter'] = imagettfbbox($this->control['font-size'], 0, $this->fontFamily(), 'X');

		/**
		 * Preparing Text - Getting image size of one all string / sentence, 
		 * to get size of full sentence on the image.
		 * @see $this->text['sentence'] 	(Result) Array of size information 
		 * 									of complete sentence.
		 * @see $this->control['font-size']	(Required)font size in points
		 * @see $this->control['content']	(Required) Text that will be showed
		 */
		$this->text['sentence'] = imagettfbbox($this->control['font-size'], 0, $this->fontFamily(), $this->control['content']);

		/**
		 * Setting up background color, if background-image didn't set.
		 * @see $this->image						(Result) Process will be saved here
		 * @see $this->control['background-image']	(Required) Will be checked, is it exists.
		 * @see $this->control['width']				(Required) Getting image width.
		 * @see $this->control['height']			(Required) Getting image height.
		 * @see Controller::backgroundColor()		(Required) Getting background color
		 * 											of the image.
		 */
		// if (!isset($this->control['background-image'])) {
		// 	imagefilledellipse($this->image, 19.5, 19.5, 39, 39, $this->backgroundColor());
		// 	imagefilledellipse($this->image, 19.5, $this->control['height']-19.5, 39, 39, $this->backgroundColor());
		// 	imagefilledellipse($this->image, $this->control['width']-19.5, $this->control['height']-19.5, 39, 39, $this->backgroundColor());
		// 	imagefilledellipse($this->image, $this->control['width']-19.5, 19.5, 39, 39, $this->backgroundColor());
		// 	imagefilledrectangle($this->image, 0, 19.5, $this->control['width'], $this->control['height']-19.5, $this->backgroundColor());
		// 	imagefilledrectangle($this->image, 19.5, 0, $this->control['width']-19.5, $this->control['height'], $this->backgroundColor());
		// }
		
		/**
		 * Make black color transparance
		 * @see $this->image	(Result) Updating image by transparing black color
		 */
		imagecolortransparent($this->image, imagecolorallocate($this->image, 0, 0, 0));

		/**
		 * Setting up text on the image
		 * @see $this->image 					(Result) This will be updated by text on the image
		 * @see $this->control['font-size']		(Required) Getting font size for the text
		 * @see $this->control['content']		(Required) Getting text that will be put on image
		 * @see $this->textAlign()				(Required) Getting horizontal text alignment
		 * @see $this->verticalAlign()			(Required) Getting vertical text alignment
		 * @see $this->color()					(Required) Getting text color
		 * @see $this->fontFamily()				(Required) Getting font from "./inc/fonts/" directory
		 */
		imagettftext($this->image, $this->control['font-size'], 0, $this->textAlign(), $this->verticalAlign(), $this->color(), $this->fontFamily(), $this->control['content']);

		/**
		 * Build image base on extension
		 * @see Controller::buildImage() (Result) Build the image.
		 */
		$this->buildImage();

		// No more code allowed once this method executed
		exit;
	}
}