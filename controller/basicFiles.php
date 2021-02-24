<?php
error_reporting(E_ALL);
Class basicFiles
{
    function __construct() {}
    
    
 //Application  files 
    /**start**/
    public function createBaseController(){
        $str = '<?php

abstract class baseController{

    /*
     * @registry object
     */
    protected $registry;

    function __construct($registry){
        $this->registry = $registry;
        // session_start();
    }

    /**
     *
     * @all controllers must contain an index method
     */
    abstract function index();
}

?>';
        return $str;
    }

    public function createRegistryClass(){
        $str = '<?php

class Registry{

    /*
     * @the vars array
     * @access private
     */
    private $vars = array();

    /**
     *
     * @set undefined vars
     *
     * @param string $index
     *
     * @param mixed $value
     *
     * @return void
     *
     */
    public function __set($index, $value){
        $this->vars[$index] = $value;
    }

    /**
     *
     * @get variables
     *
     * @param mixed $index
     *
     * @return mixed
     *
     */
    public function __get($index){
        return $this->vars[$index];
    }
}

?>';
        return $str;
    }

    public function createRouterClass(){
        $str = '<?php

class router{

    /*
     * @the registry
     */
    private $registry;

    /*
     * @the controller path
     */
    private $path;

    private $args = array();

    public $file;

    public $controller;

    public $action;

    function __construct($registry){
        $this->registry = $registry;
    }

    /**
     *
     * @set controller directory path
     *
     * @param string $path
     *
     * @return void
     *
     */
    function setPath($path){

        /**
         * * check if path i sa directory **
         */
        if (is_dir($path) == false) {
            throw new Exception("Invalid controller path: `" . $path . "`");
        }
        /**
         * * set the path **
         */
        $this->path = $path;
    }

    /**
     *
     * @load the controller
     *
     * @access public
     *        
     * @return void
     *
     */
    public function loader(){
        /**
         * * check the route **
         */
        $this->getController();

        /**
         * * if the file is not there diaf **
         */
        if (is_readable($this->file) == false) {
            $this->file = $this->path . "/error404.php";
            $this->controller = "error404";
        }

        /**
         * * include the controller **
         */
        include $this->file;

        /**
         * * a new controller class instance **
         */
        $class = $this->controller . "Controller";
        $controller = new $class($this->registry);

        /**
         * * check if the action is callable **
         */
        if (is_callable(array(
            $controller,
            $this->action
        )) == false) {
            $action = "index";
        } else {
            $action = $this->action;
        }
        /**
         * * run the action **
         */
        $controller->$action();
    }

    /**
     *
     * @get the controller
     *
     * @access private
     *        
     * @return void
     *
     */
    private function getController(){

        /**
         * * get the route from the url **
         */
        $route = (empty($_GET["rt"])) ? "" : $_GET["rt"];

        if (empty($route)) {
            $route = "index";
        } else {
            /**
             * * get the parts of the route **
             */
            $parts = explode("/", $route);
            $this->controller = $parts[0];
            if (isset($parts[1])) {
                $this->action = $parts[1];
            }
        }

        if (empty($this->controller)) {
            $this->controller = "index";
        }

        /**
         * * Get action **
         */
        if (empty($this->action)) {
            $this->action = "index";
        }

        /**
         * * set the file path **
         */
        $this->file = $this->path . "/" . $this->controller . "Controller.php";
    }
}

?>';
        return $str;
    }

    public function createTemplateClass(){
        $str = '<?php

class Template{

    /*
     * @the registry
     * @access private
     */
    private $registry;

    /*
     * @Variables array
     * @access private
     */
    private $vars = array();

    /**
     *
     * @constructor
     *
     * @access public
     *        
     * @return void
     *
     */
    function __construct($registry){
        $this->registry = $registry;
    }

    /**
     *
     * @set undefined vars
     *
     * @param string $index
     *
     * @param mixed $value
     *
     * @return void
     *
     */
    public function __set($index, $value) {
        $this->vars[$index] = $value;
    }

    function show($name, $header = "", $footer = ""){
        $path = __SITE_PATH . "/views" . "/" . $name . ".php";
        // $path = __SITE_PATH . "/views" . "\" . $name .".php";

        if ($header != "none") {}

        if (file_exists($path) == false && ! empty($name) && $header != "none") {
            throw new Exception("Template not found in " . $path);
            return false;
        }

        // Load variables
        foreach ($this->vars as $key => $value) {
            $$key = $value;
        }

        if ($header != "none") {
            include ($header);
        }
        // echo $path;
        if (! empty($name)) {
            include ($path);
        }

        if ($header != "none") {
            include ($footer);
        }
    }
}
?>';
        return $str;
    }

    public function createValidationsClass(){
        $str = '<?php

class validations{

    public function checkemail($email){
        if (! preg_match("/([\w\-]+\@[\w\-]+\.[\w\-]+)/", $email)) {
            // $emailErr = "Invalid email format";
            return true;
        }
        return false;
    }

    public function checkphone($phone){
        if (! preg_match("/^[56789]\d{9}$/", $phone)) {
            return true;
        }
        return false;
    }

    public function checknoonly($input){}

    public function checkchar($input){}

    public function checkdate_self($date){
        $month_array = array(
            "01" => "Jan",
            "02" => "Feb",
            "03" => "Mar",
            "04" => "Apr",
            "05" => "May",
            "06" => "Jun",
            "07" => "Jul",
            "08" => "Aug",
            "09" => "Sep",
            "10" => "Oct",
            "11" => "Nov",
            "12" => "Dec"
        );

        if (! empty($date) && $date != "0.00" && $date != "0000-00-00" && $date != "0") {
            $date_ex = explode("-", $date);
            $day = $date_ex[0];
            $month = array_search($date_ex[1], $month_array);
            $year = $date_ex[2];

            if (checkdate($month, $day, $year) && $year >= 1949 && $year <= 2020) {
                return false;
            }
        }

        return true;
    }

    public function checkyear_self($year){
        if (! empty($year) && $year != "0.00" && $year != "0000" && $year != "0") {

            if (substr($year, 0, 4) >= 1950 && substr($year, 0, 4) <= 2020) {
                return false;
            }
        }

        return true;
    }

    public function isblank($input){
        // echo "hello";die;
        $input = trim($input);

        if (empty($input) || $input == "0.00" || $input == "0000-00-00" || $input == "0") {

            return true;
        }
        return false;
    }

    public function checkfloat($input){
        if (! is_numeric($input)) {

            return true;
        }
        return false;
    }

    public function checkint($input) {
        if (! is_numeric($input)) {
            return true;
        }
        return false;
    }

    public function checkcharc($input){
        if (! preg_match("#^[a-zA-Z\s]+$#", $input)) {
            return true;
        }
        return false;
    }

    public function isblank_exp($input){
        $input = trim($input);

        if ($input == "") {

            return true;
        }

        return false;
    }

    public function pincheck($pincode){
        if (! preg_match("/^([1-9])([0-9]){5}$/", $pincode)) {
            return true;
        }

        return false;
    }

    public function filterinput($input){}

    public function datesformat($input){}

    public function datescheck($from, $to){}

    public function yearcheck($from, $to){}
}
?>';
        return $str;
    }

    /**end**/
    
    
    //Includes  files 
    /**start**/
    public function createInitFile($projectTitle){
        $str = '<?php
/*** include the controller class ***/
include __SITE_PATH . "/application/" . "controller_base.class.php";

/**
 * * include the registry class **
 */
include __SITE_PATH . "/application/" . "registry.class.php";

/**
 * * include the router class **
 */
include __SITE_PATH . "/application/" . "router.class.php";

/**
 * * include the template class **
 */
include __SITE_PATH . "/application/" . "template.class.php";

/**
 * * include the validations class, if not utilised will be removed later **
 */
include __SITE_PATH . "/application/" . "validations.class.php";

include ($_SERVER["DOCUMENT_ROOT"] . "/'.$projectTitle.'/db_config.php");
/**
 * * a new registry object **
 */
$registry = new registry();
/**
 * * create the database registry object **
 */
$registry->db = DBConnect::getInstance()->getConnection();
$pdo = $registry->db;

// Used in core/design

/**
 * * auto load model classes **
 */
function my_autoloader($class_name)
{
    $filename = strtolower($class_name) . ".class.php";
    $file = __SITE_PATH . "/model/" . $filename;

    if (file_exists($file) == false) {
        return false;
    }
    include ($file);
}
spl_autoload_register("my_autoloader");

?>';
        return $str;
    }
    /**end**/
    
    //Controller files
    /**start**/
    
    public function controllerStart($projectTitle,$varSessionCheck){
        
        $str = '<?php
'.$varSessionCheck.'
error_reporting(0);
Class adminController extends baseController {
	private $root_dir;
	function __construct($registry) {
		parent::__construct($registry);
		$this->root_dir="http://".$_SERVER["SERVER_NAME"]."/'.$projectTitle.'/";
		$this->registry->template->root_dir="http://".$_SERVER["SERVER_NAME"]."/'.$projectTitle.'/admin/";
        $this->registry->template->root_dir1="http://".$_SERVER["SERVER_NAME"]."/'.$projectTitle.'/";
	}	
	public function index() { 
		$this->registry->template->show("header");
		$this->registry->template->show("menu");
		$this->registry->template->show("home");
        $this->registry->template->show("footer"); 
	}
    public function home(){
		$this->registry->template->show("header");
		$this->registry->template->show("menu");
		$this->registry->template->show("home");
        $this->registry->template->show("footer");
	}
';
        
        return $str;
    }
    
    public function createController($projectTitle,$table,$id){
        $str = '
	public function '.$table.'Form(){
		$this->registry->template->show("header");
        $this->registry->template->show("menu");
		$this->registry->template->show("'.$table.'Form");
        $this->registry->template->show("footer");
	}
				
	public function show'.ucwords(strtolower($table)).'Data(){
		$admin_obj = new admin_Model($this->registry->db);
		$result = $admin_obj->show'.ucwords(strtolower($table)).'Data();
		$this->registry->template->data = $result;
        $this->registry->template->show("header");
        $this->registry->template->show("menu");
		$this->registry->template->show("'.$table.'ViewForm");
        $this->registry->template->show("footer");
	}
	
	
	public function insert'.ucwords(strtolower($table)).'Data(){
        if ($_POST) {
			$save = new admin_Model($this->registry->db);
			$result = $save->insert'.ucwords(strtolower($table)).'Data();

            if ($result) {
                $msg = "Data successfully inserted";
				$color="green";
            }else{
				$msg = "Problem in insertion";
				$color="red";
			} 
			$this->registry->template->msg = $msg;
			$this->registry->template->color = $color;
			$this->registry->template->show("header");
            $this->registry->template->show("menu");
            $this->registry->template->show("'.$table.'Form");
            $this->registry->template->show("footer");
        }
    }
		
	public function update'.ucwords(strtolower($table)).'Data(){
		
       
        if (isset($_REQUEST["id"])) {
			$id = $_REQUEST["id"];
			$admin_Model_obj = new admin_Model($this->registry->db);
            $data = $admin_Model_obj->show'.ucwords(strtolower($table)).'DataById($id);
			
            if(isset($_REQUEST["update"])){
				$result = $admin_Model_obj->update'.ucwords(strtolower($table)).'Data($id);
				
				if ($result) {
					$msg = "Data successfully updated.";
					$color="green";
				}
			}
			$this->registry->template->msg = $msg;
			$this->registry->template->color = $color;
        
			$this->registry->template->data = $data;
			$this->registry->template->show("header");
			$this->registry->template->show("menu");
			$this->registry->template->show("'.$table.'Form");
			$this->registry->template->show("footer");
		}
    }
	
	public function delete'.ucwords(strtolower($table)).'Data(){
		$id = $_REQUEST["'.$id.'"];
		$admin_Model_obj = new admin_Model($this->registry->db);
		$result = $admin_Model_obj->delete'.ucwords(strtolower($table)).'Data($id);
		
		if($result){
			
			$data = $admin_Model_obj->show'.ucwords(strtolower($table)).'Data();
			$this->registry->template->data = $data;
            $this->registry->template->show("header");
            $this->registry->template->show("menu");
			$this->registry->template->show("'.$table.'ViewForm");
            $this->registry->template->show("footer");
		}
	}
';
        return $str;
    }
    
    public function addLoginToController(){
        
        $str = 'public function changePassword()
    {
        $message = "";
        $old_pwd = $_POST["old_pwd"];
        $new_pwd = $_POST["new_pwd"];
        $cnf_pwd = $_POST["cnf_pwd"];
        $valid = new validations();
        $error_array = array();
        if ($_POST) {
            if ($valid->isblank($old_pwd)) {
                $error_array["old_pwd"] = "Please provide old password";
            } else {
                $chg_pwd = new index_Model($this->registry->db);
                $result_chg = $chg_pwd->login($_SESSION["username"], $old_pwd);
                if (! $result_chg) {
                    $error_array["old_pwd"] = "Old Password is wrong !.";
                }
            }
            if ($valid->isblank($new_pwd)) {
                $error_array["new_pwd"] = "Please provide new password";
            }
            if ($valid->isblank($cnf_pwd)) {
                $error_array["cnf_pwd"] = "Please provide confirm password";
            }
            if ($new_pwd != $cnf_pwd) {
                $error_array["match"] = "New password and confirm password do not match";
            }
        }
        if (count($error_array) == 0 && isset($_POST["save"])) {
            $change_obj = new index_Model($this->registry->db);
            $result = $change_obj->changePass();
            $message = "Changed successfully";
        }
        
        $this->registry->template->error_array = $error_array;
        $this->registry->template->message = $message;
        $this->registry->template->show("header");
        $this->registry->template->show("menu");
        $this->registry->template->show("changePass");
        $this->registry->template->show("footer");
    }
	
	public function logout(){
        $_SESSION = array();
        // get session parameters
        $params = session_get_cookie_params();
        
        // Delete the actual cookie.
        setcookie(session_name(), "", time() - 42000, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);
        
        // Destroy session
        session_destroy();
        header("Location:" . $this->root_dir);
    }
	
	public function add_newuser()
    {
        $error_array = array();
        $valid = new validations();
        $admin_ModelObj = new admin_Model($this->registry->db);
        if ($_POST) {
            if ($valid->isblank($_POST["user_name"])) {
                $error_array["user_name"] = "Please provide User ID.";
            } else {
                $user_res = $admin_ModelObj->select_username();
                foreach ($user_res as $key => $val) {
                    if (strtolower($_POST["user_name"]) == strtolower($val["username"])) {
                        $error_array["user_name"] = "This user is already added !.";
                        break;
                    }
                }
            }
            
            if ($valid->isblank($_POST["user_fullName"])) {
                $error_array["user_fullName"] = "Please provide User Name.";
            }
			if ($valid->isblank($_POST["user_role"])) {
                $error_array["user_role"] = "Please provide User Role.";
            }
            if (count($error_array) == 0) {
                $message = "";
                
                if ($_POST["save_changes"] == "save") {
                    if ($admin_ModelObj->add_users()) {
                        $message = "Added Successfully";
                        $message1 = "By default password is 12345 ";
                    }
                }
            }
        }
        $this->registry->template->message = $message;
        $this->registry->template->message1 = $message1;
        $this->registry->template->error_array = $error_array;
        $this->registry->template->show("header");
        $this->registry->template->show("menu");
        $this->registry->template->show("userForm");
        $this->registry->template->show("footer");
    }
	
	public function list_users(){
		$admin_obj = new admin_Model($this->registry->db);
		$result = $admin_obj->select_username();
		$this->registry->template->data = $result;
        $this->registry->template->show("header");
        $this->registry->template->show("menu");
		$this->registry->template->show("list_users");
        $this->registry->template->show("footer");
	}
	
	public function update_user()
    {
        if (isset($_REQUEST["id"])) {
			$id = $_REQUEST["id"];
			$admin_Model_obj = new admin_Model($this->registry->db);
            $data = $admin_Model_obj->getUserById($id);
			
            if(isset($_REQUEST["save_changes"])){
				$result = $admin_Model_obj->update_user($id);
				
				if ($result) {
					$msg = "User successfully updated.";
					$color="green";
				}
			}
			$this->registry->template->token = "1";
			$this->registry->template->msg = $msg;
			$this->registry->template->color = $color;
        
			$this->registry->template->data = $data;
			$this->registry->template->show("header");
			$this->registry->template->show("menu");
			$this->registry->template->show("userForm");
			$this->registry->template->show("footer");
		}
	}
	
	public function deleteuser(){
		$id = $_REQUEST["id"];
		$admin_Model_obj = new admin_Model($this->registry->db);
		$result = $admin_Model_obj->delete_user($id);
		
		if($result){
			
			$data = $admin_Model_obj->select_username();
			$this->registry->template->data = $data;
            $this->registry->template->show("header");
            $this->registry->template->show("menu");
			$this->registry->template->show("list_users");
            $this->registry->template->show("footer");
		}
	}
	
}
?>';
        
        return $str;
    }
    
    public function createIndexController($projectTitle,$loginRequired=NULL){
        $str = '<?php

class indexController extends baseController{

    private $root_dir;

    function __construct($registry){
        parent::__construct($registry);

        $this->root_dir = "http://" . $_SERVER["SERVER_NAME"] . "/'.$projectTitle.'/";
        $this->registry->template->root_dir = "http://" . $_SERVER["SERVER_NAME"] . "/'.$projectTitle.'/admin/";
    }';
        if($loginRequired != NULL){
            
            $str .='
   public function index(){
	   
        $index_ModelObj = new index_Model($this->registry->db);
        $err_message = "";
        $valid = new validations();
        $error_array = array();
        if ($_POST) {
            if ($valid->isblank($_POST["username"])) {
                $error_array["username"] = "Please provide username";
            }
            if ($valid->isblank($_POST["password"])) {
                $error_array["password"] = "Please provide password";
            }
            
            if (count($error_array) == 0) {
                $result = $index_ModelObj->login($_POST["username"], $_POST["password"]);
                if ($result) {
                    $login_result = $index_ModelObj->single_user($_POST["username"]);
                    foreach ($login_result as $key => $val) {
                          $_SESSION["id"]=$val["id"];
                          $_SESSION["username"]=$val["username"];
                          $_SESSION["user_full_name"]=$val["user_fullname"];
                        header("Location:" . $this->root_dir . "admin");
                    }
                    $save = $index_ModelObj->loginDetails();
                } else {
                    $err_message = "Wrong Credentials !";
                }
            }
        }
        $this->registry->template->error_array = $error_array;
        $this->registry->template->err_message = $err_message;
        
        $this->registry->template->show("header");
        $this->registry->template->show("index");
        $this->registry->template->show("footer");
    }';
            
        }else{
            
            $str .='public function index(){
        $this->registry->template->show("header");
        $this->registry->template->show("home");
        $this->registry->template->show("footer");
    }';
        }
        
        $str .= '    
}

?>';
        return $str;
    }

    public function createError404File(){
        $str = '<?php

class error404Controller extends baseController{

    public function index(){
        $this->registry->template->blog_heading = "This is the 404";
        $this->registry->template->show("error404");
    }
}
?>';
        return $str;
    }
    /**end**/
    
    //View files
    /**start**/
    
    public function createHeader($db){
        $str='<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>'.ucwords(strtolower($table)).'</title>
    <style>
		body{
			 font: 12px verdana, sans-serif;
			 margin: 0px;
		}
		.header{
				background-color: #679BB7;
		 }
		.header span{
                color:white;
				text-align: center;
				font-size:33px;
				font-family: "Times New Roman", Times, serif;
		}
		.footer{
				background-color: #679BB7;
		}
		.footer td{
                color:white;
				font-size: 15px;
				text-align: center;
				margin: 6px;
		}
	</style>
        
	<link rel="stylesheet" type="text/css" href="<?php echo $root_dir1;?>public/css/bootstrap.min.css">
        
	<script src="public/js/bootstrap.min.js"></script>
	<script src="public/js/jquery-3.3.1.min.js"></script>
        
        
</head>
        
<body>
	<div class="container-fluid">
		<div class="row">
		<table class="table-stripped">
			<tr class="header" >
                <td align="left"><img src="<?php echo $root_dir1;?>public/images/db.png" alt="RAC" height="90" width="91"></td>
                <td></td>
				<td align="center" width="100%" colspan="1"><span>STUDENT</span></td>
			</tr>
		</table>
		</div>';
        return $str;
    }
    
    /**
		  
	*/
    public function createFooter(){
        $str = '
<br><br><br><br><br><br><br>
<div class="row">
			<table class="table table-stripped">
				<tr class="footer">
					<td>Generated by "database2form" Copyright &copy;2018, All Rights Reserved</td>
				</tr>
			</table>
		</div>
            
	</div>
</body>
</html> ';
        return $str;
    }
      

    public function createError404View(){
        $str = '<h1><?php echo $blog_heading; ?></h1>
				<p>This is a custom 404 error page.</p>
				<p>You can put whatever content you like here such as search for your site</p>';
        return $str;
    }

    
    public function createHomeView($table){
        $str = '<tr>
    <td>
        <h2><a href="<?php echo $root_dir;?>show'.ucwords(strtolower($table)).'Data">'.ucwords(strtolower($table)).'</a></h2>
    </td>
</tr>';
        return $str;
    }
    
    public function createMenuView($loginRequired=NULL){
        if($loginRequired != NULL){
            $str = '<div class="row">
	<div class="col-sm-12" style="padding-left:0px;padding-right:0px">
		<table class="table table-stripped">
			<tr bgcolor="#e4f1fe">
				<td style="padding-left:51px;width:10%;align:left">
					<strong>
						<a href="<?php echo $root_dir;?>home" >
							<img src="<?php echo $root_dir1;?>/public/images/home.png" height="41" width="41" alt="Home">
						</a>
					</strong>
				</td>
				<?php if($_SESSION["user_role"] == "admin"){?>
				<td style="width:7%">
					<strong>
						<a href="<?php echo $root_dir;?>list_users"> 
							<img src="<?php echo $root_dir1;?>/public/images/users.jpg" height="41" width="41" alt="Add New User">
						</a>
					</strong>
				</td>
				<?php }?>
				<td  style="width:60%">
					<strong>
						<a href="<?php echo $root_dir;?>changePassword"> 
							<img src="<?php echo $root_dir1;?>/public/images/changePass.png" height="41" width="41" alt="Change Password">
						</a>
					</strong>
				</td>
				<td style="color:#446cb3;font-size:21px;width:21%">
						<img src="<?php echo $root_dir1;?>/public/images/user.jpg" height="41" width="41" alt="User">
						<strong><?php echo $_SESSION["user_full_name"]?></strong>
				</td>
				<td style="padding-right:11px;align:right">
					<strong>
						<a href="<?php echo $root_dir; ?>logout">
							<img src="<?php echo $root_dir1;?>/public/images/logout.png" height="41" width="41" alt="Logout">
						</a>
					</strong>
				</td>
			</tr>
		</table>
	</div>
</div>';
        }else{
            $str = '<div class="row">
	<div class="col-sm-12" style="padding-left:0px;padding-right:0px">
		<table class="table table-stripped">
			<tr bgcolor="#e4f1fe">
				<td style="padding-left:51px;width:80%;align:left">
					<strong>
						<a href="<?php echo $root_dir;?>home" >
							<img src="<?php echo $root_dir1;?>/public/images/home.png" height="41" width="41" alt="Home">
						</a>
					</strong>
				</td>
				<td style="color:#446cb3;font-size:21px;width:20%">
						<img src="<?php echo $root_dir1;?>/public/images/user.jpg" height="41" width="41" alt="User">
						<strong><?php echo "Welcome User"?></strong>
				</td>
			</tr>
		</table>
	</div>
</div>';
        }
        return $str;
    }
    
    public function createIndexView(){
        
        $str = '
<br/><br/>
<div class="row">
	<br/><br/>
	<div class="col-sm-3"></div>
	<div class="col-sm-6">
		<form method="POST">
			<table class="table table-bordered">
				<?php
					if(!empty($err_message)){
						echo "<tr>
							<td align=\'cente\' colspan=\'2\'>
								<h3 style=\'color:red;\'><strong>".$err_message."</strong></h3>
							</td>
						</tr>";
					}
				?>
                <tr>
					<td align="center" colspan="2">
						<h3>
							<u>Login Here</u>
						</h3>
					</td>
				</tr>
				<tr>
					<td align="center">
						<strong>Username : </strong>
					</td>
					<td align="center">
						<input type="text" name="username" class="form-control" placeholder="Enter username" required>
					</td>
				</tr>
				<tr>
					<td align="center">
						<strong>Password : </strong>
					</td>
					<td align="center">
						<input type="password" name="password" class="form-control" placeholder="Enter password" required>
					</td>
				</tr>
				<tr>
					<td align="center" colspan="2">
						<input type="submit" name="login" value="Login" class="btn btn-primary">
					</td>
				</tr>
			</table>
		</form>
	</div>
</div>
';
        
        return $str;
    }
    
    public function createFormView($projectTitle,$table,$colList,$fType=NULL,$id){
       
       
        $fieldType = '';
        $inputType = '';
		$hiddenField = '<input type=\'hidden\' name=\''.$id.'\' value=\'".$_REQUEST["'.$id.'"]."\'>'.PHP_EOL;
        for($i = 0;$i<count($colList);$i++){
            $column = explode("-",$colList[$i]);
            
            $fieldType = substr($column[1],0,4);
            $hiddenField .= '<input type=\'hidden\' name=\''.$column[0].'\' value=\'".$_REQUEST["'.$column[0].'"]."\'>'.PHP_EOL;
            
            if(strcmp($fieldType,'enum')==0){
               
                $len = strlen($column[1]);
                $tempArr = explode(",",substr($column[1],5,($len-6)));
                
                if(!empty($fType)){
                    $inputType .= '<tr valign="top">
							     <td><strong>'.ucwords(strtolower($column[0])).'</strong></td>
							     <td>';
                    if(strcmp($fType[$i],"dropdown")!=0){
                        for($k=0;$k<count($tempArr);$k++){
                            $temp = substr($tempArr[$k], 1,(strlen($tempArr[$k])-2));
                            $inputType .= '<input type="'.$fType[$i].'" name="'.$column[0].'" id="'.$column[0].'" value="'.$temp.'"<?php if($_REQUEST["'.$column[0].'"]=="'.$temp.'") echo "CHECKED";?>>'.ucwords(strtolower($temp)).'<br/>';
                        }
                    }else{
                        $inputType .= '<select name="'.$column[0].'" id="'.$column[0].'" class="form-control" required="required">
                                        <option value="">Select '.ucwords(strtolower($column[0])).'</option>';
                        for($k=0;$k<count($tempArr);$k++){
                            $temp = substr($tempArr[$k], 1,(strlen($tempArr[$k])-2));
                            $inputType .= '<option value="'.$temp.'"<?php if($data["'.$column[0].'"]=="'.$temp.'") echo "SELECTED";?>>'.ucwords(strtolower($temp)).'</option>';
                        }
                        $inputType .= '</select>';
                    }
                    $inputType .= '</td>
						      </tr>';
                }else{
                    $inputType .= '<tr valign="top">
							     <td><strong>'.ucwords(strtolower($column[0])).'</strong></td>
							     <td>
								     <input type="text" name="'.$column[0].'" class="form-control" id="'.$column[0].'" value="<?php echo $data["'.$column[0].'"];?>" placeholder="Enter '.ucwords(strtolower($column[0])).'" required="required">
							     </td>
						     </tr>';
                }
            }  else{
                $maxlength = '';
                if(strcmp($fType[$i],"date")==0){
                    $maxlength = '';
                }else{
                    $numStr = strchr($column[1],"(");
                    $maxlength = 'maxlength="'.substr($numStr,1,strlen($numStr)-2).'"';
                }
                $inputType .= '<tr valign="top">
							     <td><strong>'.ucwords(strtolower($column[0])).'</strong></td>
							     <td>
								     <input type="'.$fType[$i].'" name="'.$column[0].'" class="form-control" id="'.$column[0].'" value="<?php echo $data["'.$column[0].'"];?>" placeholder="Enter '.ucwords(strtolower($column[0])).'" '.$maxlength.' required>
							     </td>
						     </tr>';
            }
        }
        
        $str = '
    <div class="row">
		<div class="col-sm-12" align="left">
			<a href="show'.ucwords(strtolower($table)).'Data">
				<img src="<?php echo $root_dir1;?>/public/images/goBack.png" height="31" width="101">
			</a>			
		</div>
	</div>
    <br/>
	<div class="row">
		<div class="col-sm-6" style="padding-left:11px;padding-right:11px;width:100%">
			<form method="POST" action="<?php if(isset($_REQUEST["'.$id.'"]))echo \'update\';else echo \'insert\'?>'.ucwords(strtolower($table)).'Data" enctype="multipart/form-data">
				<table class="table table-bordered">
                    <?php
						if(isset($msg)){
							echo "<tr>
								<td colspan=\'2\' align=\'center\'>
									<h4 style=\'color:green;\'>".$msg."</h4>
								</td>
							</tr>";
						}
					?>
					<tr>
						<td colspan="2" align="center">
							<h3><u>Enter Details</u></h3>
						</td>
					</tr>
					'.$inputType.'
					<tr>
						<td>
							<?php
								$id = "";
								$value = "";
								$data = "";
								if(isset($_REQUEST["'.$id.'"])){
									$button_id = "update";
									$value = "Update";
									$name = "update";
									$data = "<input type=\'hidden\' name=\''.$id.'\' id=\''.$id.'\' value = \'".$_REQUEST["'.$id.'"]."\'>
                                            <input type=\'hidden\' name=\'token\' id=\'token\' value=\'token\'>";
								}else{
									$button_id = "save";
									$value = "Save";
									$name = "save";
									$data = "";
								}
									    
								echo $data."<br/>
								<input type=\'submit\' id=\'".$button_id."\' name=\'".$name."\' class =\'btn btn-primary btn-md\' value=\'".$value."\'>";
							?>
						</td>
						<td>
							<input type="reset" class = "btn btn-primary" value="Clear">
						</td>
					</tr>
				</table>
			</form>
		</div>
	</div>';
        return $str;
    }
    
    public function createTableView($table,$colList,$id,$fieldType){
        $colHeading = '';
        $colData = '';
        $tdCount = '';
        
        for($i = 0;$i<count($colList);$i++){
            $column = explode("-",$colList[$i]);
            $colHeading .= '<td align="center"><strong>'.ucwords(strtolower($column[0])).'</strong></td>'.PHP_EOL;
			$colData .= '<td align=center>
                        ".$val["'.$column[0].'"]."
                        </td>'.PHP_EOL;
            $tdCount++;
            
        }
        $tdCount += 2;
        $str = '<br/><br/>
	<div class="row">
		<div class="col-sm-6" style="padding-left:0px;padding-right:0px;width:100%">
			<table class= "table table-stripped">
                <tr>
                    <td align="left" colspan="'.$tdCount.'">
						<a href="<?php echo $root_dir;?>'.$table.'Form" style="color:#446cb3;font-size:21px;">
							<img src="<?php echo $root_dir1;?>/public/images/insert.png" height="31" width="31">
							<strong>Insert New Record</strong>
						</a>
					</td>
                </tr>
				<tr>
                    <td align="center"><strong>Delete</strong></td>
                    <td align="center"><strong>Edit</strong></td>
					'.$colHeading.'
                    
				</tr>
					    
				<?php
					foreach($data as $key=>$val)
					{
						echo "<tr> 
                             <form action=\'".update'.ucwords(strtolower($table)).'Data."\' method=\'POST\'>";
                        echo "<td align=\'center\'><a href=\'delete'.ucwords(strtolower($table)).'Data?'.$id.'=".$val["'.$id.'"]."\' onclick=\'return checkDelete()\'>
                            <img src=\'../public/images/delete.png\' alt=\'delete\' height=\'36\' width=\'36\'>
                        </a></td>";
						echo "<td align=\'center\'>
						<a href=\'update'.ucwords(strtolower($table)).'Data?'.$id.'=".$val["'.$id.'"]."\'>
                            <img src=\'../public/images/edit.png\' alt=\'delete\' height=\'36\' width=\'36\'>
                        </a>
						</td>";
                       
						echo "'.$colData.'";
                        echo "</form>
                            </tr>";
					}
				?>
			</table>
		</div>
	</div>
    <script>
        function checkDelete(){
            return confirm(\'Are you sure?\');
        }
    </script>';
        return $str;
    }

    //for user functionality neccessary files
    /**start**/
    
    public function createPasswordResetView(){
        
        $str = '<!-- changes password of logged in user -->
<div align="center">
	<div class="row">
		<div class="col-sm-3"></div>
		<div class="col-sm-6">
		<form id="aa" action="" method="post" > 
		<table class="table table-bordered">
		<tr><td colspan="2" align="center"><h3><u>Change password</u></h3><br><?php echo "<span style=\'color:green\'>".$message."</span>" ?></td></tr>
			<?php
				if(!empty($error_array)){
					if($error_array["old_pwd"])
						echo "<tr><td style=\'color:red\' colspan=\'2\' align=\'center\'>".$error_array["old_pwd"]."</td></tr>";
						
					if($error_array["new_pwd"])
						echo "<tr><td style=\'color:red\' colspan=\'2\' align=\'center\'>".$error_array["new_pwd"]."</td></tr>";
						
					if($error_array["cnf_pwd"])
						echo "<tr><td style=\'color:red\' colspan=\'2\' align=\'center\'>".$error_array["cnf_pwd"]."</td></tr>";
					
					if($error_array["match"])
						echo "<tr><td style=\'color:red\' colspan=\'2\' align=\'center\'>".$error_array["match"]."</td></tr>";
				}
			?>
		  <tr>
			<td>Enter old password :</td>
			<td><input type="password" name="old_pwd" id="old_pwd" class="form-control"></td>
		  </tr>
		  
		  <tr>
			<td>Enter New Password :</td>
			<td><input type="password" name="new_pwd" id="new_pwd" class="form-control"></td>
		  </tr>
		  
		  <tr>
			<td>Confirm New Password :</td>
			<td><input type="password" name="cnf_pwd" id="cnf_pwd" class="form-control"></td>
		  </tr>
		   
		  <tr>
		<td colspan="2" align="center"><input type="submit" name="save"  value="save" class="btn btn-primary"/></td>
		  </tr>
		</table>
		</form>
    </div>
	</div>
</div>';
        return $str;
        
    }
    
    public function createAddUserView(){
        
        $str = '<?php 
error_reporting(0);
	$ab=new element();	
?>
<div class="row">
	<div class="col-sm-3"></div>
	<div class="col-sm-6">
		<form action="" method="POST">
			<table class="table table-bordered">
				 <?php
						if(isset($msg)){
							echo "<tr>
								<td colspan=\'2\' align=\'center\'>
									<h4 style=\'color:green;\'>".$msg."</h4>
								</td>
							</tr>";
						}
					?>
				<tr>
					<td colspan="3" align="center">
						<h3><u>User Details</u></h3>
						<?php echo "<span style=\'color:green\'>".$message."</span><br/><span style=\'color:red\'>".$message1."</span>"?>
					</td>
				</tr>
				<?php
					
					if(!empty($error_array)){
						if($error_array["user_name"])
							echo "<tr><td style=\'color:red\' colspan=\'2\' align=\'center\'>".$error_array["user_name"]."</td></tr>";
						
						if($error_array["user_fullName"])
							echo "<tr><td style=\'color:red\' colspan=\'2\' align=\'center\'>".$error_array["user_fullName"]."</td></tr>";
						
						if($error_array["user_role"])
							echo "<tr><td style=\'color:red\' colspan=\'2\' align=\'center\'>".$error_array["user_role"]."</td></tr>";
					}
				?>
				<tr>
					<td><label><b>Username : </b></label></td>
					<td><input type="text" name="user_name" value="<?php echo $data["username"];?>" class="form-control"id="user_name" placeholder="Username" <?php if($data["username"]) echo "disabled";?>>
						<input type="hidden" name="id" value="<?php echo $data["id"];?>">
					</td>	
				</tr>
				<tr>
					<td><label><b>User Full Name : </b></label></td>
					<td><input type="text" name="user_fullName" value="<?php echo $data["user_fullname"];?>" class="form-control" id="user_fullName" placeholder="User Full Name" <?php if($data["user_fullname"]) echo "disabled";?>></td>	
				</tr>
				<tr>
					<td><label><b>User Role : </b></label></td>
					<td>
						<select name="user_role" value="" class="form-control" id="user_role">
							<option value="">User Role</option>
							<option value="admin"<?php if($data["user_role"]=="admin") echo "SELECTED";?>>Administrator</option>
							<option value="guest"<?php if($data["user_role"]=="guest") echo "SELECTED";?>>Guest</option>
							<option value="standardUser"<?php if($data["user_role"]=="standardUser") echo "SELECTED";?>>Standard User</option>
						</select>
					</td>	
				</tr>
				<?php if($data["user_fullname"]){?>
				<tr>
					<td><label><b>User Status : </b></label></td>
					<td>
						<select name="user_status" value="" class="form-control" id="user_role">
							<option value="">User Status</option>
							<option value="Active"<?php if($data["user_status"]=="Active") echo "SELECTED";?>>Active</option>
							<option value="Blocked"<?php if($data["user_status"]=="Blocked") echo "SELECTED";?>>Blocked</option>
						</select>
					</td>	
				</tr>
				<?php }?>
				<tr>
					<td colspan="2" align="center">
					<button type="submit" name="save_changes" class="btn btn-primary" value="save">Save</button> 
					</td>
				</tr>			 
			</table>
		</form>
	</div>	
</div>';
        return $str;
        
    }
    
    public function createUserView(){
		$str = '<br/><br/>
	<div class="row">
	<div class="col-sm-3"></div>
		<div class="col-sm-6">
			<table class= "table table-stripped">
                <tr>
                    <td align="left" colspan="6">
						<a href="<?php echo $root_dir;?>add_newuser" style="color:#446cb3;font-size:21px;">
							<img src="<?php echo $root_dir1;?>/public/images/addUser.png" height="31" width="31">
							<strong>Add New User</strong>
						</a>
					</td>
                </tr>
				<tr>
                    <td align="center"><strong>Delete</strong></td>
                    <td align="center"><strong>Edit</strong></td>
					<td align="center"><strong>Username</strong></td>
					<td align="center"><strong>Full Name</strong></td>
					<td align="center"><strong>Role</strong></td>
					<td align="center"><strong>Status</strong></td>

                    
				</tr>
					    
				<?php
				
					foreach($data as $key=>$val)
					{
						echo "<tr>";
                        echo "<td align=\'center\'><a href=\'deleteuser?id=".$val["id"]."\' onclick=\'return checkDelete()\'>
                            <img src=\'../public/images/delete.png\' alt=\'delete\' height=\'36\' width=\'36\'>
                        </a></td>";
						echo "<td align=\'center\'>
								<a href=\'update_user?id=".$val["id"]."\'>
								<img src=\'../public/images/edit.png\' alt=\'edit\' height=\'36\' width=\'36\'></a>
							</td>";
                       
						echo "<td align=center>
                        ".$val["username"]."
                        </td>
						<td align=center>
                        ".$val["user_fullname"]."
                        </td>
						<td align=center>
                        ".$val["user_role"]."
                        </td>
						<td align=center>
                        ".$val["user_status"]."
                        </td>
						
                       </tr>";
					}
				?>
			</table>
		</div>
	</div>
    <script>
        function checkDelete(){
            return confirm(\'Are you sure?\');
        }
    </script>';
		return $str;
	}
    /**end**/
    /**end**/
    
    //Root files
    /**start**/
    public function createRootIndex(){
        $str = '<?php

		header("X-Frame-Options: DENY");

		if ( ! isset($_SERVER["HTTPS"])) { 
		}
		session_start();  

		/*** error reporting on ***/
		error_reporting("E_All");

		ob_end_clean(); // remove output buffers
		ob_implicit_flush(true);
		set_time_limit(0);
		date_default_timezone_set("Asia/Calcutta");
		setlocale(LC_MONETARY, "en_IN");
		if(isset($_SERVER["HTTP_REFERER"])) {
			// echo $_SERVER["SERVER_ADDR"];
		}

		$info = getdate();
		$date = $info["mday"];
		$month = $info["mon"];
		$year = $info["year"];
		$hour = $info["hours"];
		$min = $info["minutes"];
		$sec = $info["seconds"];
		$current_date = "$date/$month/$year == $hour:$min:$sec";
		$current_time = "$hour:$min";


		 /*** define the site path ***/
		 $site_path = realpath(dirname(__FILE__));
		 define ("__SITE_PATH", $site_path);

		 /*** include the init.php file ***/
		 include "includes/init.php";

		 /*** load the router ***/
		 $registry->router = new router($registry);

		 /*** set the controller path ***/
		 $registry->router->setPath (__SITE_PATH . "/controller");

		 /*** load up the template ***/
		 $registry->template = new template($registry);

		 /*** load the controller ***/
		 $registry->router->loader();

		?>';
        return $str;
    }
    
    
    public function createConfigurationFile($rdbms,$host,$db,$username,$password){
        $str = '<?php 
		class DTConfig{
			const dbtype	=	"'.$rdbms.'";
			const host		=	"'.$host.'";
			const dtb		=	"'.$db.'";
			const user		=	"'.$username.'";
			const password	=	"'.$password.'";
		}

		class DBConnect extends DTConfig{
			private $dbh;
			private static $instance = null;
			private function __construct(){
			try {
					$dsn    = DTConfig::dbtype.":host=".DTConfig::host.";dbname=".DTConfig::dtb;
					$user   = DTConfig::user;
					$dbname = DTConfig::password;
					$initArr = array(\PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8, time_zone = \'+05:30\'");


					$this->dbh = new PDO($dsn,$user,$dbname, $initArr);
					$this->dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

				} catch(PDOException $e) {

					echo "ERROR: " . $e->getMessage();
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
		?>';
        return $str;
    }
    
    
    /**end**/
    
    //model files
    /**start**/
    public function createModel($table,$colList,$id){
        $str = '
	public function show'.ucwords(strtolower($table)).'Data(){
		$query = "SELECT * FROM '.$table.'";
		$ab = $this->pdo->prepare($query);
		$ab->execute();
		return $ab->fetchAll(PDO::FETCH_ASSOC);
	}
	
    public function show'.ucwords(strtolower($table)).'DataById($id){
		$query = "SELECT * FROM '.$table.' WHERE '.$id.'=".$id."";
		$ab = $this->pdo->prepare($query);
		$ab->execute();
		return $ab->fetch(PDO::FETCH_ASSOC);
	}
				    
	public function insert'.ucwords(strtolower($table)).'Data(){
		$queryObj = new queryData();
		$insertData = $queryObj->'.$table.'DataQuery();
        $insertValues = $queryObj->'.$table.'Values();
        $insertDataArr = explode("-",$insertData);
		try{
			$query = "INSERT INTO '.$table.' (".$insertDataArr[0].") VALUES(".$insertDataArr[1].")";
			$sth = $this->pdo->prepare($query);
			if($sth->execute($insertValues))
				return $this->pdo->lastInsertId();
		}catch(Exception $e){
			echo $e->getMessage();					    
		}
	}
	
	public function update'.ucwords(strtolower($table)).'Data($id){
        $queryObj = new queryData();
        $updateData = $queryObj->'.$table.'DataQuery();
        $updateDataArr = explode("-",$updateData);
        $updateValues = $queryObj->'.$table.'Values();
		try{
			$query = "UPDATE '.$table.' SET ".$updateDataArr[2]." WHERE '.$id.'=".$id."";
			$sth = $this->pdo->prepare($query);
			$sth->execute($updateValues);
            return true;
		}catch(Exception $e){
			echo $e->getMessage();
		}
	}
	
	public function delete'.ucwords(strtolower($table)).'Data($id){
		$query = "DELETE FROM '.$table.' WHERE '.$id.'=".$id."";
		$sth = $this->pdo->prepare($query);
		$sth->execute();
		return true;
	}
';
        return $str;
    }
    
    public function addLoginToModel($db){
        
        $str = '
	public function select_username(){
        $query = "SELECT * FROM '.$db.'_users WHERE 1=1 ";
		$sth = $this->pdo->prepare($query);
        $sth->execute();
        return $sth->fetchAll(PDO::FETCH_ASSOC);
    }
	
	public function getUserById($id){
        $query = "SELECT * FROM '.$db.'_users WHERE id=".$id."";
		$sth = $this->pdo->prepare($query);
        $sth->execute();
        return $sth->fetch(PDO::FETCH_ASSOC);
    }
	            
	public function add_users(){
        $status = "Active";
		$query = "INSERT INTO '.$db.'_users (username,user_fullname,password,user_status,user_role) VALUES(:user_name,:user_fullName,\'".sha1("12345")."\',\'".$status."\',:user_role)";
        $sth = $this->pdo->prepare($query);
        $sth->execute(
            array(
                "user_name" => $_POST["user_name"],
                "user_fullName" => $_POST["user_fullName"],
                "user_role" => $_POST["user_role"]
            )
        );
        return $this->pdo->lastInsertId();
    }
	
	public function delete_user($id){
		$query = "DELETE FROM '.$db.'_users WHERE id=".$id."";
        $sth = $this->pdo->prepare($query);
        $sth->execute();
        return true;
    }
	
	public function update_user(){
		try{
			$query = "UPDATE '.$db.'_users SET user_status=:user_status,user_role=:user_role WHERE id=:id";
			$sth = $this->pdo->prepare($query);
			$sth->execute(
				array(
					"id" => $_POST["id"],
					"user_status" => $_POST["user_status"],
					"user_role" => $_POST["user_role"]
				)
			);
        return true;
		}catch(Exception $e){
			echo $e->getMessage();
		}
    }
	            
}
?>';
        
        return $str;
    }
    
    
    //utility file
    public function createQueryDataFile($table,$colList,$fieldType,$id){
        
        $varCol = '';
        
        for($i=0;$i<count($colList);$i++){
            $temp = explode("-",$colList[$i]);
            if($i == (count($colList)-1)){
                $varCol .= $temp[0].'='.$fieldType[$i].''.PHP_EOL;
            }
            else{
                $varCol .= $temp[0].'='.$fieldType[$i].','.PHP_EOL;
            }
        }
        $str = '
		public function '.$table.'DataQuery(){
		    
            $id = "'.$id.'";
			$varNameType = "'.$varCol.'";
			$varIns = "";
            $varCol = "";
			$varUp = "";
            $varColType = "";
			$tempArr = explode(",",$varNameType);
			    
			for($i=0;$i<(count($tempArr)-1);$i++){
               $trimmedData = trim($tempArr[$i]);
               $trimmedCol = explode("=",$trimmedData);
			   $varIns .= ":".$trimmedCol[0].", "; //columns for insertion values
               $varCol .= $trimmedCol[0] . ", "; //columns for insertion
               $varColType .= $trimmedCol[1]."=";	// columns type
			   $varUp .= "".$trimmedCol[0]."=:".$trimmedCol[0].", "; //columns for updation
			}
             $trimmedData = trim($tempArr[count($tempArr)-1]);
             $trimmedCol = explode("=",$trimmedData);
             $varColType .= $trimmedCol[1];
              $varCol .=  $trimmedCol[0];
			 $varIns .= ":".$trimmedCol[0];
			 $varUp .= "".$trimmedCol[0]."=:".$trimmedCol[0];
			    
			return $varCol."-".$varIns."-".$varUp."-".$varColType."-".$id;
		}
        public function '.$table.'Values($id=null,$ext=null){
            $data = $this->'.$table.'DataQuery();
                
			$tempArr = explode(",",explode("-",$data)[0]);//print_r($tempArr);die;
			$tempArrType = explode("=", explode("-", $data)[3]);
			$varValues = array();
			for($i = 0;$i<count($tempArr);$i++){
                $trimmedData = trim($tempArr[$i]);
                if( strcmp($tempArrType[$i],"file")==0){
				    $varValues[$trimmedData] = $id."_".$trimmedData."_'.$table.'".".".$ext."";
			    }else{
				    $varValues[$trimmedData] = $_POST[$trimmedData];
			    }
			}
				        
            return $varValues;
        }
	';
        return $str;
    }
    
    public function createIndexModel($db){
        
        $str = '<?php
error_reporting(0);

class index_Model{

    protected $pdo = null;

    public function __construct(PDO $pdo) { 
        $this->pdo = $pdo;// Dependency Injection
    }

    public function login($username, $password){
        $pass1 = hash("sha1", $password);
		$query = "SELECT * FROM '.$db.'_users WHERE username=:user && password=:pass";
        $sth = $this->pdo->prepare($query);
        $sth->bindValue(":user", $username);
        $sth->bindValue(":pass", $pass1);
        $sth->execute();
        if ($sth->rowCount() == 1) {
            $sth->closeCursor();
            return true;
        }
        $sth->closeCursor();
        return false;
    }

    public function single_user($username){
		$query = "SELECT * FROM '.$db.'_users WHERE username = \'" . $username . "\'";
        $sth = $this->pdo->prepare($query);
        $sth->execute();
        return $sth->fetchAll(PDO::FETCH_ASSOC);
    }

    public function loginDetails(){
        $query = "INSERT INTO '.$db.'_user_log
				(username,loggedAt,ip) 
				VALUES(:username,NOW(),:ip)";
        $sth = $this->pdo->prepare($query);
        $sth->execute(array(
            "username" => $_POST["username"],
            "ip" => $_SERVER["REMOTE_ADDR"]
        ));
        
        return $this->pdo->lastInsertId();
    }
	
	public function changePass(){
		$query = "UPDATE '.$db.'_users SET password=:password WHERE id=:id";
		$sth = $this->pdo->prepare($query);
		$sth->execute(array(
				"password"  =>  sha1($_POST["new_pwd"]),
				"id"=> $_SESSION["id"]
		));
	}
}

?>';
        return $str;
    }
    
    public function createElementClassFile(){
        
        $str = '<?php

class element{

 public function openform($method,$action,$others){
   return "<form class=\'form-horizontal\' role=\'form\' method=\'".$method."\' action=\'".$action."\' ".$others.">"; 
 }
 
 
 public function closeform(){
   return "</form>"; 
 }
 
 
 public function text($name_field,$value,$others){
   return "<input type=\'text\' name=\'".$name_field."\' value=\'".$value."\' ".$others.">"; 
 }
 
 public function textpassword($name_field,$value,$others){
   return "<input type=\'password\' name=\'".$name_field."\' value=\'".$value."\' ".$others.">"; 
 }
 
 public function email($name_field,$value,$others){
   return "<input type=\'text\' name=\'".$name_field."\' value=\'".$value."\' ".$others.">"; 
 }
 

 
 public function uploadfile($name_field,$value,$others){
   return "<input type=\'file\' name=\'".$name_field."\' value=\'".$value."\' ".$others.">";   
 }
 
 
 public function hiddentext($name_field,$value,$others){
 return "<input type=\'hidden\' name=\'".$name_field."\' value=\'".$value."\' ".$others.">";    
 }
 
 public function submit($name_field,$value,$others){
 return "<input type=\'submit\' name=\'".$name_field."\' value=\'".$value."\' ".$others.">";    
 }
 
 public function reset($name_field,$value,$others){
 return "<input type=\'reset\' name=\'".$name_field."\' value=\'".$value."\' ".$others.">";    
 }
 
 public function select_drop($select_array,$name_select,$firstrow="Select",$firstrowvalue=0,$selected,$extra){
    
$a="<select name=\'".$name_select."\' ".$extra.">";

$a.="<option value=\'".$firstrowvalue."\'>".$firstrow."</option>";

foreach($select_array as $key=>$val)
{
$a.="<option value=\'".$key."\' ";

if($key==$selected)
{
$a.="selected";
}

$a.=">".$val."</option>";
}

$a.="</select>";
return $a;
}


function yeardrop($name,$currentbehind,$currentforward,$selected,$extras){

$currentyear=date("Y");

$a="<select name=\'".$name."\' ".$extras.">";
$a.="<option value=\'\'>Select</option>";
for($i=$currentyear-$currentbehind;$i<=$currentyear+$currentforward;$i++)
{
    $a.="<option value=\'".$i."\'";
    if($i==$selected)
    {
        $a.=" selected";
    }
    $a.=" >".$i."</option>";
}

$a.="</select>";

return $a;
}



function check_buttons($check_array,$check_name,$check_checked,$extra,$breakafter){
$a="<div style=\'float:left;width:100%;\' id=\'".$check_name."\'>";

$percentage=100/$breakafter;

foreach($check_array as $key=>$val)
{
$a.="<div style=\'float:left;width:".round($percentage)."%;\'>";

if($key=="Others"){
 $lan_o=$check_checked[5];
 $a.=\'Others :<input type="text" name="lang_other" id="lang_other" value="\'.$lan_o.\'" maxlength="200" style="width:100%">\';
 
}
else{

$a.="<input type=\'checkbox\' name=\'lang_".strtolower($key)."\' value=\'".$key."\'";

if(in_array($key,$check_checked)) 
{
$a.=" checked";
}

$a.=" ".$extra."> ".$val."";

$a.="</div>";
}


}



$a.="</div>";



return $a;

}


function radio_buttons($radio_array,$radio_name,$radio_checked,$extra,$breakafter){
$a="<div style=\'float:left;width:100%;border:0px solid #cccccc;\' class=\'btn-group\' data-toggle=\'buttons\'>";

$percentage=100/$breakafter;

foreach($radio_array as $key=>$val){

$ra=\'\';
if($key==$radio_checked){
$ra=" active";
}
$a.="<label style=\'padding:10px;\' class=\'btn btn-default".$ra."\'>
<input type=\'radio\' name=\'".$radio_name."\' value=\'".$key."\'";
if($key==$radio_checked){
$a.=" checked";
}
$a.=" ".$extra.">".$val."</label>";


}

$a.="</div>";

return $a;

}



function coretextarea($fieldset="yes",$action,$value,$name,$id,$fieldid,$rows,$cols,$legendname,$labelname,$rest="",$required="yes",$break="yes",$resize="no",$maxlength="4000")
{
    if($fieldset=="yes"){
        $output=\'<fieldset id="\'.$fieldid.\'" ><legend>\'.$legendname.\':</legend>\';
    }
    
    
        
       if($required=="yes"){
        $output.=\'<span class="astrish">*&nbsp;</span>\';
       }
        
         if($break=="yes"){
            $output.=\'<br/>\';
       }
        
        $output.=\'<textarea name="\'.$name.\'"  id="\'.$id.\'" rows="\'.$rows.\'" cols="\'.$cols.\'"\';
        if($required=="yes"){
            $output.=\' class="required" \';
        }
        
        $output.=\' style="\';
        
        if($resize=="no"){
            $output.=\' resize:none;\';
        }
        
        $output.=\'\'.$rest.\'" maxlength="\'.$maxlength.\'">\'.$value.\'</textarea>\';
        
        if($break=="yes"){
            $output.=\'<br/>\';
       }
    
    if($fieldset=="yes"){
        $output.=\'</fieldset>\';
    }
   
   return $output;
}
    
}';
        
        return $str;
    }
    /**end**/
   
    
}
?>
