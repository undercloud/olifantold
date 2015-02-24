<?php
	//check version
	/*
	if(defined('PHP_VERSION_ID') == false){
		$version = explode('.', PHP_VERSION);
		define('PHP_VERSION_ID', ($version[0] * 10000 + $version[1] * 100 + $version[2]));
	}

	define('PHP_VERSION_REQUIRE', 50300);
	if(PHP_VERSION_ID < PHP_VERSION_REQUIRE)
		throw new Exception("PHP version requre " . PHP_VERSION_REQUIRE . " or higher");
	*/
	
	define('DEBUG'   , 0x000);
	define('RELEASE' , 0xFFF);
	define('DEV_MODE', DEBUG); // DEBUG | RELEASE

	if(DEV_MODE === DEBUG)        error_reporting(E_ALL | E_STRICT);
	else if(DEV_MODE === RELEASE) error_reporting(0);

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

	\app\Application::getInstance()->init(/*function(){}*/)->run();
?>