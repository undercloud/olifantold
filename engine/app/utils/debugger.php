<?php

	namespace app\utils;

		/** @class Debugger
			@brief Профайлер
			@details Выполняет профилирование с сохранением точек 
			временных меток и количеством используемой памяти
		*/
		class Debugger
		{
			/// @param $instance объект Debugger 
			protected static $instance = null;

			private $stack = null;
			private $time_start = null;

			private function __construct()
			{
				$this->stack = array();
				$this->time_start = microtime(true);

				$this->saveState("begin");
			}

			/** Создает объект Debugger 
				@return объект Debugger
			*/

			public static function getInstance()
			{
				if(null === self::$instance)
					self::$instance = new self();

				return self::$instance;
			}

			/** Сохранение конкретного состояния
				@param $name имя метки состяния
				@param $buffer дополнительная информация (включая типы array и object)
				@return null
			*/

			public function saveState($name,$buffer = false)
			{
				$time_end = microtime(true);

				$this->stack[$name] = array(
					"time"      => round($time_end - $this->time_start,3),
					"timestamp" => microtime(true),
					"memory"    => \core\utils\Model_String::bytes2human(memory_get_usage(),3),
					"buffer"    => $buffer
				);

				$this->time_start = $time_end;
			}

			/** Возврат стека состояний
				@return array стек состояний
			*/

			public function getStack()
			{
				return $this->stack;
			}

			/** Получение точки состояния
				@param $key метка
				@return array запись стека или исключение если метка не найдена
			*/

			public function getPoint($key)
			{
				if(array_key_exists($key,$this->stack))
					return $this->stack[$key];
				else
					throw new \app\exceptions\AppException("Key: ".$key." not exists");
			}

			/** Вывод / возврат содержимого стека
				@param boolean $return вернуть или вывети содержимое
				@return содержимое стека или null
			*/

			public function dump($return = false)
			{
				$echo = "
				<table cellspacing='1' cellpadding='3' style='color:#444;font-family:Tahoma;font-size:13px;margin:5px;'>
					<tr style='background-color: #f1f1f1;font-weight:bold;color:#999;text-align:center;'>
						<td>Point</td>
						<td colspan='2'>Time</td>
						<td>Memory</td>
						<td>Buffer</td>
					</tr>";

				$i = 0;
				foreach ($this->stack as $key => $value){
					$buffer = null;
					if($value['buffer']){
						if(is_object($value['buffer']) or is_array($value['buffer'])){
							$buffer = "<pre>".print_r($value['buffer'],true)."</pre>";
						}else{
							$buffer = $value['buffer'];
						}
					}

					$echo .= "
					<tr bgcolor='".((++$i%2 == 0) ? '#EEEEEE' : '#FFFFFF')."'>
						<td>".$key."</td>
						<td>".$value['timestamp']."</td>
						<td>( + ".$value['time'].")</td>
						<td>".$value['memory']."</td>
						<td>".$buffer."</td>
					</tr>";
				}

				$start = reset($this->stack);
				$end = end($this->stack);

				$all_time = round($end['timestamp'] - $start['timestamp'],3);

				$echo .= "
				<tr bgcolor='".((++$i%2 == 0) ? '#EEEEEE' : '#FFFFFF')."'>
					<td></td>
					<td></td>
					<td>".$all_time."</td>
					<td></td>
					<td></td>
				</tr>";

				$echo .= "</table>";

				if($return)
					return $echo;
				else
					echo $echo;
			}
		}
?>