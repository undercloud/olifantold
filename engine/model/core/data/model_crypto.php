<?php
	namespace core\data;

		class Model_Crypto
		{
			public static function reversibleEncryption($data,$key)
			{
				$salt = 'BGuxLWQtKweKEMV4'; 
			    $strlen = strlen($data);
			    $gamma = '';
			    while (strlen($gamma)<$strlen){
			        $key = pack("H*",sha1($gamma.$key.$salt)); 
			        $gamma .= substr($key,0,8);
			    }
			    return $data^$gamma;
			}

			public static function genPass($length)
			{
				$abc = array(
					'q','w','e','r','t','y','u','i','o','p',
					'a','s','d','f','g','h','j','k','l',
					'z','x','c','v','b','n','m',
					'Q','W','E','R','T','Y','U','I','O','P',
					'A','S','D','F','G','H','J','K','L',
					'Z','X','C','V','B','N','M',
					'0','1','2','3','4','5','6','7','8','9'
				);

				shuffle($abc);
				$abs_length = count($abc) - 1;

				$length = (int)$length;

				$pass = "";

				for($i = 0; $i < $length; $i++){
					$index = rand(0,$abs_length);
					$pass .= $abc[$index];
				}

				return $pass;
			}
		}
?>