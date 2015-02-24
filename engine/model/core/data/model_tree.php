<?php
    namespace core\data;

        class Model_Tree
        {
            private $_items = array();
            private $_childs = array();

            public function itemExists($id)
            {
                return isset($this->_items[$id]);
            }
            
            public function getCount()
            {
                return sizeof($this->_items);
            }
          
            public function addItem($id , $parent = 0 , $data)
            {   
               $this->_items[$id] = array(
                    'id'=>$id ,
                    'parent'=>$parent ,
                    'data'=>$data
                );    

                if(!isset($this->_childs[$parent]))
                    $this->_childs[$parent] = array();
                
                $this->_childs[$parent][$id] = & $this->_items[$id];    
            }
           
            public function getItem($id)
            {
                if($this->itemExists($id))
                    return $this->_items[$id];
                else     
                    throw new \app\Exception('Wrong id = '.$id);   
            }
          
            public function hasChilds($id)
            {
                return isset($this->_childs[$id]);
            }
           
            public function getChilds($id)
            {
                 if(!$this->hasChilds($id))
                    return array();
                  return $this->_childs[$id];
            }
           
            protected function _remove($id)
            {
                $childs = $this->getChilds($id);     
                if(!empty($childs)){
                    foreach ($childs as $k=>$v){
                        $this->_remove($v['id']);
                    }
                }        
                if(isset($this->_childs[$id]))
                    unset($this->_childs[$id]);
                  
                $parent = $this->_items[$id]['parent'];
                 
                if(!empty($this->_childs[$parent])){
                    foreach ($this->_childs[$parent] as $key=>$item){
                        if($item['id']==$id){
                            unset($this->_childs[$parent][$key]);
                            break; 
                        }
                    }
                }      
               
                unset($this->_items[$id]);
            }
            
            public function removeItem($id)
            {
                if($this->itemExists($id))
                   $this->_remove($id);     
            }
          
            public function changeParent($id,$newParent)
            {
                if($this->itemExists($id) && ($this->itemExists($newParent) || $newParent === 0)){
                    $oldParent = $this->_items[$id]['parent'];
                    $this->_items[$id]['parent'] = $newParent;
                    if(!empty($this->_childs[$oldParent])){
                        foreach ($this->_childs[$oldParent] as $k=>$v){
                                if($v['id']===$id){
                                    unset($this->_childs[$oldParent][$k]);
                                    break;
                                }
                        }
                    }
                    $this->_childs[$newParent][$id] = & $this->_items[$id];
                }
            }
        } 
?>