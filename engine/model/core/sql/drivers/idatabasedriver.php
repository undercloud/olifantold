<?php
	namespace core\sql\drivers;

		interface IDatabaseDriver
		{
			const FETCH_NUM    = 0;
			const FETCH_ASSOC  = 1;
			const FETCH_OBJECT = 2;

			public function getHandle();
			public function selectDatabase($dbname);
			public function escapeField($string);
			public function escape($string);
			public function query($sql);
			public function lastQuery();
			public function errorCode();
			public function errorInfo();
			public function lastInsertId();
			public function affectedRows();
			public function countRows();
			public function countFields();
			public function setFetchMode($mode);
			public function fetch();
			public function fetchAll($key);
			public function totalRows();
			public function begin();
			public function commit();
			public function rollback();
		}
?>