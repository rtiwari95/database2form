<?php
error_reporting(E_ALL);
class admin_Model{
    
    protected $pdo = null;
    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }
    
    public function list_tables($db){
        $quer = 'USE '.$db;
        $sth = $this->pdo->prepare($quer);
        $sth->execute();
        
        $sql = 'SHOW TABLES';
        $query = $this->pdo->query($sql);
        return $query->fetchAll(PDO::FETCH_COLUMN);
    }
    
    public function list_columns($table,$db){
        $quer = 'USE '.$db;
        $sth = $this->pdo->prepare($quer);
        $sth->execute();
        
        $sql = 'DESCRIBE '.$table.'';
        $query = $this->pdo->query($sql);
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        
        return $result;
    }
    
    public function list_databases(){
        $rdbms = $_REQUEST['rdbms'];
        $host = $_REQUEST['host'];
        $username = $_REQUEST['username'];
        $password = $_REQUEST['password'];
        try {
            $conn = new PDO($rdbms.":host=$host;dbname=test", $username, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = 'SHOW DATABASES';
            $query = $this->pdo->query($sql);
            return $query->fetchAll(PDO::FETCH_ASSOC);
        }
        catch(PDOException $e)
        {
            return "Connection failed: " . $e->getMessage();
            
        }
        
    }
    
    public function createUserTable($rdbms,$host,$username,$password,$db){
        try {
            $conn = new PDO($rdbms.":host=$host;dbname=".$db, $username, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            $query = 'USE '.$db;
             $sth = $this->pdo->prepare($query);
             $sth->execute();
            
            //creation of user table
            $query = "CREATE TABLE IF NOT EXISTS ".$db."_users(
                 id INT(5) NOT NULL AUTO_INCREMENT,
                 username VARCHAR(21) NOT NULL,
                 user_fullname VARCHAR(51) NOT NULL,
                 password VARCHAR(101) NOT NULL,
                 user_status ENUM('Active','Blocked') NOT NULL,
                 user_role ENUM('admin','standardUser','guest') NOT NULL,
                 PRIMARY KEY(id),UNIQUE(username))";
            $conn->exec($query);
            
            //creation of user's log table
            $query = "CREATE TABLE IF NOT EXISTS ".$db."_user_log(
                  id INT(5) NOT NULL AUTO_INCREMENT ,
                  username VARCHAR(21) NOT NULL ,
                  loggedAt DATETIME NOT NULL ,
                  ip VARCHAR(101) NOT NULL ,
                  PRIMARY KEY (id))";
            $conn->exec($query);
            
            //insertion of admin data to user's table
            $query = "INSERT INTO ".$db."_users
                      (username,user_fullname,password,user_status,user_role)
                      VALUES('admin','Administrator',SHA1('admin'),'Active','admin')";
            
            $sth = $this->pdo->prepare($query);
            $sth->execute();
            
            return $this->pdo->lastInsertId();
            
        } catch(PDOException $e) {
            echo $e->getMessage();//Remove or change message in production code
        }
    }
    
}
?>