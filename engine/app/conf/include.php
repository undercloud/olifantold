<?php
	$configs = array(
		"general",
		"upgrade",
		"url",
		"dbconnector",
		"mysql"
	);

	foreach($configs as $config)
		require_once __DIR__ . "/{$config}.php";
?>