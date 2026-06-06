<?php
#######################################################################################################
# COPYRIGHT NOTICE
# © 2015 Michael Jacobsen
# All rights reserved
# This copyright notice MUST appear in all copies of the script!
# @author				: Michael Jacobsen <-- place email address here -->
# @package				: Michael Jacobsen CMS (Content Management System)
# @file last updated	: 16.05.2015
#######################################################################################################
class fileUploader{
	//#################################################################
	// REMOVE HTML ENTITIES
	//#################################################################
	function removeHTMLEntity($str){

		$search  = array('&amp;', '&lt;', '&gt;', '&euro;', '&lsquo;', '&rsquo;', '&ldquo;','&rdquo;', '&ndash;', '&mdash;', '&iexcl;','&cent;', '&pound;', '&curren;', '&yen;', '&brvbar;', '&sect;', '&uml;', '&copy;', '&ordf;', '&laquo;', '&not;', '&reg;', '&macr;', '&deg;', '&plusmn;', '&sup2;', '&sup3;', '&acute;', '&micro;', '&para;', '&middot;', '&cedil;', '&sup1;', '&ordm;', '&raquo;', '&frac14;', '&frac12;', '&frac34;', '&iquest;', '&Agrave;', '&Aacute;', '&Acirc;', '&Atilde;', '&Auml;', '&Aring;', '&AElig;', '&Ccedil;', '&Egrave;', '&Eacute;', '&Ecirc;', '&Euml;', '&Igrave;', '&Iacute;', '&Icirc;', '&Iuml;', '&ETH;', '&Ntilde;', '&Ograve;', '&Oacute;', '&Ocirc;', '&Otilde;', '&Ouml;', '&times;', '&Oslash;', '&Ugrave;', '&Uacute;', '&Ucirc;', '&Uuml;', '&Yacute;', '&THORN;', '&szlig;', '&agrave;', '&aacute;', '&acirc;', '&atilde;', '&auml;', '&aring;', '&aelig;', '&ccedil;', '&egrave;', '&eacute;','&ecirc;', '&euml;', '&igrave;', '&iacute;', '&icirc;', '&iuml;', '&eth;', '&ntilde;', '&ograve;', '&oacute;', '&ocirc;', '&otilde;', '&ouml;', '&divide;','&oslash;', '&ugrave;', '&uacute;', '&ucirc;', '&uuml;', '&yacute;', '&thorn;', '&yuml;', '&OElig;', '&oelig;', '&sbquo;', '&bdquo;', '&hellip;', '&trade;', '&bull;', '&asymp;', "&#39;", '&quot;');

		$replace = array('');

		//REPLACE VALUES
		$str = str_replace($search, $replace, $str);

		//RETURN FORMATED STRING
		return $str;
	}

	//#################################################################
	// REMOVE SPECIAL CHARACTERS
	//#################################################################
	function removeSpecialCharacters($str){

		$search = array('<', '>', '€', '‘', '’', '“', '”', '–', '—', '¡', '¢','£', '¤', '¥', '¦', '§', '¨', '©', 'ª', '«', '¬', '®', '¯', '°', '±', '²', '³', '´', 'µ', '¶', '·', '¸', '¹', 'º', '»', '¼', '½', '¾', '¿', 'À', 'Á', 'Â', 'Ã', 'Ä', 'Å', 'Æ', 'Ç', 'È', 'É', 'Ê', 'Ë', 'Ì', 'Í', 'Î', 'Ï', 'Ð', 'Ñ', 'Ò', 'Ó', 'Ô', 'Õ', 'Ö', '×', 'Ø', 'Ù', 'Ú', 'Û', 'Ü', 'Ý', 'Þ', 'ß', 'à', 'á', 'â', 'ã','ä', 'å', 'æ', 'ç', 'è', 'é', 'ê', 'ë', 'ì', 'í', 'î', 'ï', 'ð', 'ñ', 'ò', 'ó', 'ô', 'õ', 'ö', '÷', 'ø', 'ù', 'ú', 'û', 'ü', 'ý', 'þ', 'ÿ','Œ', 'œ', '‚', '„', '…', '™', '•', '˜', "'", '"', '&');

		$replace  = array('');

		//REPLACE VALUES
		$str = str_replace($search, $replace, $str);

		//RETURN FORMATED STRING
		return $str;
	}

	//#################################################################
    // UPLOAD DOCUMENTS
    //#################################################################
	function uploadDocuments($inputfield, $docdirectory, $doctitle){
		ini_set('memory_limit', '512M');
		ini_set('max_execution_time', '900');

		//STRIP TAGS
		$doctitle	= strip_tags(strtolower($doctitle));

		//REMOVE UNWANTED CHARACTERS
		$doctitle = $this->removeHTMLEntity($doctitle);
		$doctitle = $this->removeSpecialCharacters($doctitle);

		//GET FILE INFO
		$file_upload_name 	= $_FILES[$inputfield]["name"];
		$file_temp			= $_FILES[$inputfield]["tmp_name"];
		$fileExtension		= substr($file_upload_name, strripos($file_upload_name, '.'));

		//CREATE FILE NAME
		$file_name 			= $doctitle.'-'.$this->random_string(10).$fileExtension;
		$file_name			= str_replace(' ','-', $file_name);

		//SAVE UPLOADED FILE
		move_uploaded_file($file_temp, $docdirectory.$file_name);

		//RETURN FILE NAME
		return $file_name;
	}

	//#################################################################
    // UPLOAD IMAGES
    //#################################################################
	function uploadImages($inputfield, $originaldirectory, $largedirectory, $mediumdirectory, $smalldirectory, $imagesize, $imagetitle){
		ini_set('memory_limit', '512M');
		ini_set('max_execution_time', '900');

		//STRIP TAGS
		$imagetitle	= strip_tags(strtolower($imagetitle));

		//REMOVE UNWANTED CHARACTERS
		$imagetitle = $this->removeHTMLEntity($imagetitle);
		$imagetitle = $this->removeSpecialCharacters($imagetitle);

		//GET FILE INFO
		$file_upload_name 	= $_FILES[$inputfield]["name"];
		$file_temp			= $_FILES[$inputfield]["tmp_name"];
		$file_extension		= substr($file_upload_name, strripos($file_upload_name, '.'));

		//CREATE FILE NAME
		$file_name 			= $imagetitle.'-'.$this->random_string(10).$file_extension;
		$file_name			= str_replace(' ','-', $file_name);

		//DEFINE NEW SIZES FOR THUMBNAILS
		$mediumSize			= $imagesize / 2;
		$smallSize			= $mediumSize / 2;

		//SAVE UPLOADED FILE
		move_uploaded_file($file_temp, $originaldirectory.$file_name);

		//SET IMAGE SETTINGS
		if($file_extension=='.jpg' || $file_extension=='.JPG' || $file_extension=='.jpeg' || $file_extension=='.JPEG'){
			$image = imagecreatefromjpeg($originaldirectory.$file_name);
			imagejpeg($image, $originaldirectory.$file_name, 100);
			imagedestroy($image);

			//SET IMAGE CREATE PROPERTIES
			$imageType			= 'imagejpeg';
			$imageQuality		= 100;
			$imageCreate 		= "imagecreatefromjpeg";

		}elseif($file_extension=='.png' || $file_extension=='.PNG'){
			$image = imagecreatefrompng($originaldirectory.$file_name);
			imagealphablending( $image, false );
			imagesavealpha( $image, true );
			imagepng($image, $originaldirectory.$file_name, 9);
			imagedestroy($image);

			//SET IMAGE CREATE PROPERTIES
			$imageType			= 'imagepng';
			$imageQuality		= 9;
			$imageCreate		= "imagecreatefrompng";

		}

		//IF THE LARGE DIRECTORY PATH WAS SPECIFIED
		if($largedirectory != ""){
			// SPECIFY THE NEW PREVIEW PARTH
			$uploadedfile 		= $originaldirectory.$file_name;

			$src = $imageCreate($uploadedfile);

			// OBTAIN ORIGINAL IMAGE SIZE
			list($width,$height)= getimagesize($uploadedfile);

			// CALCULATE NEW HEIGHT ACCORDING TO WIDTH
			if ($width <= $imagesize) {
				$new_width 		= $width;
				$new_height 	= $height;
				$tmp			= imagecreatetruecolor($new_width,$new_height);
			}

			if ($width > $imagesize) {
				$new_width 		= $imagesize;
				$new_height 	= $height * ($new_width/$width);
				$tmp			= imagecreatetruecolor($new_width,$new_height);
			}

			//SAVE TRANSPARENCY
			if($file_extension=='.png' || $file_extension=='.PNG'){
				imagealphablending( $tmp, false );
				imagesavealpha( $tmp, true );
			}

			// MOVE NEWLY CREATED IMAGE
			imagecopyresampled($tmp, $src, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
			$filename 			= $largedirectory. $file_name;
			$imageType($tmp,$filename,$imageQuality);
		}

		// IF THE MEDIUM DIRECTORY PATH WAS SPECIFICED
		if($mediumdirectory != ""){
			// SPECIFY THE NEW THUMBNAIL PARTH
			$uploadedfile2 		= $originaldirectory . $file_name;

			// CREATE A IMAGE TO RESIZE
			$src2 = $imageCreate($uploadedfile2);

			// OBTAIN ORIGINAL IMAGE SIZE
			list($width2,$height2)= getimagesize($uploadedfile2);

			// CALCULATE NEW HEIGHT ACCORDING TO WIDTH
			if ($width2 <= $mediumSize) {
				$new_width2 	= $width2;
				$new_height2 	= $height2;
				$tmp2			= imagecreatetruecolor($new_width2,$new_height2);
			}

			if ($width2 > $mediumSize) {
				$new_width2 	= $mediumSize;
				$new_height2 	= $height2 * ($new_width2/$width2);
				$tmp2			= imagecreatetruecolor($new_width2,$new_height2);
			}

			//SAVE TRANSPARENCY
			if($file_extension=='.png' || $file_extension=='.PNG'){
				imagealphablending( $tmp2, false );
				imagesavealpha( $tmp2, true );
			}

			// MOVE NEWLY CREATED IMAGE
			imagecopyresampled($tmp2, $src2, 0, 0, 0, 0, $new_width2, $new_height2, $width2, $height2);
			$filename2 = $mediumdirectory. $file_name;
			$imageType($tmp2,$filename2,$imageQuality);
		}

		// IF THE SMALL DIRECTORY PATH WAS SPECIFIED
		if($smalldirectory != ""){
			// SPECIFY THE NEW MOBILE PARTH
			$uploadedfile3 		= $originaldirectory . $file_name;

			// CREATE A IMAGE TO RESIZE
			$src3 = $imageCreate($uploadedfile3);

			// OBTAIN ORIGINAL IMAGE SIZE
			list($width3,$height3)= getimagesize($uploadedfile3);

			// CALCULATE NEW HEIGHT ACCORDING TO WIDTH
			if ($width3 <= $smallSize) {
				$new_width3 	= $width3;
				$new_height3 	= $height3;
				$tmp3			= imagecreatetruecolor($new_width3,$new_height3);
			}

			if ($width3 > $smallSize) {
				$new_width3 	= $smallSize;
				$new_height3 	= $height3 * ($new_width3/$width3);
				$tmp3			= imagecreatetruecolor($new_width3,$new_height3);
			}

			//SAVE TRANSPARENCY
			if($file_extension=='.png' || $file_extension=='.PNG'){
				imagealphablending( $tmp3, false );
				imagesavealpha( $tmp3, true );
			}

			// MOVE NEWLY CREATED IMAGE
			imagecopyresampled($tmp3, $src3, 0, 0, 0, 0, $new_width3, $new_height3, $width3, $height3);
			$filename3 = $smalldirectory. $file_name;
			$imageType($tmp3,$filename3,$imageQuality);
		}

		//REMOVE ORIGIANL FILE
		unlink($originaldirectory.$file_name);

		//RETURN FILE NAME
		return $file_name;
	}

	###################################################################
	## RESIZE IMAGE FROM CENTER
	###################################################################
	function cropImageCenter($max_width, $max_height, $inputfield, $sourcedirectory, $savedirectory, $fileName){
		//GET IMAGE PROPERTIES
		$imgsize = getimagesize($sourcedirectory.$fileName);
		$width = $imgsize[0];
		$height = $imgsize[1];
		$mime = $imgsize['mime'];

		//SET IMAGE CREATE VALUES
		switch($mime){

			case 'image/png':
				$image_create = "imagecreatefrompng";
				$image = "imagepng";
				$quality = 9;
				break;

			case 'image/jpeg':
				$image_create = "imagecreatefromjpeg";
				$image = "imagejpeg";
				$quality = 100;
				break;
		}

		$dst_img = imagecreatetruecolor($max_width, $max_height);
		$src_img = $image_create($sourcedirectory.$fileName);

		$width_new = $height * $max_width / $max_height;
		$height_new = $width * $max_height / $max_width;
		//if the new width is greater than the actual width of the image, then the height is too large and the rest cut off, or vice versa
		if($width_new > $width){
			//cut point by height
			$h_point = (($height - $height_new) / 2);
			//copy image
			imagecopyresampled($dst_img, $src_img, 0, 0, 0, $h_point, $max_width, $max_height, $width, $height_new);
		}else{
			//cut point by width
			$w_point = (($width - $width_new) / 2);
			imagecopyresampled($dst_img, $src_img, 0, 0, $w_point, 0, $max_width, $max_height, $width_new, $height);
		}

		$image($dst_img, $savedirectory.$fileName, $quality);

		if($dst_img)imagedestroy($dst_img);
		if($src_img)imagedestroy($src_img);
	}

	//#################################################################
    // GENERATE RANDOM STRING
    //#################################################################
	function random_string($length) {
		$key = '';
		$keys = array_merge(range(0, 9), range('a', 'z'), range('A', 'Z'));

		for ($i = 0; $i < $length; $i++) {
			$key .= $keys[array_rand($keys)];
		}

		return $key;
	}

	//#################################################################
    // UPLOAD GALLERY IMAGES
    //#################################################################
	function uploadGalleryImages($inputfield, $originaldirectory, $largedirectory, $mediumdirectory, $smalldirectory, $imagesize, $imagetitle, $file){
		ini_set('memory_limit', '512M');
		ini_set('max_execution_time', '9000');

		//STRIP TAGS
		$imagetitle	= strip_tags(strtolower($imagetitle));

		//REMOVE UNWANTED CHARACTERS
		$imagetitle = $this->removeHTMLEntity($imagetitle);
		$imagetitle = $this->removeSpecialCharacters($imagetitle);

		//GET FILE INFO
		$file_upload_name 	= $_FILES[$inputfield]["name"][$file];
		$file_temp			= $_FILES[$inputfield]["tmp_name"][$file];
		$file_extension		= substr($file_upload_name, strripos($file_upload_name, '.'));

		//CREATE FILE NAME
		$file_name 			= $imagetitle.'-'.$this->random_string(10).$file_extension;
		$file_name			= str_replace(' ','-', $file_name);

		//DEFINE NEW SIZES FOR THUMBNAILS
		$mediumSize			= $imagesize / 2;
		$smallSize			= $mediumSize / 2;

		//SAVE UPLOADED FILE
		move_uploaded_file($file_temp, $originaldirectory.$file_name);

		//SET IMAGE SETTINGS
		if($file_extension=='.jpg' || $file_extension=='.JPG' || $file_extension=='.jpeg' || $file_extension=='.JPEG'){
			$image = imagecreatefromjpeg($originaldirectory.$file_name);
			imagejpeg($image, $originaldirectory.$file_name, 100);
			imagedestroy($image);

			//SET IMAGE CREATE PROPERTIES
			$imageType			= 'imagejpeg';
			$imageQuality		= 100;
			$imageCreate 		= "imagecreatefromjpeg";

		}elseif($file_extension=='.png' || $file_extension=='.PNG'){
			$image = imagecreatefrompng($originaldirectory.$file_name);
			imagealphablending( $image, false );
			imagesavealpha( $image, true );
			imagepng($image, $originaldirectory.$file_name, 9);
			imagedestroy($image);

			//SET IMAGE CREATE PROPERTIES
			$imageType			= 'imagepng';
			$imageQuality		= 9;
			$imageCreate		= "imagecreatefrompng";

		}

		//IF THE LARGE DIRECTORY PATH WAS SPECIFIED
		if($largedirectory != ""){
			// SPECIFY THE NEW PREVIEW PARTH
			$uploadedfile 		= $originaldirectory.$file_name;

			$src = $imageCreate($uploadedfile);

			// OBTAIN ORIGINAL IMAGE SIZE
			list($width,$height)= getimagesize($uploadedfile);

			// CALCULATE NEW HEIGHT ACCORDING TO WIDTH
			if ($width <= $imagesize) {
				$new_width 		= $width;
				$new_height 	= $height;
				$tmp			= imagecreatetruecolor($new_width,$new_height);
			}

			if ($width > $imagesize) {
				$new_width 		= $imagesize;
				$new_height 	= $height * ($new_width/$width);
				$tmp			= imagecreatetruecolor($new_width,$new_height);
			}

			//SAVE TRANSPARENCY
			if($file_extension=='.png' || $file_extension=='.PNG'){
				imagealphablending( $tmp, false );
				imagesavealpha( $tmp, true );
			}

			// MOVE NEWLY CREATED IMAGE
			imagecopyresampled($tmp, $src, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
			$filename 			= $largedirectory. $file_name;
			$imageType($tmp,$filename,$imageQuality);
		}

		// IF THE MEDIUM DIRECTORY PATH WAS SPECIFICED
		if($mediumdirectory != ""){
			// SPECIFY THE NEW THUMBNAIL PARTH
			$uploadedfile2 		= $originaldirectory . $file_name;

			// CREATE A IMAGE TO RESIZE
			$src2 = $imageCreate($uploadedfile2);

			// OBTAIN ORIGINAL IMAGE SIZE
			list($width2,$height2)= getimagesize($uploadedfile2);

			// CALCULATE NEW HEIGHT ACCORDING TO WIDTH
			if ($width2 <= $mediumSize) {
				$new_width2 	= $width2;
				$new_height2 	= $height2;
				$tmp2			= imagecreatetruecolor($new_width2,$new_height2);
			}

			if ($width2 > $mediumSize) {
				$new_width2 	= $mediumSize;
				$new_height2 	= $height2 * ($new_width2/$width2);
				$tmp2			= imagecreatetruecolor($new_width2,$new_height2);
			}

			//SAVE TRANSPARENCY
			if($file_extension=='.png' || $file_extension=='.PNG'){
				imagealphablending( $tmp2, false );
				imagesavealpha( $tmp2, true );
			}

			// MOVE NEWLY CREATED IMAGE
			imagecopyresampled($tmp2, $src2, 0, 0, 0, 0, $new_width2, $new_height2, $width2, $height2);
			$filename2 = $mediumdirectory. $file_name;
			$imageType($tmp2,$filename2,$imageQuality);
		}

		// IF THE SMALL DIRECTORY PATH WAS SPECIFIED
		if($smalldirectory != ""){
			// SPECIFY THE NEW MOBILE PARTH
			$uploadedfile3 		= $originaldirectory . $file_name;

			// CREATE A IMAGE TO RESIZE
			$src3 = $imageCreate($uploadedfile3);

			// OBTAIN ORIGINAL IMAGE SIZE
			list($width3,$height3)= getimagesize($uploadedfile3);

			// CALCULATE NEW HEIGHT ACCORDING TO WIDTH
			if ($width3 <= $smallSize) {
				$new_width3 	= $width3;
				$new_height3 	= $height3;
				$tmp3			= imagecreatetruecolor($new_width3,$new_height3);
			}

			if ($width3 > $smallSize) {
				$new_width3 	= $smallSize;
				$new_height3 	= $height3 * ($new_width3/$width3);
				$tmp3			= imagecreatetruecolor($new_width3,$new_height3);
			}

			//SAVE TRANSPARENCY
			if($file_extension=='.png' || $file_extension=='.PNG'){
				imagealphablending( $tmp3, false );
				imagesavealpha( $tmp3, true );
			}

			// MOVE NEWLY CREATED IMAGE
			imagecopyresampled($tmp3, $src3, 0, 0, 0, 0, $new_width3, $new_height3, $width3, $height3);
			$filename3 = $smalldirectory. $file_name;
			$imageType($tmp3,$filename3,$imageQuality);
		}

		//REMOVE ORIGIANL FILE
		unlink($originaldirectory.$file_name);

		//RETURN FILE NAME
		return $file_name;
	}
}

//DEFINE CLASS
$fileUploader = new fileUploader();
?>
