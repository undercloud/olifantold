<?php
	namespace core\drive;

		class Model_Image
		{
			public static $captcha_length = 4;
			public static $captcha_fonts  = array();

			public static function createFromMIME($type,$source)
			{
				switch($type){
					case 'image/gif': return imagecreatefromgif($source);
					case 'image/jpg': return imagecreatefromjpeg($source);
					case 'image/png': return imagecreatefrompng($source);
					default : return false;
				}
			}

			public static function writeImage($dest,$file = null)
			{
				if(null === $file){
					$ext = $file;
					header("Content-Type: image/jpeg");
				}else{
					$ext = pathinfo($file,PATHINFO_EXTENSION);
				}

				switch($ext){
					case null:
					case 'jpg':
					case 'jpeg':
						return imagejpeg($dest,$file,100);
					
					case 'png':
						return imagepng($dest,$file,0);

					case 'gif':
						return imagegif($dest,$file);
				}
			}

			public static function getImageType($file)
			{ 
				if(false === ($f = fopen($file, 'rb'))) 
					return false;
				
				$data = fread($f, 8); 
				fclose($f); 

				$unpacked = unpack('H12', $data);
				if(array_pop($unpacked) == '474946383961' or array_pop($unpacked) == '474946383761')
					return 'image/gif'; 

				$unpacked = unpack('H4', $data);
				if(array_pop($unpacked) == 'ffd8')
					return 'image/jpg'; 

				$unpacked = unpack('H16', $data);
				if(array_pop($unpacked) == '89504e470d0a1a0a')
					return 'image/png'; 
				
				return false; 
			}
			
			private static function keepTransparent($type,$isrc,$idest,$width,$height)
			{
				if($type == "image/jpg") {

				}else if($type == "image/gif"){
					$index = imagecolortransparent($isrc);
					if ($index >= 0) {
						$color = imagecolorsforindex($isrc, $index);
						$index  = imagecolorallocate($idest, $color['red'], $color['green'], $color['blue']);				
						imagefill($idest, 0, 0, $index);				
						imagecolortransparent($idest, $index);
					}
				}else if($type == "image/png") {
					imagealphablending($idest, false);
					imagesavealpha($idest,true);
					$transparent = imagecolorallocatealpha($idest, 255, 255, 255, 127);
					imagefilledrectangle($idest, 0, 0, $width, $height, $transparent);
				}
			}

			/*
				source,
				dest,
				array(
					width  =>1024,
					height => 768
				)

			*/

			public static function resizeForce($source, $dest, array $info)
			{
				$type = self::getImageType($source);
				if($type === false)
					return false;

				$source_image = self::createFromMIME($type,$source);

				$source_width  = imagesx($source_image);
				$source_height = imagesy($source_image);
				$dest_width  = $info["width"];
				$dest_height = $info["height"];
				$n1 = $source_width / $dest_width;
				$n2 = $source_height / $dest_height;
				$x = $y = $xx = $yy = $x0 = $y0 = 0;
				$delta_h = $source_height;
				$delta_w = $source_width;

				if($n2 > $n1){
					$delta_h = $n1 * $dest_height;
					$y = ($source_height - $delta_h) / 2;
				}else if($n2 < $n1){
					$delta_w = $n2 * $dest_width;
					$x = ($source_width - $delta_w) / 2;
				}
				
				$dest_image = imagecreatetruecolor($dest_width, $dest_height);
				self::keepTransparent($type,$source_image,$dest_image,$dest_width,$dest_height);
				imagecopyresampled(
					$dest_image, $source_image, 
					$x0, $y0, 
					$x, $y, 
					$dest_width-$x0*2, $dest_height-$y0*2, 
					$delta_w-$xx, $delta_h-$yy
				);

				self::writeImage($dest_image, $dest);
				imagedestroy($source_image);
				imagedestroy($dest_image);	

				return true;	
			}

			/*
				$source,
				$dest,
				array(
					width  => 1024, OR
					height => 768
				)
			*/

			public static function resizeProp($source,$dest,array $info)
			{
				$type = self::getImageType($source);
				if($type === false)
					return false;

				$isrc = self::createFromMIME($type,$source);

				$source_width  = imagesx($isrc);
				$source_height = imagesy($isrc);

				if(isset($info['width'])){
					$width  = $info['width'];
					$height = $source_height * ($width / $source_width);
				}else if(isset($info['height'])){
					$height = $info['height'];
					$width  = $source_width * ($height / $source_height);
				}

				$idest = imagecreatetruecolor($width, $height);
		
				self::keepTransparent($type,$isrc,$idest,$width,$height);
		
				imagecopyresampled(
					$idest, 
					$isrc, 
					0, 0, 0, 0, 
				 	$width, $height, 
				 	$source_width, $source_height
				);

				self::writeImage($idest, $dest);

				imagedestroy($isrc);
				imagedestroy($idest);

				return true;
			}

			/*
				$source,
				$dest,
				array(
					width  =>1024,
					height =>768,
					left=> 0,
					top=>0,
					selectionWidth  => 200,
					selectionHeight => 200
				)
			*/

			public static function resizeArea($source,$dest,array $info)
			{
				$type = self::getImageType($source);
				if($type === false)
					return false;

				$source_image = self::createFromMIME($type,$source);

				$source_width  = imagesx($source_image);
				$source_height = imagesy($source_image);
				$dest_width = $info["width"];
				$dest_height = $info["height"];
				$n1 = $source_width / $dest_width;
				$n2 = $source_height / $dest_height;
				$x = $info['left'];
				$y = $info['top'];
				$xx = $info['selectionWidth'];
				$yy = $info['selectionHeight'];
					
				$dest_image = imagecreatetruecolor($dest_width, $dest_height);
				self::keepTransparent($type,$source_image,$dest_image,$dest_width,$dest_height);
				imagecopyresampled(
					$dest_image, 
					$source_image, 
					0, 0, 
					$x, $y, 
					$dest_width, $dest_height, 
					$xx, $yy
				);

				self::writeImage($dest_image, $dest);
				imagedestroy($source_image);
				imagedestroy($dest_image);

				return true;
			}

			public static function jpg2png($jpg,$png)
			{
				$jpg_res = imagecreatefromjpeg($jpg);
				imagepng($jpg_res,$png);
				imagedestroy($jpg_res);
			}

			public static function jpg2gif($jpg,$gif)
			{
				$jpg_res = imagecreatefromjpeg($jpg);
				imagegif($jpg_res,$gif);
				imagedestroy($jpg_res);
			}

			public static function png2jpg($png,$jpg)
			{
				$png_res = imagecreatefrompng($png);
				imagejpeg($png_res,$jpg,100);
				imagedestroy($png_res);
			}

			public static function png2gif($png,$gif)
			{
				$size = getimagesize($png);
				$img = imagecreatefrompng($png);
				$image = imagecreatetruecolor($width = $size[0], $height = $size[1]);
				imagefill($image, 0, 0, $bgcolor = imagecolorallocatealpha($image, 255, 255, 255,127));
				imagecopyresampled($image, $img, 0, 0, 0, 0, $width, $height, $width, $height);
				imagecolortransparent($image, $bgcolor);
				imagegif($image, $gif);
				imagedestroy($image);
			}

			public static function gif2jpg($gif,$jpg)
			{
				$gif_res = imagecreatefromgif($gif);
				imagegif($gif_res,$jpg);
				imagedestroy($gif_res);
			}

			public static function gif2png($gif,$png)
			{
				$size = getimagesize($gif);
				$img = imagecreatefromgif($gif);
				$image = imagecreatetruecolor($width = $size[0], $height = $size[1]);
				imagefill($image, 0, 0, $bgcolor = imagecolorallocatealpha($image, 255, 255, 255,127));
				imagecopyresampled($image, $img, 0, 0, 0, 0, $width, $height, $width, $height);
				imagecolortransparent($image, $bgcolor);
				imagepng($image, $png);
				imagedestroy($image);
			}
			
			public static function rotate($source,$dest,$angle)
			{
				$type = self::getImageType($source);
				$source_image = self::createFromMIME($type,$source);
				$source_image = imagerotate($source_image, $angle,0);
				self::writeImage($source_image, $dest);
				imagedestroy($source_image);
			}

			/*
				position=>array(
					"x"=>left|right|center
					"y"=>top|bottom|middle
					"mode" => position|mosaic
					"opacity" => 100,
					"padding_x" => 10
					"padding_y" => 10,
					"zoom" ? 
				)
			*/

			public static function setWatermark($source,$watermark,$dest,$options = array())
			{
				if(isset($options['mode']) == false)      $options['mode'] = 'position';
				if(isset($options['opacity']) == false)   $options['opacity'] = 0;
				if(isset($options['x']) == false)         $options['x'] = 'right';
				if(isset($options['y']) == false)         $options['y'] = 'bottom';
				if(isset($options['padding_x']) == false) $options['padding_x'] = 20;
				if(isset($options['padding_y']) == false) $options['padding_y'] = 20;
				if(isset($options['angle']) == false)     $options['angle'] = 0;

				$source_info = self::getImageType($source);
				$source_image = self::createFromMIME($source_info,$source);

				$sx = imagesx($source_image);
				$sy = imagesy($source_image);
				
				$watermark_info  = self::getImageType($watermark);
				$watermark_image = self::createFromMIME($watermark_info,$watermark);
				
				$wx = imagesx($watermark_image);
				$wy = imagesy($watermark_image);

				if(isset($options['zoom'])){
					$x = $sx / 100 * (int)$options['zoom'];
					$y = $x / ($wx / $wy);

					$watermark_image_tmp = imagecreatetruecolor($x, $y);

					self::keepTransparent(
						$watermark_info,
						$watermark_image,
						$watermark_image_tmp,
						$x,
						$y
					);

					imagecopyresampled(
						$watermark_image_tmp, 
						$watermark_image, 
						0, 0, 0, 0, 
					 	$x, $y, 
					 	$wx, $wy
					);

					$watermark_image = $watermark_image_tmp;
				}else{
					$x = $wx;
					$y = $wy;
				}

				if($options['angle'] != 0)
					$watermark_image = imagerotate($watermark_image,$options['angle'],imagecolorallocatealpha($watermark_image , 0, 0, 0, 127));

				imagealphablending($source_image,true);
				imagealphablending($watermark_image,true);
				imagesavealpha($source_image,true);
				imagesavealpha($watermark_image,true);

				if($options["mode"] == "position"){
					switch($options["x"]){
						case 'left' : $sx = $options["padding_x"]; break;
						case 'right': $sx = $sx - $x - $options["padding_x"]; break;
						case 'center': $sx = ($sx / 2 ) - ($x / 2) + $options["padding_x"]; break;
						default: $sx = $options["x"];break;
					}

					switch($options["y"]){
						case 'top' : $sy = $options["padding_y"]; break;
						case 'bottom': $sy = $sy - $y - $options["padding_y"]; break;
						case 'middle': $sy = ($sy / 2 ) - ($y / 2) + $options["padding_y"]; break;
						default: $sy = $options["y"];break;
					}

					self::imageCopyMergeAlpha($source_image,$watermark_image,$sx,$sy,0,0,$x,$y,$options['opacity']);
				}else if($options["mode"] == "mosaic"){
					$repeat_x = (int)($sx / $x) + 1;
					$repeat_y = (int)($sy / $y) + 1;

					for($i=0;$i<$repeat_x;$i++)
						for($j=0;$j<$repeat_y;$j++)    
							self::imageCopyMergeAlpha(
								$source_image,
								$watermark_image,
								$i * ($x + $options["padding_x"]),
								$j * ($y + $options["padding_y"]),
								0,0,
								$x,$y,
								$options['opacity']
							);
				}

				self::writeImage($source_image,$dest);

				imagedestroy($source_image);
				imagedestroy($watermark_image);
			}

			public static function imageCopyMergeAlpha($dst_im, $src_im, $dst_x, $dst_y, $src_x, $src_y, $src_w, $src_h, $pct)
			{
				$opacity = $pct;
				$w = imagesx($src_im);
				$h = imagesy($src_im);
				$cut = imagecreatetruecolor($src_w, $src_h);
				imagecopy($cut, $dst_im, 0, 0, $dst_x, $dst_y, $src_w, $src_h);
				$opacity = 100 - $opacity;
				imagecopy($cut, $src_im, 0, 0, $src_x, $src_y, $src_w, $src_h);
				imagecopymerge($dst_im, $cut, $dst_x, $dst_y, $src_x, $src_y, $src_w, $src_h, $opacity);
			}

			public static function getCaptcha(&$target)
			{
				function randColor(&$i){
					return imagecolorallocate($i,rand(20,220),rand(20,220),rand(20,220));
				}

				function randPoint($x,$y){
					return array(
						"x" => mt_rand(0,$x),
						"y" => mt_rand(0,$y)
					);
				}
				//-0 -o -1 -l -j
				$alph = "qwertyupasdfghkzxcvbnm23456789";

				$alph_count = strlen($alph) - 1;

				$num = array();
				for($i=0;$i<self::$captcha_length;$i++)
					$num[$i] = $alph[mt_rand(0,$alph_count)];

				$target = implode('',$num);

				$fonts = glob(self::$captcha_fonts . "*.ttf");

				$width  = self::$captcha_length * 32;
				$height = 60;

				$im = imagecreate($width,$height);
				$white = imagecolorallocate ($im, 255, 255, 255);
				
				for($i=0;$i<self::$captcha_length;$i++)
					imagettftext($im, mt_rand(2,4)*10, mt_rand(-2,2)*10, ($i*32) + 5, 40, randColor($im), $fonts[mt_rand(0,count($fonts) - 1)] ,$num[$i]);
			
				imagesetthickness($im, 2);
				for($i=0;$i<5;$i++){
					$start = randPoint($width,$height);
					$end   = randPoint($width,$height);
					$color = randColor($im);
					imageline($im,$start["x"],$start["y"],$end["x"],$end["y"],$color);
				}

				header("Cache-Control: post-check=0, pre-check=0", false);
				header("Pragma: no-cache");
				header("Content-type: image/png");
				imagepng($im);
				imagedestroy($im);
			}

		}
		
		Model_Image::$captcha_length = 6;
		Model_Image::$captcha_fonts = ENGINE_PATH . "/etc/fonts/";
?>