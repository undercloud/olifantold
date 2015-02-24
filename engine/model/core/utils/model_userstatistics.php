<?php
	namespace core\utils;
	
		class Model_UserStatistics
		{
			public static function getRemoteIP()
			{
				if (!empty($_SERVER['HTTP_CLIENT_IP'])){
	        		return $_SERVER['HTTP_CLIENT_IP'];
	    		}else if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
	        		return $_SERVER['HTTP_X_FORWARDED_FOR'];
	        	}else{
	        		return $_SERVER['REMOTE_ADDR'];
	        	}
			} 

			public static function getReferer()
			{
				return isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : false;
			}

			public static function getOS($userAgent) 
			{
				$oses = array (
					'iPhone' => '(iPhone)',
					"Android" => "android",
					'Windows 3.11' => 'Win16',
					'Windows 95' => '(Windows 95)|(Win95)|(Windows_95)',
					'Windows 98' => '(Windows 98)|(Win98)',
					'Windows 2000' => '(Windows NT 5.0)|(Windows 2000)',
					'Windows XP' => '(Windows NT 5.1)|(Windows XP)',
					'Windows 2003' => '(Windows NT 5.2)',
					'Windows Vista' => '(Windows NT 6.0)|(Windows Vista)',
					'Windows 7' => '(Windows NT 6.1)|(Windows 7)',
					'Windows NT 4.0' => '(Windows NT 4.0)|(WinNT4.0)|(WinNT)|(Windows NT)',
					'Windows ME' => 'Windows ME',
					'Open BSD'=>'OpenBSD',
					'Sun OS'=>'SunOS',
					'Linux'=>'(Linux)|(X11)',
					'Safari' => '(Safari)',
					'Macintosh'=>'(Mac_PowerPC)|(Macintosh)',
					'QNX'=>'QNX',
					'BeOS'=>'BeOS',
					'OS/2'=>'OS/2',
					'Search Bot'=>'(nuhk)|(Googlebot)|(Yammybot)|(Openbot)|(Slurp/cat)|(msnbot)|(ia_archiver)'
				);

				foreach($oses as $os=>$pattern){
					if(@preg_match("/".$pattern."/", $userAgent)) {
						return $os;
					}
				}

				return 'Unknown';
			}

			public static function getBrowser($agent)
			{
		        preg_match("/(MSIE|Opera|Firefox|Chrome|Version|Opera Mini|Netscape|Konqueror|SeaMonkey|Camino|Minefield|Iceweasel|K-Meleon|Maxthon)(?:\/| )([0-9.]+)/", $agent, $browser_info);
		        list(,$browser,$version) = $browser_info;
		        if (preg_match("/Opera ([0-9.]+)/i", $agent, $opera)) return 'Opera '.$opera[1];
		        if ($browser == 'MSIE') { 
		            preg_match("/(Maxthon|Avant Browser|MyIE2)/i", $agent, $ie); 
		            if ($ie) return $ie[1].' based on IE '.$version; 
		               return 'IE '.$version; 
		        }
		        if ($browser == 'Firefox'){
		            preg_match("/(Flock|Navigator|Epiphany)\/([0-9.]+)/", $agent, $ff);
		            if ($ff) return $ff[1].' '.$ff[2]; 
		        }
		        if ($browser == 'Opera' && $version == '9.80') return 'Opera '.substr($agent,-5);
		        if ($browser == 'Version') return 'Safari '.$version;
		        if (!$browser && strpos($agent, 'Gecko')) return 'Browser based on Gecko'; 

		        return trim($browser).' '.trim($version);
			}
		}
?>