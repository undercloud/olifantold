<?php
	namespace core\data;

		class Model_Pagination
		{
			private $active_class = "";
			private $passive_class = "";
			private $click_type = "href";
			private $callback = "";
			private $tagname = "a";
			private $inline = 10;
		
			public function setActiveClass($class)
			{
				$this->active_class = $class;
			}
			
			public function setPassiveClass($class)
			{	
				$this->passive_class = $class;
			}
		
			public function setClickType($type)
			{
				$this->click_type = $type;
			}

			public function setCallback($callback)
			{
				if(is_callable($callback))
					$this->callback = $callback;
				else
					throw new \app\AppException("Bad calback");
			}

			public function setTagName($tag)
			{
				$this->tagname = $tag;
			}

			public function setInline($inline)
			{
				$this->inline = $inline;
			}

			public function buildPag($total,$limit,$page)
			{
				$total = (int)$total;
				$pages = intval(($total - 1) / $limit ) +1;  
		
				if($page <= 0)
					$page = 1;

				if($page > $pages)
					$page = $pages;

				$page_start = intval(($page - 1) / $this->inline) * $this->inline;
				$page_end = ($page_start + $this->inline > $pages) ? $pages : $page_start + $this->inline;

				if($page > $this->inline){
					$toFirst = "<$this->tagname class='".$this->passive_class."' ".$this->click_type."='".call_user_func($this->callback,1)."'>1</$this->tagname>  ";  
				}

				if($page > $this->inline){
					$toFirstDouble = "<$this->tagname class='".$this->passive_class."' ".$this->click_type."='".call_user_func($this->callback,(int)(($page - 1) / $this->inline - 1) * $this->inline + 1)."'>...</$this->tagname>  ";
				}

				if($page_end < $pages){
					$toLastDouble = "  <$this->tagname class='".$this->passive_class."' ".$this->click_type."='".call_user_func($this->callback,(int)(($page - 1) / $this->inline + 1) * $this->inline + 1)."'>...</$this->tagname> ";
				}

				if($pages > $this->inline and $pages >= $page_start + $this->inline){
					$toLast = "  <$this->tagname class='".$this->passive_class."' ".$this->click_type."='".call_user_func($this->callback,$pages)."'>$pages</$this->tagname> ";  
				}
				
				if($pages != 1){
					for($i = $page_start; $i < $page_end; $i++){
						if($i + 1 == $page){
							$mainNav .= " <$this->tagname class='".
							$this->active_class."' ".
							$this->click_type."='".call_user_func($this->callback,$i + 1)."'>".
							($i + 1).
							"</$this->tagname> ";
						}else{
							$mainNav .= " <$this->tagname class='".
							$this->passive_class."' ".
							$this->click_type."='".call_user_func($this->callback,$i + 1)."'>".
							($i + 1).
							"</$this->tagname> ";
						}
					}
				}else{
					$mainNav .= " <$this->tagname class='".
					$this->active_class."' ".
					$this->click_type."='".call_user_func($this->callback,$i + 1)."'>".
					($i + 1).
					"</$this->tagname> ";
				}

				return $toFirst.$toFirstDouble.$mainNav.$toLastDouble.$toLast;
			}
		}
?>