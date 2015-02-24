<?php
	\app\conf\DBConnector::getInstance()->push(
		array(
			"sqlite" => array(
				"default" => array(
					"database" => "/sample.sqlite3",
					"flags" => SQLITE3_OPEN_READWRITE | SQLITE3_OPEN_CREATE
				)
			)
		)
	);
?>