<?php

$bimage = true;

// this text will be deleted from URI
$remove_uri = '*-123!?/';

// exploding URI
$uri = '*-123!?' . $_SERVER['REQUEST_URI'];
$uri = str_replace( $remove_uri, '', $uri );
$uris = explode( '/', $uri );

if( $uris[0] == '' || $uris[0] == 'index.php' || $uris[0] == 'index.html' || $uris[0] == 'index.htm' ) {
	require_once('page/index.php');
} else {

	// format
	$format_get = $uris[0];
	$format_all = explode( '.', $format_get );
	if( count($format_all) == 2 ) {
		switch( $format_all[1] ) {
			case 'png':
			default:
				$format = 'png';
				break;
			case 'jpg':
			case 'jpeg':
				$format = 'jpeg';
				break;
			case 'gif':
				$format = 'gif';
				break;
		}
	} else {
		$format = 'png';
	}

	$uris_wh = explode( 'x', $format_all[0] );
	if( count($uris_wh) == 2 ) {
		$uris_w = $uris_wh[0];
		$uris_h = $uris_wh[1];
	} else {
		$uris_w = $uris_wh[0];
		$uris_h = $uris_wh[0];
	}
	array_shift($uris);
	array_unshift($uris, $uris_w, $uris_h);

	// text
	$texts = explode( '?=', end($uris) );
	array_pop( $uris );
	array_push( $uris, $texts[0] );
	if( count($texts) == 2 ) {
		$text_value = $texts[1];
	}

	####################### BEGIN USER EDITS #######################
	// function
	function Filtering($objFilter) {
		$objFilterAfter = stripslashes( strip_tags( htmlspecialchars( $objFilter , ENT_QUOTES ) ) );
		return $objFilterAfter;
	}

	// get string width
	function fixbbox($bbox) {
		$xcorr=0-$bbox[6]; //northwest X
		$ycorr=0-$bbox[7]; //northwest Y
		$tmp_bbox['left']=$bbox[6]+$xcorr;
		$tmp_bbox['top']=$bbox[7]+$ycorr;
		$tmp_bbox['width']=$bbox[2]+$xcorr;
		$tmp_bbox['height']=$bbox[3]+$ycorr;
		return $tmp_bbox;
	}

	// width
	$width 			= isset($uris[0]) ? Filtering($uris[0]) : 300;

	// height
	$height 		= isset($uris[1]) ? Filtering($uris[1]) : 130;

	// background
	$background 	= isset($uris[2]) ? Filtering($uris[2]) : 'dcdde1';

	// color
	$color 			= isset($uris[3]) ? Filtering($uris[3]) : '718093';

	// text
	$text 			= isset($text_value) ? Filtering($text_value) : false;
	$text 			= $text !== false ? urldecode($text) : false;

	####################### BEGIN USER EDITS #######################

	### Declare this script will be displayed as a PNG image.
	header("Content-type: image/{$format}");


	####################### BEGIN USER EDITS #######################
	$imagewidth = $width;
	$imageheight = $height;
	$fontsize = $imageheight;
	$fontangle = "0";
	$font = "Lato-Regular.ttf";
	// text
	if( $text !== false && $text != '' ) {
		$bbox = fixbbox(imagettfbbox($fontsize, 0, $font, $text));
		$fontsize = $imageheight/( $bbox["height"]*( 3/100 ) );
		$bbox2 = fixbbox(imagettfbbox($fontsize, 0, $font, $text));
		if($bbox2["width"] > ( $imagewidth * (70/100) ) ) {
			$fontsize = $imagewidth/( $bbox2["width"]*( 5/100 ) );
			$bbox3 = fixbbox(imagettfbbox($fontsize, 0, $font, $text));
			if($bbox3["height"] > ( $imageheight * (40/100) ) ) {
				$fontsize = $imageheight/( $bbox3["height"]*( 12/100 ) );
			}
		}
	} else {
		if( $imageheight <= ($imagewidth*(2/10)) ) {
			$fontsize = $imageheight*(3/10);
		} elseif( $imagewidth <= ($imageheight*(6.5/10)) ) {
			$fontsize = $imagewidth*(1/10);
		} else {
			$fontsize = ($imagewidth+$imageheight)*(.4/10);
		}
		$text = $imagewidth . " x " . $imageheight;;
	}
	$backgroundcolor = $background;
	$textcolor = $color;
	######################## END USER EDITS ########################

	### Convert HTML backgound color to RGB
	// if( eregi( "([0-9a-f]{2})([0-9a-f]{2})([0-9a-f]{2})", $backgroundcolor, $bgrgb ) ) {
	// 	$bgred = hexdec( $bgrgb[1] );
	// 	$bggreen = hexdec( $bgrgb[2] );
	// 	$bgblue = hexdec( $bgrgb[3] );
	// }
	$bgred = hexdec( substr( $backgroundcolor, 0, 2 ) );
	$bggreen = hexdec( substr( $backgroundcolor, 2, 2 ) );
	$bgblue = hexdec( substr( $backgroundcolor, 4, 2 ) );

	// ### Convert HTML text color to RGB
	// if( eregi( "([0-9a-f]{2})([0-9a-f]{2})([0-9a-f]{2})", $textcolor, $textrgb ) ) {
	// 	$textred = hexdec( $textrgb[1] );
	// 	$textgreen = hexdec( $textrgb[2] );
	// 	$textblue = hexdec( $textrgb[3] );
	// }
	$textred = hexdec( substr( $textcolor, 0, 2 ) );
	$textgreen = hexdec( substr( $textcolor, 2, 2 ) );
	$textblue = hexdec( substr( $textcolor, 4, 2 ) );

	### Create image
	$img = imagecreate( $imagewidth, $imageheight );

	### Declare image's background color
	$bgcolor = imagecolorallocate($img, $bgred, $bggreen, $bgblue);
	
	### Declare image's text color
	$fontcolor = imagecolorallocate($img, $textred, $textgreen, $textblue);

	### Get exact dimensions of text string
	$box = imageTTFBbox($fontsize,$fontangle,$font,$text);

	### Get width of text from dimensions
	$textwidth = abs($box[4] - $box[0]);

	### Get height of text from dimensions
	$textheight = abs($box[5] - $box[1]);

	### Get x-coordinate of centered text horizontally using length of the image and length of the text
	$xcord = ($imagewidth/2)-($textwidth/2)-2;

	### Get y-coordinate of centered text vertically using height of the image and height of the text
	$ycord = ($imageheight/2)+($textheight/2);

	### Declare completed image with colors, font, text, and text location
	imagettftext ( $img, $fontsize, $fontangle, $xcord, $ycord, $fontcolor, $font, $text );

	### Display completed image as PNG
	imagepng($img);

	### Close the image
	imagedestroy($img);

}