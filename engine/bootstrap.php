<?php	
	define('DEBUG'   , 0x000);
	define('RELEASE' , 0xFFF);
	define('DEV_MODE', DEBUG); // DEBUG | RELEASE

	if(DEV_MODE === DEBUG)        error_reporting(E_ALL | E_STRICT);
	else if(DEV_MODE === RELEASE) error_reporting(0);

	if((!isset($_SERVER['SERVER_SOFTWARE']) && (php_sapi_name() == 'cli' || (is_numeric($_SERVER['argc']) && $_SERVER['argc'] > 0)))){
		$_SERVER['REQUEST_METHOD'] = 'CLI';
		$_SERVER['REQUEST_URI']    = $_SERVER['argv'][1];

		$pos = strpos($_SERVER['REQUEST_URI'],'?');
		$_SERVER['QUERY_STRING'] = substr($_SERVER['REQUEST_URI'],$pos + 1);

		if(false !== $pos)
			parse_str($_SERVER['QUERY_STRING'],$_REQUEST);

		$_SERVER['DOCUMENT_ROOT'] = __DIR__ . "/../public";
	}

	define('NAMESPACE_SEPARATOR', '\\');
	define('DOCUMENT_ROOT'   , $_SERVER['DOCUMENT_ROOT']);
	define('ENGINE_PATH'     , DOCUMENT_ROOT . "/../engine");
	define('APPLICATION_PATH', ENGINE_PATH   . "/app");
	define('CONTROLLER_PATH' , ENGINE_PATH   . "/controller");
	define('MODEL_PATH'      , ENGINE_PATH   . "/model");
	define('ETC_PATH'        , ENGINE_PATH   . "/etc");
	define('EXTENSION_PATH'  , ENGINE_PATH   . "/extension");
	define('VIEW_PATH'       , ENGINE_PATH   . "/view/default/");

	require_once ENGINE_PATH . '/app/autoload.php';
	spl_autoload_register('\app\Autoload::load');
	
	set_error_handler("\app\utils\ErrUtils::errorHandler");
	set_exception_handler("\app\utils\ErrUtils::showExceptionInfo");

	require_once APPLICATION_PATH . "/conf/include.php";
?>