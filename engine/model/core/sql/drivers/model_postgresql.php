<?php
	namespace core\sql\drivers;

		class Model_PostgreSQL implements IDatabaseDriver
		{
			protected $link    = null;
			protected $result = null;
			protected $fetch_mode = self::FETCH_ASSOC; 
		
			protected $last_query = null;
		
			public function __construct(array $connection)
			{
				$this->host = $connection['host'];
				$this->user = $connection['user'];
				$this->pass = $connection['pass'];
				$this->charset = $connection['charset'];
			}

			public function getHandle()
			{
				return $this->link;
			}
			
			public function selectDatabase($dbname)
			{
				$this->link = @pg_connect (
					"host=".$this->host.
					" user=".$this->user.
					" password=".$this->pass.
					" dbname=".$dbname
				);

				if(!$this->link)
					throw new \core\sql\DBException("Can't connect to database");

	            if(pg_set_client_encoding($this->link,$this->charset) == -1)
	            	throw new \core\sql\DBException("Can't set charset " . $this->charset);
	            	
			}
			
			public function escapeField($string)
			{
				$string = explode('.',$string);
				foreach($string as &$item)
					if($item != '*')
						$item = "'{$item}'";

				return implode('.',$string);
			}

			public function escape($string)
			{
				return pg_escape_string($this->link,$string);
			}
			
			public function query($query)
			{
				$query = trim($query);
				$this->last_query = $query;

				@pg_send_query($this->link,$query);

				$this->result = pg_get_result($this->link);

				if($this->errorCode() != 0)
	                throw new \core\sql\DBException(
	                	$this->errorCode()." ".
	                    $this->errorInfo()." ".
	                    $query
	                );
			}
			
			public function lastQuery()
			{
				return $this->last_query;
			}

			public function errorCode()
			{
				return pg_result_error_field($this->result,PGSQL_DIAG_SQLSTATE);
			}
			
			public function errorInfo()
			{
				return pg_result_error($this->result);
			}
			
			public function lastInsertId()
			{
				$this->query("SELECT lastval()");
				$f = $this->fetch();
				return (int)$f['lastval'];
			}
			
			public function affectedRows()
			{
				return pg_affected_rows($this->result);
			}
			
			public function countRows()
			{
				return pg_num_rows($this->result);
			}
			
			public function countFields()
			{
				return pg_num_fields($this->result);
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
						$fn ='pg_fetch_assoc';
					break;

					case self::FETCH_NUM:
						$fn = 'pg_fetch_row';
					break;

					case self::FETCH_OBJECT:
						$fn = 'pg_fetch_object';
					break;
				}

				return $fn;
			}

			public function fetch()
			{
				$fn = $this->getFetchFunction();
				$row =  $fn($this->result);

				if(!$row)
					pg_free_result($this->result);
				
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
					}else
						while($row = $fn($this->result)){
							foreach($row as $k=>&$v)
								if(trim($v)==='')
									$v = null;
							
							$assoc[] = $row;
						}
					pg_free_result($this->result);
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
				if($this->link)
					pg_close($this->link);
			}
		}
?>