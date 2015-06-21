<?php
	namespace app;

	class Request
	{
		private $request_uri = null;
		private $map_key     = null;

		public function __construct($request)
		{
			$request = trim($request,' /');
			$pos = strpos($request,'?');
			if($pos !== false)
				$request = substr($request,0,$pos);

			$this->request_uri = $request;
		}

		public function getURI()
		{
			return $this->request_uri;
		}

		/*public static function isPostOverflow()
		{
			return ($_SERVER['REQUEST_METHOD'] == 'POST' && 
				empty($_POST) &&
 				empty($_FILES) && 
 				$_SERVER['CONTENT_LENGTH'] > 0);
		}*/

		public function excludeMapKey($mapkey)
		{
			$this->mapkey      = $mapkey;
			$this->request_uri = str_replace($mapkey,'', $this->request_uri);

			return $this;
		}

		public function parse()
		{
			$splitted = explode('/',$this->request_uri);
			return array_filter($splitted,function($v){
				return ($v != '');
			}); 
		}

		public function prepareGlobalVars()
		{
			if(function_exists('get_magic_quotes_gpc') and get_magic_quotes_gpc()){
				function stripslashes_gpc(&$value){
					$value = stripslashes($value);
				}

				array_walk_recursive($_GET, 'stripslashes_gpc');
				array_walk_recursive($_POST, 'stripslashes_gpc');
				array_walk_recursive($_COOKIE, 'stripslashes_gpc');
				array_walk_recursive($_REQUEST, 'stripslashes_gpc');
			}

			return $this;
		}

		public function reorderFiles()
		{
			$reorder = array();
			$files   = $_FILES;

			foreach($files as $key => $all){
				if(is_array($all['name'])){
					foreach($all as $i => $val){
						$reorder[$i][$key] = $val;    
					}
				}else{
					$reorder[$key] = $all;
				}
			}

			$_FILES = $reorder;

			return $this;
		}

		public function build()
		{
			$req = new \stdClass();

			$req->ajax        = (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) and strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest');
			$req->params      = $this->parse();
			$req->originalUrl = $_SERVER['REQUEST_URI']; 
			$req->mapkey      = $this->mapkey;

			$map = array(
				'GET'  => $_GET,
				'POST' => $_POST,
				'CLI'  => $_REQUEST
			);

			$req->query = $map[$_SERVER['REQUEST_METHOD']];

			$req->cookies  = \core\utils\Model_CookieHelper::getReader();

			$req->files    = $_FILES;
			$req->method   = $_SERVER['REQUEST_METHOD'];
			$req->protocol = $_SERVER['SERVER_PROTOCOL'];
			$req->port     = $_SERVER['SERVER_PORT'];
			$req->host     = $_SERVER['HTTP_HOST'];
			$req->secure   = (isset($_SERVER['HTTPS']) and $_SERVER['HTTPS'] == 'on');

			if(false == function_exists('getallheaders')){
				$req->header = array();
				$use_header  = array('CONTENT_TYPE','CONTENT_LENGTH');
				foreach($_SERVER as $name => $value){
					if(strtolower(substr($name, 0, 5)) == 'http_'){
						$req->header[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
					}

					if(in_array($name,$use_header)){
						$req->header[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', $name))))] = $value;
					}
				}
			}else{
				$req->header = getallheaders();
			}

			if(isset($_SERVER['CONTENT_TYPE']) and 0 === strpos($_SERVER['CONTENT_TYPE'],'application/json')){
				$req->json = json_decode(file_get_contents('php://input'),true);
			}

			if(isset($_SERVER['HTTP_REFERER'])){
				$req->referer = $_SERVER['HTTP_REFERER'];
			}

			$req->client = new \core\utils\Model_UserStatistics();

			return $req;
		}
	}
?>