<?php 
class DBConnect{
    private $dbh;
    private static $instance = null;
    private function __construct(){
        
    try {
            if(isset($_POST['host'])){
                $dbtype = $_POST['rdbms'];
                $host = $_POST['host'];
                $user   = $_POST['username'];
    		    $password = $_POST['password']; 
            }else{
                $dbtype = "mysql";
                $host = 'localhost';
                $user   = 'root';
                $password = '@rahul'; 
            }
			$dsn    = $dbtype.":host=".$host.";dbname=test";
			$initArr = array(\PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8, time_zone = '+05:30'");


			$this->dbh = new PDO($dsn,$user,$password, $initArr);
			$this->dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        } catch(PDOException $e) {

            echo 'ERROR: ' . $e->getMessage();
			echo "Server error: Kindly contact administrator.<br />";
		    die();

        }
	}
	public static function getInstance() {
	    if (is_null(self::$instance)) {
	        self::$instance = new self();
	    }
	    return self::$instance;
	}
    public function getDb() {
       if ($this->dbh instanceof PDO) {
            return $this->dbh;
       }
    }
    public function getConnection() {
        return $this->dbh;
    }
    private function __clone() {
        return false;
    }
    private function __wakeup() {
        return false;
    }
}
?>
