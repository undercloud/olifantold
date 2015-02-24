<?php
	namespace core\utils;

	class Model_SessionHandler
    {
        private $dbh = null;

        public function __construct() 
        {
            $this->dbh = \core\sql\Model_ORM::take('session_handler_table');

            session_set_save_handler(
                array($this, 'open'),
                array($this, 'close'),
                array($this, 'read'),
                array($this, 'write'),
                array($this, 'destroy'),
                array($this, 'gc')
            );

            register_shutdown_function('session_write_close');
        }
     
        public function open($savePath, $sessionName) 
        {
            $this->dbh->delete()
            ->where(
                array(
                    'timestamp:lt' => time() - (3600 * 24)
                )
            )
            ->exec();

            return true;
        }
     
        public function close() 
        {
            return true;
        }
     
        public function read($id) 
        {
            $res = $this->dbh->select('data')
            ->where(
                array(
                    'id' =>$id
                )
            )
            ->limit(1)
            ->exec();

            if($res->countRows() == 0){
                return false;
            }else{
                $fetched = $res->fetch();
                return $fetched['data'];
            }
        }
     
        public function write($id, $data) 
        {
            $this->dbh->replace(
                array(
                    'id' => $id,
                    'data' => $data,
                    'timestamp' => time()
                )
            )
            ->exec();

            return true;
        }
     
        public function destroy($id) 
        {
            $this->dbh->delete()
            ->where(
                array(
                    'id' => $id
                )
            )
            ->exec();

            return true;
        }
     
        public function gc($max) 
        {
            echo '<h1>'. $max . '</h1>';

            $this->dbh->delete()
            ->where(
                array(
                    'timestamp:lt' => time() - intval($max)
                )
            )
            ->exec();

            return true;
        }
    }
?>