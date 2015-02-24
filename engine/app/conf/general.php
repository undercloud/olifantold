<?php
	namespace app\conf;

	const TIME_ZONE  = "Europe/Moscow";
	const TIME_LIMIT = 30;
	const BACKTRACE_ERRORS = true;
	const BACKTRACE_EXCEPTIONS = true;

	date_default_timezone_set(TIME_ZONE);
	set_time_limit(TIME_LIMIT);

	if(DEV_MODE == DEBUG)
		$err_mode = 1;
	else if(DEV_MODE == RELEASE)
		$err_mode = 0;

	//access
	ini_set('open_basedir', DOCUMENT_ROOT . '/../');

	//ini
	ini_set("display_errors", $err_mode);
	ini_set("display_startup_errors",$err_mode);
	ini_set('xdebug.default_enable', $err_mode);
	ini_set("log_errors", 1);
	ini_set('zlib.output_compression','On');
	ini_set('zlib.output_compression_level', -1);

	//session
	ini_set('session.gc_divisor',      100);
	ini_set('session.gc_maxlifetime', 40);
	ini_set('session.gc_probability',    100);

	//new \core\utils\Model_SessionHandler();
	//session_set_cookie_params(1623600);
	//session_start();
	//$_SESSION['sasai'] = 'lalka';
?>