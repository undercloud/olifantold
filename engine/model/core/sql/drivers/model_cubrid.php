<?php
	namespace core\sql\drivers;

		class Model_CUBRID implements IDatabaseDriver
		{
			protected $handle = null;
			protected $result = null;
			protected $fetch_mode = self::FETCH_ASSOC; 

			protected $last_query = null;

			public function __construct(array $connection)
			{
				$this->host = $connection['host'];
				$this->port = $connection['port'];
				$this->user = $connection['user'];
				$this->pass = $connection['pass'];
				$this->charset = $connection['charset'];
			}

			public function getHandle()
			{
				return $this->handle;
			}

			public function selectDatabase($dbname)
			{
				$this->handle = @cubrid_connect(
					$this->host, 
					$this->port, 
					$dbname,
					$this->user,
					$this->pass
				);

				if(!$this->handle)
					throw new \core\sql\DBException("Can't connect to database");

				$this->query("SET NAMES " . $this->charset);
			}

			public function escapeField($string)
			{
				$string = explode('.',$string);
				foreach($string as &$item)
					if($item != '*')
						$item = "`{$item}`";

				return implode('.',$string);
			}

			public function escape($string)
			{
				return cubrid_real_escape_string($string,$this->handle);
			}

			public function query($sql)
			{
				$sql = trim($sql);
				$this->last_query = $sql;

				$this->result = cubrid_query($sql,$this->handle);

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

			public function errorCode()
			{
				return cubrid_errno($this->handle);
			}
			
			public function errorInfo()
			{
				return cubrid_error($this->handle);
			}
			
			public function lastInsertId()
			{
				return cubrid_insert_id($this->handle);
			}

			public function affectedRows()
			{
				return cubrid_affected_rows($this->handle);
			}

			public function countRows()
			{
				return cubrid_num_rows($this->result);
			}
			
			public function countFields()
			{
				return cubrid_num_fields($this->result);
			}

			public function setFetchMode($mode)
			{
				$this->fetch_mode = $mode;
			}
			
			private function getFetchFunction()
			{
				switch($this->fetch_mode){
					default:
						throw new \core\sql\DBException('Unknown fetch mode ' . $this->fetch_mode);
					break;

					case self::FETCH_ASSOC:
						$fn ='cubrid_fetch_assoc';
					break;

					case self::FETCH_NUM:
						$fn = 'cubrid_fetch_row';
					break;

					case self::FETCH_OBJECT:
						$fn = 'cubrid_fetch_object';
					break;
				}

				return $fn;
			}

			public function fetch()
			{
				$fn = $this->getFetchFunction();
				$row =  $fn($this->result);

				if(!$row)
					cubrid_free_result($this->result);
				
				return $row;
			}

			public function fetchAll($key = false)
			{
				$fn = $this->getFetchFunction(); 

				$assoc = array();
				if($this->result){
					if($key !== false and $this->fetch_mode != self::FETCH_NUM){
						if($this->fetch_mode === self::FETCH_ASSOC){
							while($row = $fn($this->result)){
								foreach($row as $k=>&$v)
									if(trim($v)==='')
										$v = null;

								$assoc[$row[$key]] = $row;
							}
						}else if($this->fetch_mode === self::FETCH_OBJECT){
							while($row = $fn($this->result)){
								foreach($row as $k=>&$v)
									if(trim($v)==='')
										$v = null;

								$assoc[$row->$key] = $row;
							}
						}
					}else{
						while($row = $fn($this->result)){
							foreach($row as $k=>&$v)
								if(trim($v)==='')
									$v = null;

							$assoc[] = $row;
						}
					}
					cubrid_free_result($this->result);
				}
				
				return $assoc;
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
					cubrid_disconnect($this->handle);
			}
		}
?>