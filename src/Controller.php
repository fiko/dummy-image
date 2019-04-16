<?php

/**
 * Borizqy Dummy Image: Controller
 * 
 * This file must be loaded if you would like to use Borizqy Dummy 
 * Image library. Because this abstract class contains whole 
 * methods that are main class needed.
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
 * Dummy Image Controller Class
 * 
 * An abstract class to prepare basic methods
 * that Dummy Image needed.
 * @abstract
 */
abstract class Controller {

	/**
	 * Setup Controller
	 * 
	 * @see $this->control (Result && Required) This controller will be updated
	 */
	protected function setupController() {
		// removing "px", "pt" on "font-size", then converting to integer
		$this->control['font-size'] = intval(str_replace(['px', 'pt'], '', $this->control['font-size']));

		// removing "px" on "width", then converting to integer
		$this->control['width'] = intval(str_replace('px', '', $this->control['width']));
		
		// removing "px" on "height", then converting to integer
		$this->control['height'] = intval(str_replace('px', '', $this->control['height']));

		/**
		 * Setting up border-radius
		 */
		// $borderRadius = $this->getControlPosition('border-radius');
		// $borderTopLeftRadius = $this->getControlPosition('border-top-left-radius');
		// $borderTopRightRadius = $this->getControlPosition('border-top-right-radius');
		// $borderBottomRightRadius = $this->getControlPosition('border-bottom-right-radius');
		// $borderBottomLeftRadius = $this->getControlPosition('border-bottom-left-radius');
		// $this->control['border-top-left-radius']
		// $this->control['border-top-right-radius']
		// $this->control['border-bottom-right-radius']
		// $this->control['border-bottom-left-radius']
	}



	/**
	 * Getting an array item position on $this->control array
	 * @see $this->control (Required) Get the array
	 */
	protected function getControlPosition($key) {
		return array_search($key, array_keys($this->control));
	}



	/**
	 * Build Image
	 * 
	 * Once all setup completed, then build the image base on what image 
	 * do you want. For a moment, there are only 3 image extension or 
	 * format you can choose. They are png, gif, and jpg/jpeg.
	 * 
	 * @see $this->image						(Result) Updated image with background-image
	 * @see $this->control['background-image']	(Required) URL or path of an image
	 * @see $this->control['width']				(Required) Getting image width
	 * @see $this->control['height']			(Required) Getting image height
	 */
	protected function buildImage() {
		// switching extension
		switch ($this->control['extension']) {
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

		// destroying image once builded
		imagedestroy($this->image);
	}



	/**
	 * Setup Background Image
	 * 
	 * Setting up background image base on $this->control['background-image'].
	 * That image will be cropped to the size of image (width x height).
	 * 
	 * @see $this->image						(Result) Updated image with background-image
	 * @see $this->control['background-image']	(Required) URL or path of an image
	 * @see $this->control['width']				(Required) Getting image width
	 * @see $this->control['height']			(Required) Getting image height
	 */
	protected function setupBackgroundImage() {
		if (isset($this->control['background-image'])) {
			// Removing all url text "url(...)" "url('...')" "url("...")"
			$this->control['background-image'] = str_replace([
				"url(\"", "\")",
				"url('", "')",
				"url(", ")",
			], '', $this->control['background-image']);
			
			// Getting image file extension
			$extension = explode(".", $this->control['background-image']);
			$extension = end($extension);

			// Switching extension
			switch ($extension) {
				// jpg image
				case 'jpg': case 'jpeg':
					$this->image = imagecreatefromjpeg($this->control['background-image']);
					break;

				// gif image
				case 'gif':
					$this->image = imagecreatefromgif($this->control['background-image']);
					break;
				
				// png image
				default:
					$this->image = imagecreatefrompng($this->control['background-image']);
					break;
			}

			// Cropping image at center
			$this->image = imagecrop($this->image, [
				'x' => (imagesx($this->image)/2)-($this->control['width']/2), // x position
				'y' => (imagesy($this->image)/2)-($this->control['height']/2), // y position
				'width' => $this->control['width'],
				'height' => $this->control['height'],
			]);
		}
	}



	/**
	 * Font Family
	 * 
	 * Checking font family on "./inc/fonts/" directory. If font family 
	 * exists, then returning that font, but if it doesn't exist then
	 * return "lato" font family.
	 * 
	 * @return String Path of that font-family
	 * @see $this->control['font-family']	(Required) Getting font-family information
	 * @see $this->font_dir					(Required) Getting fonts directory
	 */
	protected function fontFamily() {
		$font = strtolower($this->control['font-family']); // lowing case of all text
		$dir = $this->font_dir; // getting fonts directory

		// checking does font exists, if doesn't then return lato
		if (file_exists($dir . $font . '.ttf')) {
			$font = $font . '.ttf';
		} else {
			$font = 'lato.ttf';
		}

		// returning path of font-family
		return $dir . $font;
	}



	/**
	 * Vertical alignment
	 * 
	 * Setting up vertical text alignment on the image by setting up
	 * text "y" position on the image from top to bottom.
	 * 
	 * @return Integer Position from top to bottom
	 * @see $this->control['vertical-align']	(Required) Getting top, middle, bottom
	 * @see $this->control['height']			(Required) Getting image height
	 * @see $this->text['sentence']				(Required) Getting height of complete text
	 * @see $this->text['letter']				(Required) Getting height of for each letter
	 */
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



	/**
	 * Horizontal alignment
	 * 
	 * Setting up horizontal text alignment on the image by setting up
	 * text "x" position on the image from left to right.
	 * 
	 * @return Integer Position from left to right
	 * @see $this->control['text-align']	(Required) Getting left, center, right
	 * @see $this->control['width']			(Required) Getting image width
	 * @see $this->text['sentence']			(Required) Getting width of complete text
	 * @see $this->text['letter']			(Required) Getting width of for each letter
	 */
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



	/**
	 * Get Color from Hex Color
	 * 
	 * Getting REG color from Hex-Color in $this->control array, then
	 * process will return imagecolorallocate of that RGB color.
	 * 
	 * @param String $attr Key attribute of $this->control
	 * @return Integer Color allocate of RGB color on the image
	 */
	protected function getColor($attr) {
		// if hex-color is base on 7-digits, #FFFFFF
		if (strlen($this->control[$attr]) == "7") {
			$r = hexdec(substr($this->control[$attr], 1, 2)); // Red color
			$g = hexdec(substr($this->control[$attr], 3, 2)); // Green color
			$b = hexdec(substr($this->control[$attr], 5, 2)); // Blue color
		}
		
		// if hex-color is base on 4-digits, #FFF
		else if (strlen($this->control[$attr]) == "4") {
			$r = hexdec(substr($this->control[$attr], 1, 1) . substr($this->control[$attr], 1, 1)); // Red color
			$g = hexdec(substr($this->control[$attr], 2, 1) . substr($this->control[$attr], 2, 1)); // Green color
			$b = hexdec(substr($this->control[$attr], 3, 1) . substr($this->control[$attr], 3, 1)); // Blue color
		}

		// if hex-color in wrong formation, then all sets to white
		else {
			$r = $g = $b = hexdec('FF');
		}

		// return color alocation for the $this->image
		return imagecolorallocate($this->image, $r, $g, $b);
	}



	/**
	 * Allocating background color from "background-color" controller
	 * 
	 * @return Integer Color allocation of RGB color for the image
	 * @see self::getColor()
	 */
	protected function backgroundColor() {
		return $this->getColor('background-color');
	}



	/**
	 * Allocating text color from "color" controller
	 * 
	 * @return Integer Color allocation of RGB color for the image
	 * @see self::getColor()
	 */
	protected function color() {
		return $this->getColor('color');
	}
}