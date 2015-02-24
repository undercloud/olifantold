<?php
	namespace core\utils;

		class Model_String
		{
			public static function checkEmail($email)
			{
				$pattern = "/^([a-z0-9_-]+\.)*[a-z0-9_-]+@[a-z0-9_-]+(\.[a-z0-9_-]+)*\.[a-z]{2,4}$/";
				return preg_match($pattern, $email);
			}

			public static function checkURL($url)
			{
				$pattern = "/^((https?|ftp)\:\/\/)?([a-z0-9]{1})((\.[a-z0-9-])|([a-z0-9-]))*\.([a-z]{2,4})(\/?)$/";
				return preg_match($pattern, $url);
			}

			public static function limitString($str,$limit,$postfix="")
			{
				$limit = int($limit);
				$encoding = mb_detect_encoding($str);
				if(mb_strlen($str,$encoding) > $limit){
					return mb_substr($str,0,$limit,$encoding) . $postfix;
				}else{
					return $str;
				}
			}

			public static function textToHyperLink($text)
			{
				$text = html_entity_decode($text);
	        	$text = " ".$text;
	        	$text = eregi_replace('(((f|ht){1}tp://)[-a-zA-Z0-9@:%_\+.~#?&//=]+)','<a href="\\1" target=_blank>\\1</a>', $text);
	        	$text = eregi_replace('(((f|ht){1}tps://)[-a-zA-Z0-9@:%_\+.~#?&//=]+)','<a href="\\1" target=_blank>\\1</a>', $text);
	        	$text = eregi_replace('([[:space:]()[{}])(www.[-a-zA-Z0-9@:%_\+.~#?&//=]+)','\\1<a href="http://\\2" target=_blank>\\2</a>', $text);
	        	$text = eregi_replace('([_\.0-9a-z-]+@([0-9a-z][0-9a-z-]+\.)+[a-z]{2,3})','<a href="mailto:\\1" target=_blank>\\1</a>', $text);
	        	return $text;
			}

			public static function bytes2human($size,$precision = 2) 
			{
			    $units = array('B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');
			    foreach ($units as $unit) {
			        if ($size >= 1024 && $unit != 'YB') {
			            $size = ($size / 1024);
			        } else {
			            return round($size, $precision) . " " . $unit;
			        }
			    }
			}

			public static function changeKeyboard($string)
			{
				$abc = array(
					"q" => "", "w" => "", "e" => "", "r" => "", "t" => "", "y" => "",
					"u" => "", "i" => "", "o" => "", "p" => "", "a" => "", "s" => "",
					"d" => "", "f" => "", "g" => "", "h" => "", "j" => "", "k" => "",
					"l" => "", "z" => "", "x" => "", "c" => "", "v" => "", "b" => "",
					"n" => "", "m" => ""

				);
			}

			public static function rus2Translit($string)
			{
			    $converter = array(
			        'а' => 'a',   'б' => 'b',   'в' => 'v',
			        'г' => 'g',   'д' => 'd',   'е' => 'e',
			        'ё' => 'e',   'ж' => 'zh',  'з' => 'z',
			        'и' => 'i',   'й' => 'y',   'к' => 'k',
			        'л' => 'l',   'м' => 'm',   'н' => 'n',
			        'о' => 'o',   'п' => 'p',   'р' => 'r',
			        'с' => 's',   'т' => 't',   'у' => 'u',
			        'ф' => 'f',   'х' => 'h',   'ц' => 'c',
			        'ч' => 'ch',  'ш' => 'sh',  'щ' => 'sch',
			        'ь' => "'",   'ы' => 'y',   'ъ' => "'",
			        'э' => 'e',   'ю' => 'yu',  'я' => 'ya',
			 
			        'А' => 'A',   'Б' => 'B',   'В' => 'V',
			        'Г' => 'G',   'Д' => 'D',   'Е' => 'E',
			        'Ё' => 'E',   'Ж' => 'Zh',  'З' => 'Z',
			        'И' => 'I',   'Й' => 'Y',   'К' => 'K',
			        'Л' => 'L',   'М' => 'M',   'Н' => 'N',
			        'О' => 'O',   'П' => 'P',   'Р' => 'R',
			        'С' => 'S',   'Т' => 'T',   'У' => 'U',
			        'Ф' => 'F',   'Х' => 'H',   'Ц' => 'C',
			        'Ч' => 'Ch',  'Ш' => 'Sh',  'Щ' => 'Sch',
			        'Ь' => "'",   'Ы' => 'Y',   'Ъ' => "'",
			        'Э' => 'E',   'Ю' => 'Yu',  'Я' => 'Ya',
			    );

			    return strtr($string, $converter);
			}
		}
?>