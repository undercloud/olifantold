<?php
	namespace app\view;
		/** @class Node
			@brief Работа с фрагментом документа
		*/
		class Node extends \app\view\XMLTemplate
		{
			private $dom = null;

			public function __construct(){}

			/** Загрузка шаблона
				@param $template имя шаблона
				@param $data передаваемые данные
				@return объект Node 
			*/
			public function load($template,array $data = array())
			{
				$this->dom = parent::getInstance()->setup($template,$data,true);
				return $this;
			}

			/** Загрузка из строки
				@param $xmlstring шаблон в виде строки
				@return объект Node 
			*/
			public function fromString($xmlstring)
			{
				$this->dom = parent::getInstance()->parse($xmlstring,true);
				return $this;
			}

			/** Установка переменной в шаблон
				@param $selector элемент с аттрибутом class или id,
				например .classname, \#idname или tagname
				@param $xmlnode фрагмент шаблона 
				@return объект Node 
			*/
			public function assign($selector,\app\view\XMLTemplate $xmlnode)
			{
				if($selector[0] === '.')
					$query = "//*[contains(concat(' ', normalize-space(@class), ' '), ' ".substr($selector,1)." ')]";
				else if($selector[0] === '#')	
					$query = ".//*[@id='".substr($selector,1)."']";
				else if(ctype_alpha($selector[0]))
					$query = "//" . $selector;
				else
					throw new \app\exceptions\AppException("Undefined selector {$selector}");

				$xpath = new \DOMXPath($this->getDom());
				$nodes = $xpath->query($query);

				if($nodes->length == null)
					throw new \app\exceptions\AppException("Element {$selector} not found");

				$mode = $this->outputMode();
				if($mode == "html")
					$root = "body";
				else if($mode == "xml")
					$root = "root";

				$body = $xmlnode->getDom()->getElementsByTagName($root)->item(0);
				foreach($nodes as $node){
					foreach($body->childNodes as $child){
					    $newnode = $this->getDom()->importNode($child,true);
					    $node->appendChild($newnode);
					}
				}
								
				return $this;
			}

			/** Установка переменных из массива (.classname|\#idname|tagname) => XMLTemplate
				@return объект Node 
			*/
			public function assignArray(array $selectors)
			{
				foreach($selectors as $k => $v){
					$this->assign($k,$v);
				}

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
				if($mode == "html"){
					$method = "saveHTML";
					$root   = "body";
				}else if($mode == "xml"){
					$method = "saveXML";
					$root   = "root";
				}else
					throw new \app\exceptions\AppException("Undefined output mode " .$mode);

				$innerHTML = "";
				foreach($this->getDom()->getElementsByTagName($root)->item(0)->childNodes as $child)
			        $innerHTML .= $this->getDom()->$method($child);

				return html_entity_decode($innerHTML,ENT_QUOTES,'UTF-8');
			}
		}
?>