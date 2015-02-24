<?php
	namespace core\sql;

	class Model_ORM
	{
		private $table = null;
		private $sql   = null;
		private $mode  = null;
		private $db    = null;
		private $links = array();
		private $flags = array();

		private function __construct($table = false,$name = false, $type = false)
		{
			$this->db = \core\sql\Model_DatabaseFactory::getDriver($type, $name);

			if(is_array($table))
				foreach($table as &$item)
					$item = $this->db->escapeField($item);
			else
				$table = $this->db->escapeField($table);

			$this->table = $table;
		}

		public function db()
		{
			return $this->db;
		}

		public static function take($table = false,$name = false, $type = false)
		{	
			return new self($table,$name,$type);
		}

		public function exec($custom = null)
		{
			//echo $this->sql;
			$this->db->query(null === $custom ? $this->sql : $custom);
			$this->sql = null;
			return $this->db;
		}

		private function clearFlag($value)
		{
			return str_replace(':nowrap', '', $value);
		}

		public function flags(array $flags = array())
		{
			$this->flags = $flags;
			return $this;
		}

		public function subsql()
		{
			return "({$this->sql}:nowrap)";
		}

		public function select()
		{
			$args = func_get_args();

			$this->sql .= "SELECT ";

			if($this->flags){
				$this->sql .= implode(' ', $this->flags) . ' ';
				$this->flags = array();
			}				

			if(func_num_args() == 0)
				$this->sql .= "*";
			else if(func_num_args() == 1 and is_string($args[0]))
				$this->sql .= (strpos($args[0],':nowrap') !== false) ? $this->clearFlag($args[0]) : $this->db->escapeField($args[0]);
			else if(func_num_args() == 1 and is_array($args[0])){
				$f = array();
				foreach($args[0] as $key => $value){
					if(is_numeric($key))
						$f[] = (strpos($value,':nowrap') !== false) ? $this->clearFlag($value) : $this->db->escapeField($value);
					else
						$f[] = ((strpos($key,':nowrap') !== false) ? $this->clearFlag($key) : $this->db->escapeField($key)) . " AS " . ((strpos($value,':nowrap') !== false) ? $this->clearFlag($value) : $this->db->escapeField($value));
			 	}

				$this->sql .= implode(",",$f);
			}

			$this->sql .= " FROM " . (is_array($this->table) ? implode(',',$this->table) : $this->table);
			return $this;
		}

		public function insert(array $data,$multi = false)
		{
			return $this->paste("INSERT INTO",$data,$multi);
		}

		private function paste($mode,array $data,$multi = false)
		{
			$keys = array();
			$values = array();

			if($multi === true){
				$is_keys = false;
				foreach($data as $key=>$subdata){
					$set = array();
					foreach($subdata as $key=>$item){
						if($is_keys === false)	
							$keys[] = $this->db->escapeField($key);
						$set[] = $item === null ? 'NULL' : "'".$this->db->escape($item)."'";
					}
					$values[] = "(" . implode(",", $set) . ")";
					$is_keys = true;
				}

				$this->sql = "{$mode} {$this->table} (".implode(",",$keys).") VALUES ".implode(",", $values).";";	
			}else{
				foreach($data as $key=>$item){
					$keys[] = $this->db->escapeField($key);
					$values[] = $item === null ? 'NULL' : "'".$this->db->escape($item)."'";
				}

				$this->sql = "{$mode} {$this->table} (".implode(",",$keys).") VALUES (".implode(",",$values).");";
			}


			return $this;
		}

		public function onDuplicate(array $set)
		{
			$c = array();
			foreach($set as $k=>$v)
				$c[] = $this->db->escapeField($k) . " = " . ($v === null ? 'NULL' : "'" . $this->db->escape($v) ."'");

			$this->sql .= " ON DUPLICATE KEY UPDATE " .  implode(',', $c);
			return $this;
		}

		public function replace(array $data)
		{
			return $this->paste("REPLACE INTO",$data,$multi);
		}

		public function update(array $set)
		{	
			$this->sql = "UPDATE {$this->table} ";
			$c = array();
			foreach($set as $k=>$v)
				$c[] = $this->db->escapeField($k) . " = " . ($v === null ? 'NULL' : "'" . $this->db->escape($v) ."'");

			$this->sql .= " SET " .  implode(',', $c);

			return $this;
		}

		public function delete(array $tables = array())
		{	
			foreach($tables as $key=>$table)
				$tables[$key] = $this->db->escapeField($table);

			$this->sql = "DELETE " . ($tables ? implode(", ",array_values($tables)) . " " : "") . "FROM " . (is_array($this->table) ? implode(',',$this->table) : $this->table);
			return $this;
		}

		public function link($f1,$f2)
		{
			$this->links[] = $this->db->escapeField($f1) ." = " . $this->db->escapeField($f2);
			return $this;
		}

		public function join($table,$f1,$f2)
		{
			$this->sql .= 
			" INNER JOIN " . $this->db->escapeField($table) 
			. " ON " 
			. $this->db->escapeField($f1) 
			. " = "
			. $this->db->escapeField($f2);
			
			return $this;
		}

		public function leftJoin($table,$f1,$f2)
		{
			$this->sql .= 
			" LEFT OUTER JOIN " . $this->db->escapeField($table) 
			. " ON " 
			. $this->db->escapeField($f1) 
			. " = "
			. $this->db->escapeField($f2);

			return $this;
		}

		public function rightJoin($table,$f1,$f2)
		{
			$this->sql .= 
			" RIGHT OUTER JOIN " . $this->db->escapeField($table) 
			. " ON " 
			. $this->db->escapeField($f1) 
			. " = "
			. $this->db->escapeField($f2);
			return $this;
		}

		private function whereBuild(array $where,$mode,$op)
		{
			$crt = \core\sql\Model_SqlCriteria::make($this->db,$this);

			foreach($where as $fn=>$args)
				$crt->parse($fn,$args);

			$stmt = array_merge($this->links,$crt->build());

			$this->links = array();

			if($stmt){
				$this->sql .=  " {$mode} ";
				if(count($stmt) == 1)
					$this->sql .= implode(" {$op} " , $stmt);
				else
					$this->sql .= "(" . implode(" {$op} " , $stmt) . ")";
			}
				
			return $this;
		}

		public function where(array $where = array(), $op = "AND")
		{
			return $this->whereBuild($where, " WHERE", $op);
		}

		public function andWhere(array $where = array(),$op = "AND")
		{
			return $this->whereBuild($where, "AND", $op);
		}

		public function orWhere(array $where = array(),$op = "AND")
		{
			return $this->whereBuild($where, "OR", $op);
		}
		
		public function order($ord)
		{
			$this->sql .= " ORDER BY ";			
			if(is_array($ord)){
				$inline = array();
				foreach($ord as $key=>$item){
					$inline[] = $this->db->escapeField($key) . " " . $item;
				}
				$this->sql .= implode(", ",$inline);
			}else
				$this->sql .= $this->db->escapeField($ord) . " ASC";

			return $this;
		}

		public function group($grp)
		{
			$this->sql .= " GROUP BY ";

			if(is_array($grp)){
				$inline = array();
				foreach($grp as $gr)
					$inline[] = $this->db->escapeField($gr);
				$this->sql .= implode(", ",$inline);
			}else
				$this->sql .= $this->db->escapeField($grp);

			return $this;
		}

		public function having(array $where,$op = "AND")
		{
			return $this->whereBuild($where, "HAVING", $op);
		}

		public function limit($limit = 20,$page = 1)
		{
			$this->sql .=  " LIMIT ";
			if($page != 1)
				$this->sql .= (($page - 1) * $limit ) . ",";
			$this->sql .= $limit;

			return $this;
		}

		public function begin()
		{
			$this->db->begin();
			return $this;
		}

		public function commit()
		{	
			$this->db->commit();
			return $this;
		}

		public function rollback()
		{
			$this->db->rollback();
			return $this;
		}

		public function __toString()
		{
			return trim($this->sql);
		}
	}
?>