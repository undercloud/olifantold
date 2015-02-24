<?php
	\app\conf\DBConnector::getInstance()->push(
		array(
			"mysql" => array(
				"default" => array(
					"host" => "localhost",
					"user" => "root",
					"pass" => "root",
					"database" => "sample",
					"charset" => "utf8" 
				)
			)
		)
	);
?>