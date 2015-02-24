<?php
	namespace core\sql\drivers;

		class Model_SQLite3 implements IDatabaseDriver
		{
			protected $handle = null;
			protected $result = null;
			protected $fetch_mode = self::FETCH_ASSOC;

			protected $last_query = null;

			private $database = null; 
			private $flags    = null;

			public function __construct($params)
			{
				$this->flags = $params['flags'];
			}

			public function selectDatabase($dbname)
			{
				$this->handle = new \SQLite3(
					$dbname,
					$this->flags
				);
			}

			public function getHandle()
			{
				return $this->handle;
			}

			public function escapeField($string)
			{
				$string = explode('.',$string);
				foreach($string as &$item)
					if($item != '*')
						$item = "`{$item}`";

				return implode('.',$string);
			}

			public function escape($str)
			{
				return $this->handle->escapeString($str);
			}

			public function query($sql)
			{
				$sql = trim($sql);
				$this->last_query = $sql;

				$this->result = $this->handle->query($sql);

				if($this->errorCode() != 0)
	                throw new \core\sql\DBException(
	                    $this->errorCode()." ".
	                    $this->errorInfo()." ".
	                    $sql
	                );
			}

			public function lastQuery()
			{
				return $this->last_query;
			}

			public function countRows()
			{
				if(!$this->result)
					return 0;

				$count = 0;
				while($row = $this->result->fetchArray(SQLITE3_NUM))
					$count++;
				$this->result->reset();

				return $count;
			}

			public function affectedRows()
			{
				return $this->handle->changes();
			}

			public function countFields()
			{
				return $this->result->numColumns();
			}

			public function setFetchMode($mode)
			{
				$this->fetch_mode = $mode;
			}

			public function fetch()
			{
				switch($this->fetch_mode){
					default:
						throw new \core\sql\DBException('Unknown fetch mode ' . $this->fetch_mode);
					break;

					case self::FETCH_ASSOC:
						$row = $this->result->fetchArray(SQLITE3_ASSOC);
					break;

					case self::FETCH_NUM:
						$row = $this->result->fetchArray(SQLITE3_NUM);
					break;

					case self::FETCH_OBJECT:
						$row = (object)$this->result->fetchArray(SQLITE3_ASSOC);
					break;
				}

				if(!$row)
					if($this->result)
						$this->result->finalize();
				
				return $row;
			}

			public function fetchAll($key = false)
			{
				switch($this->fetch_mode){
					default:
						throw new \core\sql\DBException('Unknown fetch mode ' . $this->fetch_mode);
					break;

					case self::FETCH_ASSOC:
						return $this->allToAssoc($key = false);
					break;

					case self::FETCH_NUM:
						return $this->allToArray();
					break;

					case self::FETCH_OBJECT:
						return $this->allToObject($key = false);
					break;
				}
			}

			private function allToArray()
			{
				$array = array();
				if($this->result){
					while($row = $this->result->fetchArray(SQLITE3_NUM)){
						foreach($row as $k=>&$v)
							if(trim($v)==='')
								$v = null;

						$array[] = $row;
					}
				}

				if($this->result)
					$this->result->finalize();
				
				return $array;
			}

			private function allToAssoc($key = false)
			{
				$assoc = array();
				if($this->result){
					if($key !== false){
						while($row = $this->result->fetchArray(SQLITE3_ASSOC)){
							foreach($row as $k=>&$v)
								if(trim($v)==='')
									$v = null;

							$assoc[$row[$key]] = $result;
						}
					}else{
						while($row = $this->result->fetchArray(SQLITE3_ASSOC)){
							foreach($row as $k=>&$v)
								if(trim($v)==='')
									$v = null;

							$assoc[] = $row;
						}
					}
				}

				if($this->result)
					$this->result->finalize();
				
				return $assoc;
			}

			private function allToObject($key = false)
			{
				$objects = array();
				if($this->result){
					if($key !== false){
						while($row = $this->result->fetchArray(SQLITE3_ASSOC)){
							foreach($row as $k=>&$v)
								if(trim($v)==='')
									$v = null;

							$objects[$row[$key]] = (object)$row;
						}
					}else{
						while($row = $this->result->fetchArray(SQLITE3_ASSOC)){
							foreach($row as $k=>&$v)
								if(trim($v)==='')
									$v = null;

							$objects[] = (object)$row;
						}
					}
				}

				if($this->result)
					$this->result->finalize();
				
				return $objects;
			}

			public function errorCode()
			{
				return $this->handle->lastErrorCode();
			}

			public function errorInfo()
			{
				return $this->handle->lastErrorMsg();
			}

			public function lastInsertId()
			{
				return $this->handle->lastInsertRowID();
			}

			public function totalRows()
			{
				$this->last_query = substr($this->last_query,0,strripos($this->last_query,"LIMIT"));
				$this->query(preg_replace("/SELECT.*?FROM/ims", "SELECT COUNT(*) AS total FROM", $this->last_query,1));
				$fetched = $this->fetch();
				
				switch($this->fetch_mode){
					case self::FETCH_ASSOC:
						return (int)$fetched['total'];
					case self::FETCH_NUM: 
						return (int)$fetched[0];
					case self::FETCH_OBJECT: 
						return (int)$fetched->total;
				}
			}

			public function begin()
			{
				$this->query('BEGIN;');
			}

			public function commit()
			{
				$this->query('COMMIT;');
			}

			public function rollback()
			{
				$this->query('ROLLBACK;');
			}

			public function __destruct()
			{
				if($this->handle)
					$this->handle->close();
			}
		}
?>