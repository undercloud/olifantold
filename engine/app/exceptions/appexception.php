<?php
	namespace app\exceptions;
		/** class AppException
			@brief Базовый класс исключений приложения
		*/
		class AppException extends \Exception
		{
			public function __construct($message, $code = 0, Exception $previous = null)
			{
	       		parent::__construct($message, $code, $previous);
			}

			public function __toString() {
	        	return __CLASS__ . ": [{$this->code}]: {$this->message}";
	    	}
		}
?>