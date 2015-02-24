<?php
	namespace core\drive;

		class Model_File
		{
			public static function download($filename,$buffer_size = 1024) 
			{
				set_time_limit(0);

			 	$mimetype='application/octet-stream';
			  
			 	if(file_exists($filename)){
					header ($_SERVER["SERVER_PROTOCOL"] . ' 200 OK');
					header ('Content-Type: ' . $mimetype);
					header ('Last-Modified: ' . gmdate ('r', filemtime ($filename)));
			    	header ('ETag: ' . sprintf ('%x-%x-%x', fileinode ($filename), filesize ($filename), filemtime ($filename)));
			    	header ('Content-Length: ' . (filesize ($filename)));
			    	header ('Connection: close');
			    	header ('Content-Disposition: attachment; filename="' . basename ($filename) . '";');

			    	$handle = fopen($filename,'r');
			    	while (!feof ($handle)) {
			      		echo fread ($handle, $buffer_size);
			      		flush ();
			    	}
			    	fclose ($handle);
				}else{
					header($_SERVER["SERVER_PROTOCOL"] . ' 404 Not Found');
	    			header('Status: 404 Not Found');
				}
			}

			public static function getMIME($filename,$mode=0)
			{
			    // mode 0 = full check
			    // mode 1 = extension check only

		    	$mime_types = array(

			        'txt' => 'text/plain',
			        'htm' => 'text/html',
			        'html' => 'text/html',
			        'php' => 'text/html',
			        'css' => 'text/css',
			        'js' => 'application/javascript',
			        'json' => 'application/json',
			        'xml' => 'application/xml',
			        'swf' => 'application/x-shockwave-flash',
			        'flv' => 'video/x-flv',

			        // images
			        'png' => 'image/png',
			        'jpe' => 'image/jpeg',
			        'jpeg' => 'image/jpeg',
			        'jpg' => 'image/jpeg',
			        'gif' => 'image/gif',
			        'bmp' => 'image/bmp',
			        'ico' => 'image/vnd.microsoft.icon',
			        'tiff' => 'image/tiff',
			        'tif' => 'image/tiff',
			        'svg' => 'image/svg+xml',
			        'svgz' => 'image/svg+xml',

			        // archives
			        'zip' => 'application/zip',
			        'rar' => 'application/x-rar-compressed',
			        'exe' => 'application/x-msdownload',
			        'msi' => 'application/x-msdownload',
			        'cab' => 'application/vnd.ms-cab-compressed',

			        // audio/video
			        'mp3' => 'audio/mpeg',
			        'qt' => 'video/quicktime',
			        'mov' => 'video/quicktime',

			        // adobe
			        'pdf' => 'application/pdf',
			        'psd' => 'image/vnd.adobe.photoshop',
			        'ai' => 'application/postscript',
			        'eps' => 'application/postscript',
			        'ps' => 'application/postscript',

			        // ms office
			        'doc' => 'application/msword',
			        'rtf' => 'application/rtf',
			        'xls' => 'application/vnd.ms-excel',
			        'ppt' => 'application/vnd.ms-powerpoint',
			        'docx' => 'application/msword',
			        'xlsx' => 'application/vnd.ms-excel',
			        'pptx' => 'application/vnd.ms-powerpoint',

			        // open office
			        'odt' => 'application/vnd.oasis.opendocument.text',
			        'ods' => 'application/vnd.oasis.opendocument.spreadsheet',
		        );

		    	$ext = strtolower(self::extension($filename));

				if(function_exists('mime_content_type')&&$mode==0){
					$mimetype = mime_content_type($filename);
				    return $mimetype;
				}

			    if(function_exists('finfo_open')&&$mode==0){
			        $finfo = finfo_open(FILEINFO_MIME);
			        $mimetype = finfo_file($finfo, $filename);
			        finfo_close($finfo);
			        return $mimetype;
			    }else if(array_key_exists($ext, $mime_types)){
				    return $mime_types[$ext];
				}else{
				    return 'application/octet-stream';
				}
			}
		}
?>