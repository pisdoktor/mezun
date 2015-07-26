<?php
// no direct access
defined( 'ERISIM' ) or die( 'Bu alanı görmeye yetkiniz yok!' );

class mezunImageHelper {
	
	static function check($src) {
		
		$allowed_ext = array('png', 'gif', 'jpg', 'jpeg');
	
		$maxsize = 2; // 2 mb
		
		if (!in_array(mezunImageHelper::getExt($src), $allowed_ext)) {
			$error = addslashes( 'Dosya türü uygun değil');
			return false;
		}
		
		if (mezunImageHelper::getImageBoyut($src) > ($maxsize*1048576)) {
			$error = addslashes('Dosya boyutu istenilenden büyük!');
			return false;
		}
	
		return true;
	}
	
	static function getExt($src) {
		$ext_type = pathinfo($src);
		return strtolower($ext_type["extension"]);
	}
	
	static function changeName($src, $len=10) {
		
		$ext = mezunImageHelper::getExt($src);
					
		return MakePassword($len).'.'.$ext;
		
	}
	
	static function crop($src, $dest_x, $dest_y, $src_x, $src_y, $dest_w, $dest_h, $src_w, $src_h) {
		
		$ext = mezunImageHelper::getExt($src);
		
		switch($ext) {
			case 'jpg':
			case 'jpeg':
			$img_r = imagecreatefromjpeg($src);
			break;
		
			case 'png':
			$img_r = imagecreatefrompng($src);
			break;
		
			case 'gif':
			$img_r = imagecreatefromgif($src);
			break;
		}
		
		$dest_r = ImageCreateTrueColor( $dest_w, $dest_h );
		
		imagecopyresampled($dest_r, $img_r, $dest_x, $dest_y, $src_x, $src_y, $dest_w, $dest_h, $src_w, $src_h);
		
		switch($ext) {
		case 'jpg':
		case 'jpeg':
		imagejpeg($dest_r, $src, 100);
		break;
		
		case 'png':
		imagepng($dest_r, $src, 100);
		break;
		
		case 'gif':
		imagegif($dest_r, $src, 100);
		break;
		}
		
		imagedestroy($dest_r);
		
	}
	
	static function resize($src, $dest_x, $dest_y, $src_x, $src_y, $dest_w, $dest_h, $src_w, $src_h) {
		
		$ext = mezunImageHelper::getExt($src);
		
		switch($ext) {
			case 'jpg':
			case 'jpeg':
			$img_r = imagecreatefromjpeg($src);
			break;
		
			case 'png':
			$img_r = imagecreatefrompng($src);
			break;
		
			case 'gif':
			$img_r = imagecreatefromgif($src);
			break;
		}
		
		$dest_r = ImageCreateTrueColor( $dest_w, $dest_h );
		
		imagecopyresampled($dest_r, $img_r, 0, 0, 0, 0, $dest_w, $dest_h, $src_w, $src_h);
		
		switch($ext) {
		case 'jpg':
		case 'jpeg':
		imagejpeg($dest_r, $src, 100);
		break;
		
		case 'png':
		imagepng($dest_r, $src, 100);
		break;
		
		case 'gif':
		imagegif($dest_r, $src, 100);
		break;
		}
		
		imagedestroy($dest_r);
		
	}
	
	static function getImageWidth($src) {
		list($width, $height) = getimagesize($src);
		
		return $width;
	}
	
	static function getImageHeight($src) {
		list($width, $height) = getimagesize($src);
		
		return $height;
	}
	
	static function deleteImage($src) {
		if (file_exists($src)) {
			@unlink($src);
			return true; 
		} else {
			return false;
		}
	}
	
	static function getImageBoyut($src) {
		if (file_exists($src)) {
			return filesize($src);	
		} else {
			return 0;
		}	
	}
	
	static function isImage($src) {
		if (file_exists($src)) {
			return true;
		} else {
			return false;
		}
	}
	
}
