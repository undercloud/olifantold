<?php
	namespace app\utils;
	
		/** @class ErrUtils
			@brief Управление ошибками
			@details Содержит callback методы для автоматического перехвата ошибок и исключений 
		*/

		class ErrUtils
		{
			/** Перехватчик ошибок
				@param $errno код ошибки
				@param $errstr строка ошибки
				@param $errfile файл
				@param $errline строка файла
				@return null
			*/
			public static function errorHandler($errno, $errstr, $errfile, $errline)
			{
			    if(!(error_reporting() & $errno)){
			        return;
			    }

			    $errors = array(
			    	1     => 'E_ERROR',
					2     => 'E_WARNING',
					4     => 'E_PARSE',
					8     => 'E_NOTICE',
					16    => 'E_CORE_ERROR',
					32    => 'E_CORE_WARNING',
					64    => 'E_COMPILE_ERROR',
					128   => 'E_COMPILE_WARNING',
					256   => 'E_USER_ERROR',
					512   => 'E_USER_WARNING',
					1024  => 'E_USER_NOTICE',
					2048  => 'E_STRICT',
					4096  => 'E_RECOVERABLE_ERROR',
					8192  => 'E_DEPRECATED',
					16384 => 'E_USER_DEPRECATED',
					30719 => 'E_ALL'
			    );

			    $echo = "
					<style type='text/css'>
						.exception-info{
							font-family: Tahoma;
							font-size: 12px;
							color: #222;
						}
					</style>
				";

				$echo .= "
				<table class='exception-info' cellpadding='3' cellspacing='3'>
					<tr bgcolor='#F4858F'>
						<td colspan='2'>{$errors[$errno]}</td>	
					</tr>
					<tr bgcolor='#F4858F'>
						<td>ERR_STR</td>
						<td>".htmlentities($errstr,ENT_QUOTES,'UTF-8')."</td>
					</tr>
					<tr bgcolor='#F4858F'>
						<td>ERR_FILE</td>
						<td>{$errfile}</td>
					</tr>
					<tr bgcolor='#F4858F'>
						<td>ERR_LINE</td>
						<td>{$errline}</td>
					</tr>";

				if(\app\conf\BACKTRACE_ERRORS === true)
					foreach(debug_backtrace() as $trace){
						$echo .= "<tr><td colspan='2'></td></tr>";
						foreach($trace as $k=>$v){
							$echo .= "
							<tr bgcolor='#ADC972'>
								<td>$k:</td>
								<td>" . ((is_array($v) or is_object($v))? "<pre>".htmlentities(print_r($v,true),ENT_QUOTES,'UTF-8')."</pre>" : $v) . "</td>
							</tr>";
						}
					}
				$echo .= "</table>";
				echo $echo;
				
				return true;
			}

			/** Перехватчик исключений
				@param $e объект Exception
				@return null
			*/

			public static function showExceptionInfo($e)
			{
				$echo = "
					<style type='text/css'>
						.exception-info{
							font-family: Tahoma;
							font-size: 12px;
							color: #222;
						}
					</style>
				";

				$echo .= "<table class='exception-info' cellspacing='3' cellpadding='3'>";
				$echo .= "
				<tr bgcolor='#F4858F'>
					<td colspan='2'>Exception: ".$e->getMessage()."</td>
				</tr>";

				$echo .= "
					<tr bgcolor='#F4858F'><td>File:</td><td>{$e->getFile()}</td></tr>
					<tr bgcolor='#F4858F'><td>Line:</td><td>{$e->getLine()}</td></tr>
				"; 

				if(\app\conf\BACKTRACE_EXCEPTIONS === true)
					foreach($e->getTrace() as $trace){
						$echo .= "<tr><td colspan='2'></td></tr>";
						foreach($trace as $k=>$v){
							$echo .= "
							<tr bgcolor='#ADC972'>
								<td>$k:</td>
								<td>".((is_array($v) or is_object($v)) ? "<pre>".htmlentities(print_r($v,true),ENT_QUOTES,'UTF-8')."</pre>" : $v)."</td>
							</tr>";
						}
					}
				$echo .= "</table>";

				echo $echo;
			} 
		}
?>