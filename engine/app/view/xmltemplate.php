<?php
	namespace app\view;

		/** @class XMLTemplate
			@brief Базовый класс шаблонизатор
			@details Выполняет базовые операции по загрузке и обработке шаблонов
		*/

		class XMLTemplate
		{
			/// расширение файлов-шаблонов
			const TEMPLATE_EXTENSION = "html";

			/// @param $instance объект XMLTemplate
			protected static $instance = null;
			/// @param $input_mode входной формат данных
			protected $input_mode  = "html";
			/// @param $output_mode выходной формат данных
			protected $output_mode = "html";

			private function __construct()
			{
				libxml_use_internal_errors(true);
			}

			/** Создание экземпляра XMLTemplate
				@return объект XMLTemplate
			*/
			protected static function getInstance()
			{
				if(null === self::$instance){
					self::$instance = new self();
		        }
				return self::$instance;
			}

			/** Получение / установка входного формата данных
				@param $mode формат данных xml или html
				@return имя формата данных или null
			*/
			public function inputMode($mode = null)
			{
				if($mode === null)
					return $this->input_mode;
				else
					$this->input_mode = $mode;
			}

			/** Получение / установка выходного формата данных
				@param $mode формат данных xml или html
				@return имя формата данных или null
			*/
			public function outputMode($mode = null)
			{
				if(null === $mode)
					return $this->output_mode;
				else
					$this->output_mode = $mode;
			}

			/** Разбор шаблона
				@param $template имя шаблона
				@param $data передаваемые данные
				@return разобранный шаблон
			*/
			private function prepare($template,array $data = array())
			{
				$full_path = VIEW_PATH . $template . "." .self::TEMPLATE_EXTENSION;
				ob_start();
				if($data)	
					extract($data);

				require($full_path);
				return ob_get_clean();
			}

			/** Проверка ошибок шаблона
				@param $xml содержимое шаблона
				@return null
			*/
			private function errorCheck($xml)
			{
				$xmlErrors = libxml_get_errors();
				if($xmlErrors){
					$lines = explode(PHP_EOL,$xml);

					$exc = $xmlErrors[0]->message;
					$exc .= htmlentities(trim($lines[$xmlErrors[0]->line - 1]),ENT_QUOTES,'UTF-8');
					libxml_clear_errors();
					throw new \app\exceptions\AppException($exc);
				}
			}

			/** Основной метод загрузки и обработки шаблона
				@param $template имя шаблона
				@param $data передаваемые данные
				@param $is_fragment false - документ, true - фрагмент
				@return объект DOMDocument
			*/
			public function setup($template,array $data,$is_fragment = false)
			{
				return $this->parse($this->prepare($template,$data),$is_fragment);
			}

			/** Приведение шаблона к типу DOMDocument
				@param $prepared обработанный шаблон
				@param $is_fragment false - документ, true - фрагмент
				@return объект DOMDocument
			*/
			public function parse($prepared,$is_fragment = false)
			{
				if($is_fragment === true){
					if($this->inputMode() == "html")
						$prepared = '<!DOCTYPE html><html><head><meta http-equiv="content-type" content="text/html; charset=UTF-8" /></head><body>'.PHP_EOL.$prepared.PHP_EOL.'</body></html>';
					else if($this->inputMode() == "xml")
						$prepared = '<?xml version="1.0" encoding="UTF-8"?><root>'.PHP_EOL.$prepared.PHP_EOL.'</root>';
				}

				$dom = new \DOMDocument();
				$dom->strictErrorChecking = false;
				$dom->preserveWhiteSpace = false;
				$dom->formatOutput = false;

				if($this->inputMode() == "html")
					$dom->loadHTML($prepared);
				else if($this->inputMode() == "xml")
					$dom->loadXML($prepared);
				else
					throw new \app\exceptions\AppException("Undefined input mode {$this->inputMode()}");

				//$this->errorCheck($prepared);

				return $dom;
			}
		}
?>