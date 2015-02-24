<?php
	namespace app\view;
		/** @class Document
			@brief Работа с html документом
		*/
		class Document extends \app\view\Node
		{
			/// @param $instance объект Document
			protected static $instance = null;
			private $dom = null;

			private function __construct(){}

			/** Экземпляр Document
				@return объект Document
			*/
			public static function getInstance()
			{
				if(null === self::$instance){
					self::$instance = new self();
		        }
				return self::$instance;
			}

			/** Загрузка шаблона
				@param $template имя шаблона
				@param $data передаваемые данные
				@return объект Document 
			*/
			public function load($template,array $data = array())
			{
				$this->dom = parent::getInstance()->setup($template,$data);
				return $this;
			}

			/** Установка title страницы
				@param $t title страницы
				@return объект Document
			*/
			public function setTitle($t)
			{
				$this->dom
				->getElementsByTagName('title')
				->item(0)
				->nodeValue = $t;

				return $this;
			}

			/** Добавление meta данных
				@param $info массив данных ключ => значение
				@return объект Document
			*/
			public function addMeta(array $info)
			{
				$meta = new \DOMElement('meta');				

				$this->dom
				->getElementsByTagName('head')
				->item(0)
				->appendChild($meta);

				foreach($info as $key=>$value){
					$meta->setAttribute($key,$value);
				}

				return $this;
			}

			/** Добавление js скрипта
				@param $path путь до js скрипта
				@return объект Document
			*/
			public function addScript($path)
			{
				$script = new \DOMElement('script');				

				$this->dom
				->getElementsByTagName('head')
				->item(0)
				->appendChild($script);

				$script->setAttribute("type","text/javascript");
				$script->setAttribute("src",$path);

				return $this;
			}

			/** Добавление css стиля
				@param $path путь до css скрипта
				@return объект Document
			*/
			public function addStyle($path)
			{
				$style = new \DOMElement('link');

				$this->dom
				->getElementsByTagName('head')
				->item(0)
				->appendChild($style);

				$style->setAttribute("rel","stylesheet");
				$style->setAttribute("type","text/css");
				$style->setAttribute("href",$path);

				return $this;
			}

			/** Получение ссылки 
				@return объект DOMDocument
			*/
			public function getDom()
			{
				return $this->dom;
			}

			/** Преобразование шаблона в текстовое представление
				@return содержимое шаблона
			*/
			public function render()
			{
				$mode = $this->outputMode();

				if($mode == "html")
					$doc = $this->getDom()->saveHTML($this->getDom()->documentElement);
				else if($mode == "xml")
					$doc = $this->getDom()->saveXML($this->getDom()->documentElement);
				else 
					throw new \app\exceptions\AppException("Undefined output mode " . $mode);
				
				return ($this->getDom()->doctype != null ? $this->getDom()->doctype->internalSubset : '') . html_entity_decode($doc,ENT_QUOTES, 'UTF-8');
			}
		}

?>