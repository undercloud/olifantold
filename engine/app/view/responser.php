<?php
	namespace app\view;
	/** class Responser
		@brief Отправка данных клиенту 
	*/
	class Responser
	{
		public function __construct(){}
		
		protected static function send($data)
		{
			echo $data;
		}

		public static function sendHtml($data)
		{
			return self::send($data);
		}

		public static function sendXHtml($data)
		{
			header('Content-Type: application/xhtml+xml; charset=utf-8');
			return self::send($data);
		}

		public static function sendXml($data)
		{
			header('Content-Type: text/xml; charset=utf-8');
			return self::send($data);
		}

		public static function sendJson($data)
		{
			header('Content-Type: application/json; charset=utf-8');
			
			$encoded = json_encode(
				$data,
				JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_FORCE_OBJECT
			);

			$last_error = json_last_error();

			if($last_error == JSON_ERROR_NONE)
				return self::send($encoded);
			else
				throw new \app\exceptions\AppException("Malformed JSON {$last_error}");
		}
	}
?>