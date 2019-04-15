<?php

/**
 * Borizqy Dummy Image
 * 
 * This library builded for developers or graphic designers to generate 
 * dummy image easier by using CSS experience.
 * 
 * @package Borizqy
 * @subpackage Dummy Image
 * @author Fiko Borizqy <fiko@dr.com>
 * @license MIT
 * @license https://choosealicense.com/licenses/mit/
 * @see https://github.com/fikoborizqy/dummy-image
 */

namespace Borizqy\DummyImage;

/**
 * Dummy Image Base Class
 * 
 * @access public
 */
class DummyImage {

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
	 * - format 			(Default: png) Extra attribute that does not exists on 
	 * 						real CSS. This attribute will set the image extension. 
	 * 						(Availables: png, jpg, jpeg, gif)
	 * 
	 * @var $control
	 */
	protected $control = [
		'content' => null,
		'font-size' => 20,
		'text-align' => 'center',
		'vertical-align' => 'middle',
		'width' => 400,
		'height' => 200,
		'background-color' => '#DCDDE1',
		'color' => '#565756',
		'format' => 'png',
		'font-family' => 'lato',
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
		 * merging custom controller to default controller
		 * @see $this->control
		 */
		$this->control = array_merge($this->control, $control);

		/**
		 * setting image filename
		 * @see $this->control['width'] Image width
		 * @see $this->control['height'] Image height
		 * @see $this->control['format'] Image extension
		 */
		header("Content-Disposition: inline; filename=\"dummy_image-{$this->control['width']}x{$this->control['height']}.{$this->control['format']}\"");
		
		/**
		 * setting page as an image file
		 * @see string $this->control['format'] Image extension
		 */
		header("Content-type: image/{$this->control['format']}");

		/**
		 * Setting up image by width and height.
		 * @var integer $this->control['width'] Image width
		 * @var integer $this->control['height'] Image height
		 */
		$this->image = imagecreatetruecolor($this->control['width'], $this->control['height']);
		
		/**
		 * if background-image controller exists, then background-image setup 
		 * will be called.
		 * @see self::setupBackgroundImage()
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
		 * Preparing Each Letter - Getting image size of one letter, to get 
		 * size of each letter.
		 * @see $this->text['letter'] 		(Result) Array of size information of 
		 * 									one letter.
		 * @see $this->control['font-size']	(Required) font size in points
		 * @see self::fontFamily() 			(Required) getting font family on 
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
		 * @see self::backgroundColor()				(Required) Getting background color
		 * 											of the image.
		 */
		if (!isset($this->control['background-image'])) {
			imagefilledrectangle($this->image, 0, 0, $this->control['width'], $this->control['height'], $this->backgroundColor());
		}

		/**
		 * Setting up text on the image
		 * @see $this->image 					(Result) 
		 * @see $this->control['font-size']		(Required) 
		 * @see $this->control['content']		(Required) 
		 * @see $this->textAlign()				(Required) 
		 * @see $this->verticalAlign()			(Required) 
		 * @see $this->color()					(Required) 
		 * @see $this->fontFamily()				(Required) 
		 */
		imagettftext($this->image, $this->control['font-size'], 0, $this->textAlign(), $this->verticalAlign(), $this->color(), $this->fontFamily(), $this->control['content']);

		// Using imagepng() results in clearer text compared with imagejpeg()
		switch ($this->control['format']) {
			// JPEG
			case 'jpg':
			case 'jpeg':
				imagejpeg($this->image);
				break;
			
			// GIF
			case 'gif':
				imagegif($this->image);
				break;
			
			// PNG
			default:
				imagepng($this->image);
				break;
		}

		// destrying image
		imagedestroy($this->image);

		// return print_r(self::$control);
	}

	protected function getColor($attr) {
		if (strlen($this->control[$attr]) == "3") {
			$r = hexdec(substr($this->control[$attr], 0, 1) . substr($this->control[$attr], 0, 1));
			$g = hexdec(substr($this->control[$attr], 1, 1) . substr($this->control[$attr], 1, 1));
			$b = hexdec(substr($this->control[$attr], 2, 1) . substr($this->control[$attr], 2, 1));
		} else if (strlen($this->control[$attr]) == "4") {
			$r = hexdec(substr($this->control[$attr], 1, 1) . substr($this->control[$attr], 1, 1));
			$g = hexdec(substr($this->control[$attr], 2, 1) . substr($this->control[$attr], 2, 1));
			$b = hexdec(substr($this->control[$attr], 3, 1) . substr($this->control[$attr], 3, 1));
		} else if (strlen($this->control[$attr]) == "7") {
			$r = hexdec(substr($this->control[$attr], 1, 2));
			$g = hexdec(substr($this->control[$attr], 3, 2));
			$b = hexdec(substr($this->control[$attr], 5, 2));
		} else {
			$r = hexdec(substr($this->control[$attr], 0, 2));
			$g = hexdec(substr($this->control[$attr], 2, 2));
			$b = hexdec(substr($this->control[$attr], 4, 2));
		}
		return imagecolorallocate($this->image, $r, $g, $b);
	}

	protected function backgroundColor() {
		return $this->getColor('background-color');
	}

	protected function color() {
		return $this->getColor('color');
	}

	protected function textAlign() {
		switch ($this->control['text-align']) {
			// left alignment
			case 'left':
				return 0;
				break;
			
			// right alignment
			case 'right':
				return $this->control['width'] - abs($this->text['sentence'][2] - $this->text['sentence'][6]);
				break;
			
			// center alignment
			case 'center': default:
				return ($this->control['width'] / 2) - (abs($this->text['sentence'][2] - $this->text['sentence'][6]) / 2);
				break;
		}
	}

	protected function verticalAlign() {
		switch ($this->control['vertical-align']) {
			// top alignment
			case 'top':
				return $this->text['letter'][1] - $this->text['letter'][5];
				break;
			
			// bottom alignment
			case 'bottom':
				return $this->control['height'] - $this->text['sentence'][1];
				break;
			
			// middle alignment
			case 'middle': default:
				$return = ($this->control['height']/2);
				$return += $this->text['letter'][1] - $this->text['letter'][5];
				$return -= ($this->text['sentence'][1] - $this->text['sentence'][5]) / 2;
				return $return;
				break;
		}
	}

	protected function fontFamily() {
		$font = strtolower($this->control['font-family']);
		$dir = $this->font_dir;

		if (file_exists($dir . $font . '.ttf')) {
			$font = $font . '.ttf';
		} else {
			$font = 'lato.ttf';
		}

		return $dir . $font;
	}

	protected function setupBackgroundImage() {
		if (isset($this->control['background-image'])) {
			$this->control['background-image'] = str_replace([
				"url(\"", "\")",
				"url('", "')",
				"url(", ")",
			], '', $this->control['background-image']);
			$extension = explode(".", $this->control['background-image']);
			$extension = end($extension);
			switch ($extension) {
				case 'jpg': case 'jpeg':
					$this->image = imagecreatefromjpeg($this->control['background-image']);
					break;

				case 'gif':
					$this->image = imagecreatefromgif($this->control['background-image']);
					break;
				
				default:
					$this->image = imagecreatefrompng($this->control['background-image']);
					break;
			}
			$this->image = imagecrop($this->image, [
				'x' => (imagesx($this->image)/2)-($this->control['width']/2),
				'y' => (imagesy($this->image)/2)-($this->control['height']/2),
				'width' => $this->control['width'],
				'height' => $this->control['height'],
			]);
		}
	}
}