<?php
	namespace core\sql;

		class Model_SqlCriteria
		{
			private $stmt  = array();
			private $db    = null;
			private $orm   = null;

			private $match = array(
				'lt','gt','lte','gte','eq','neq',
				'in','nin','isnull','notnull',
				'like','notlike','between',
				'notbetween'
			);

			public function __construct($db,$orm)
			{
				$this->db = $db;
				$this->orm = $orm;
			}

			public static function make($db,$orm)
			{
				return new self($db,$orm);
			}

			public function parse($fn,$args)
			{
				if(is_numeric($fn))
					$fn = $args;

				$quote = true;
				if(is_object($args)){
					$args  = $this->wrap($args);
					$quote = false;
				}	

				$split = explode(':',$fn);

				if(count($split) == 1)
					return $this->allEq(
						$this->db->escapeField($split[0]),
						$args,
						$quote
					);

				if(count($split) == 2 and $split[1] == 'nowrap')
					return $this->allEq(
						$split[0],
						$args,
						$quote
					);

				if(isset($split[2]) and $split[2] == 'nowrap')
					$field = $split[0];
				else	
					$field = $this->db->escapeField($split[0]);

				if(in_array($split[1], $this->match))
					return call_user_func_array(
						array($this,$split[1]), 
						array(
							$field,
							$args,
							$quote
						)
					);
			}

			private function escape($str,$quote = true)
			{
				return ($quote === true) ? "'" . $this->db->escape($str) . "'" : $str;
			}

			public function wrap($subsql)
			{
				return "({$subsql})";
			}

			public function lt($field,$value,$quote = true)
			{	
				$this->stmt[] = $field . " < " . $this->escape($value,$quote);
				return $this;
			}

			public function gt($field,$value,$quote = true)
			{	
				$this->stmt[] = $field . " > " . $this->escape($value,$quote);
				return $this;
			}

			public function lte($field,$value,$quote = true)
			{	
				$this->stmt[] = $field . " <= " . $this->escape($value,$quote);
				return $this;
			}

			public function gte($field,$value,$quote = true)
			{	
				$this->stmt[] = $field . " >= " . $this->escape($value,$quote);
				return $this;
			}

			public function allEq($key,$value,$quote = true)
			{
				if(is_array($value))
					$this->in($key,$value,$quote);
				else if($value === null)
					$this->isnull($key);
				else
					$this->eq($key,$value,$quote);

				return $this;
			}

			public function eq($field,$value,$quote = true)
			{
				$this->stmt[] = $field . " = " . $this->escape($value,$quote);
				return $this;
			}

			public function neq($field,$value,$quote = true)
			{	
				$this->stmt[] = $field . " != " . $this->escape($value,$quote);
				return $this;
			}

			public function in($field,$value,$quote = true)
			{	
				if(is_array($value)){
					if($value){
						foreach($value as $k=>&$v)
							$v = $this->escape($v,$quote);

						$this->stmt[] = $field . " IN (" . implode(",",$value) . ")";
					}
				}else{
					$this->stmt[] = $field . " IN (" . $this->escape($value,$quote) . ")";
				}

				return $this;
			}

			public function nin($field,$value,$quote = true)
			{	
				if(is_array($value)){
					if($value){
						foreach($value as $k=>&$v)
							$v = $this->escape($v,$quote);

						$this->stmt[] = $field . " NOT IN (" . implode(",",$value) . ")";
					}
				}else{
					$this->stmt[] = $field . " NOT IN (" . $this->escape($value,$quote) . ")";
				}

				return $this;
			}

			public function isnull($field)
			{	
				$this->stmt[] = $field . " IS NULL ";
				return $this;
			}

			public function notnull($field)
			{	
				$this->stmt[] = $field ." IS NOT NULL ";
				return $this;
			}

			public function like($field,$value,$quote = true)
			{	
				$this->stmt[] = $field . " LIKE " . $this->escape($value,$quote);
				return $this;
			}

			public function notlike($field,$value,$quote = true)
			{	
				$this->stmt[] = $field . " NOT LIKE " . $this->escape($value,$quote);
				return $this;
			}

			public function between($field,array $range)
			{	
				$this->stmt[] = $field . " BETWEEN " . $this->escape($range[0]) . " AND " . $this->escape($range[1]);
				return $this;
			}

			public function notbetween($field,array $range)
			{	
				$this->stmt[] = $field . " NOT BETWEEN " . $this->escape($range[0]) . " AND " . $this->escape($range[1]);
				return $this;
			}

			public function build()
			{
				return $this->stmt;
			}
		}
			
?>