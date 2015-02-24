<?php
	namespace core\sql\drivers;

		class Model_MySQL implements IDatabaseDriver
		{
			protected $link   = null;
			protected $result = null;
			protected $fetch_mode = self::FETCH_ASSOC; 

			protected $last_query = null;
		
			public function __construct(array $connection)
			{
				$this->link = @mysqli_connect(
					$connection['host'],
					$connection['user'],
					$connection['pass']
				);
				
				if(!$this->link)
	                throw new \core\sql\DBException("Can't connect to database");

	            if(mysqli_set_charset($this->link,$connection['charset']) === false)
	            	throw new \core\sql\DBException("Can't set charset " . $connection['charset']);
			}

			public function getHandle()
			{
				return $this->link;
			}
			
			public function selectDatabase($dbname)
			{
				$is_select = mysqli_select_db($this->link,$dbname);
				if(!$is_select)
	                throw new \core\sql\DBException("Can't select database ".$dbname);
			}
			
			public function escapeField($string)
			{
				$string = explode('.',$string);
				foreach($string as &$item){
					if($item != '*')
						$item = "`{$item}`";
				}

				return implode('.',$string);
			}

			public function escape($string)
			{
				return mysqli_real_escape_string($this->link,$string);
			}
			
			public function query($query)
			{
				$query = trim($query);
				$this->last_query = $query;

				$this->result = mysqli_query($this->link,$query);
				
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
				return mysqli_errno($this->link);
			}
			
			public function errorInfo()
			{
				return mysqli_error($this->link);
			}
			
			public function lastInsertId()
			{
				return mysqli_insert_id($this->link);
			}
			
			public function affectedRows()
			{
				return mysqli_affected_rows($this->link);
			}
			
			public function countRows()
			{
				return mysqli_num_rows($this->result);
			}
			
			public function countFields()
			{
				return mysqli_num_fields($this->result);
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
						$fn ='mysqli_fetch_assoc';
					break;

					case self::FETCH_NUM:
						$fn = 'mysqli_fetch_row';
					break;

					case self::FETCH_OBJECT:
						$fn = 'mysqli_fetch_object';
					break;
				}

				return $fn;
			}

			public function fetch()
			{
				$fn = $this->getFetchFunction();
				$row =  $fn($this->result);

				if($row)
					foreach($row as $key=>&$value)
						if(trim($value)==='')
							$value = null;

				if(!$row)
					mysqli_free_result($this->result);
				
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

					mysqli_free_result($this->result);
				}
				
				return $assoc;
			}

			public function totalRows()
			{
				$pos = strripos($this->last_query,"LIMIT");
				if($pos)
					$this->last_query = substr($this->last_query,0,$pos);
				
				$this->query(preg_replace("/SELECT.*?FROM/ims", "SELECT COUNT(*) AS total FROM", $this->last_query,1));
				$fetched = $this->fetch();

				switch($this->fetch_mode){
					default:
						throw new \core\sql\DBException('Unknown fetch mode ' . $this->fetch_mode);
					break;
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
					mysqli_close($this->link);
			}
		}
?>