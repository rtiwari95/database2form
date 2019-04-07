<?php
 //require("basicFiles.php");
error_reporting(E_ALL);
Class indexController Extends baseController {

	private $root_dir;
	private $root_dir1;
	
    function __construct($registry) {
        parent::__construct($registry);
       
        $this->root_dir="http://".$_SERVER["SERVER_NAME"]."/database2form/";
        $this->root_dir1="http://".$_SERVER["SERVER_NAME"]."/database2form/";
		$this->registry->template->root_dir="http://".$_SERVER["SERVER_NAME"]."/database2form/index/";
		$this->registry->template->root_dir1="http://".$_SERVER["SERVER_NAME"]."/database2form/";
		
		
    }
    
	public function index() {
		/*** set a template variable ***/
			$this->registry->template->welcome = 'Welcome to PHPRO MVC';
		/*** load the index template ***/
			$this->registry->template->show('header');
			$this->registry->template->show('index');
			$this->registry->template->show('footer');
	}
	
	public function list_databases(){
	   
	    $admin_obj = new admin_Model($this->registry->db);
	    $result = $admin_obj->list_databases();
	    $this->registry->template->data = $result;
	    $this->registry->template->show('header');
	    $this->registry->template->show('listDatabases');
	    $this->registry->template->show('footer');
	    
	}
	
	public function list_tables(){
	   
		$admin_obj = new admin_Model($this->registry->db);
		$result = $admin_obj->list_tables($_REQUEST['database']);
		$this->registry->template->data = $result;
		$this->registry->template->show('header');
		$this->registry->template->show('listTables');
		$this->registry->template->show('footer');
	}
	
	public function selected_tables(){
	    $tables = $_REQUEST['table'];
	    $db = $_REQUEST['database'];
	    $adminModelObj = new admin_Model($this->registry->db);
	    $col = array();
	    for($i=0;$i<count($tables);$i++){
	        $columns = $adminModelObj->list_columns($tables[$i], $db);
	        
	        for($j=0;$j<count($columns);$j++){
	            $col[$i][$j] = $columns[$j]['Field'].'-'.$columns[$j]['Type'].'-'.$columns[$j]['Null'];
	        }
	    }
	    $this->registry->template->cols = $col;
	    $this->registry->template->database = $db;
	    $this->registry->template->data = $tables;
	    $this->registry->template->show('header');
	    $this->registry->template->show('selectedTables');
	    $this->registry->template->show('footer');
	}
	
	public function createZip($source, $destination){
	    if (!extension_loaded('zip') || !file_exists($source)) {
	        return false;
	    }
	    
	    $zip = new ZipArchive();
	    if (!$zip->open($destination, ZIPARCHIVE::CREATE)) {
	        return false;
	    }
	    $source = str_replace('\\', '/', realpath($source));
	    if (is_dir($source) === true){
	        $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($source), RecursiveIteratorIterator::SELF_FIRST);
	        foreach ($files as $file){
	            $file = str_replace('\\', '/', $file);
	            // Ignore "." and ".." folders
	            if( in_array(substr($file, strrpos($file, '/')+1), array('.', '..')) )
	                continue;
	                
	                $file = realpath($file);
	                if (is_dir($file) === true){
	                    $zip->addEmptyDir(str_replace($source . '/', '', $file . '/'));
	                }
	                else if (is_file($file) === true){
	                    $zip->addFromString(str_replace($source . '/', '', $file), file_get_contents($file));
	                }
	        }
	    }
	    else if (is_file($source) === true){
	        $zip->addFromString(basename($source), file_get_contents($source));
	    }
	    return $zip->close();
	}
	
	
	public function createProject(){
	    /**data from form(REQUEST ,POST or GET methods)**/
	    $tables = $_REQUEST['table']; //list of tables for creating form.
	    $db = $_REQUEST['database']; //database
	    $host = $_REQUEST['host'];
	    $rdbms = $_REQUEST['rdbms']; //rdbms
	    $username = $_REQUEST['username'];//username
	    $password = $_REQUEST['password'];//password
	     
	    
	    //Title of the project according to user's choice
	    
	    
	    $tempArr = explode(".",$host);
	    $endStr = '';
	    if(count($tempArr)<4){
	        $endStr = 'local';
	    }else{
	        $endStr = $tempArr[3];
	    }
	    
	    if(!empty($_REQUEST['project_title'])){
	        $projectTitle = $_REQUEST['project_title'];
	    }else{
	        $projectTitle = $db.'_'.$endStr;
	    }
	    
	    $this->createFiles($rdbms, $host, $username, $password, $db, $tables,$projectTitle);
	    
	    if(isset($_POST['createProject'])){
	       
	        $source = $_SERVER['DOCUMENT_ROOT']."/".$projectTitle."/";
	        $zip_name = $projectTitle.".zip";
	        $res = $this->createzip($source, $zip_name);
	        if($res){ 
	            header('Content-type: application/zip');
	            header('Content-Disposition: attachment; filename="'.$zip_name.'"');
	            readfile($zip_name);
	            unlink($zip_name);
	        }else{
	            print_r($res);
	        }
	    }
	}
	
	
	public function createLibraryFiles($basicFilesObj,$root_path,$projectTitle,$loginRequired=NULL){
	    
	    //application folder files
	    /**start**/
	    
	    if (!file_exists($root_path.'/'.$projectTitle.'/application')) {
	        mkdir($root_path.'/'.$projectTitle.'/application', 0777, true);
	    }
	    
	    $st = $basicFilesObj->createBaseController();
	    $file = fopen($root_path.'/'.$projectTitle.'/application/controller_base.class.php', "w");
	    fwrite($file, $st);
	    fclose($file);
	    
	    $st = $basicFilesObj->createRegistryClass();
	    $file = fopen($root_path.'/'.$projectTitle.'/application/registry.class.php', "w");
	    fwrite($file, $st);
	    fclose($file);
	    
	    $st = $basicFilesObj->createRouterClass();
	    $file = fopen($root_path.'/'.$projectTitle.'/application/router.class.php', "w");
	    fwrite($file, $st);
	    fclose($file);
	    
	    $st = $basicFilesObj->createTemplateClass();
	    $file = fopen($root_path.'/'.$projectTitle.'/application/template.class.php', "w");
	    fwrite($file, $st);
	    fclose($file);
	    
	    
	    $st = $basicFilesObj->createValidationsClass();
	    $file = fopen($root_path.'/'.$projectTitle.'/application/validations.class.php', "w");
	    fwrite($file, $st);
	    fclose($file);
	    
	    /**end**/
	    
	    //controller folder files
	    /**start**/
	    
	    if (!file_exists($root_path.'/'.$projectTitle.'/controller')) {
	        mkdir($root_path.'/'.$projectTitle.'/controller', 0777, true);
	    }
	    
	    $st = $basicFilesObj->createError404File();
	    $file = fopen($root_path.'/'.$projectTitle.'/controller/error404.php', "w");
	    fwrite($file, $st);
	    fclose($file);
	    
	    $st = $basicFilesObj->createIndexController($projectTitle,$loginRequired);
	    $file = fopen($root_path.'/'.$projectTitle.'/controller/indexController.php', "w");
	    fwrite($file, $st);
	    fclose($file);
	    
	    /**end**/
	    
	    //includes folder files
	    /**start**/
	    
	    if (!file_exists($root_path.'/'.$projectTitle.'/includes')) {
	        mkdir($root_path.'/'.$projectTitle.'/includes', 0777, true);
	    }
	    
	    $st = $basicFilesObj->createInitFile($projectTitle);
	    $file = fopen($root_path.'/'.$projectTitle.'/includes/init.php', "w");
	    fwrite($file, $st);
	    fclose($file);
	    
	    /**end**/
	}
	
	public function copyFilesFromRootProject($root_path,$projectTitle){
	    
	    /** Library files(bootstrap and jquery) **/
	    if (!file_exists($root_path.'/'.$projectTitle.'/public')) {
	        mkdir($root_path.'/'.$projectTitle.'/public', 0777, true);
	    }
	    
	    if (!file_exists($root_path.'/'.$projectTitle.'/public/js')) {
	        mkdir($root_path.'/'.$projectTitle.'/public/js', 0777, true);
	        copy($this->root_dir.'/public/js/bootstrap.min.js',$root_path.'/'.$projectTitle.'/public/js/bootstrap.min.js');
	        copy($this->root_dir.'/public/js/jquery-3.3.1.js',$root_path.'/'.$projectTitle.'/public/js/jquery-3.3.1.js');
	    }
	    
	    if (!file_exists($root_path.'/'.$projectTitle.'/public/css')) {
	        mkdir($root_path.'/'.$projectTitle.'/public/css', 0777, true);
	        copy($this->root_dir.'/public/css/bootstrap.min.css',$root_path.'/'.$projectTitle.'/public/css/bootstrap.min.css');
	    }
	    
	    if (!file_exists($root_path.'/'.$projectTitle.'/public/images')) {
	        mkdir($root_path.'/'.$projectTitle.'/public/images', 0777, true);
	        copy($this->root_dir.'/public/images/db.png',$root_path.'/'.$projectTitle.'/public/images/db.png');
	        copy($this->root_dir.'/public/images/users.jpg',$root_path.'/'.$projectTitle.'/public/images/users.jpg');
	        copy($this->root_dir.'/public/images/edit.png',$root_path.'/'.$projectTitle.'/public/images/edit.png');
	        copy($this->root_dir.'/public/images/delete.png',$root_path.'/'.$projectTitle.'/public/images/delete.png');
	        copy($this->root_dir.'/public/images/insert.png',$root_path.'/'.$projectTitle.'/public/images/insert.png');
	        copy($this->root_dir.'/public/images/home.png',$root_path.'/'.$projectTitle.'/public/images/home.png');
	        copy($this->root_dir.'/public/images/goBack.png',$root_path.'/'.$projectTitle.'/public/images/goBack.png');
	        copy($this->root_dir.'/public/images/logout.png',$root_path.'/'.$projectTitle.'/public/images/logout.png');
	        copy($this->root_dir.'/public/images/addUser.png',$root_path.'/'.$projectTitle.'/public/images/addUser.png');
	        copy($this->root_dir.'/public/images/changePass.png',$root_path.'/'.$projectTitle.'/public/images/changePass.png');
	        copy($this->root_dir.'/public/images/user.jpg',$root_path.'/'.$projectTitle.'/public/images/user.jpg');
	    }
	    
	}
	
	
	public function createFiles($rdbms,$host,$username,$password,$db,$tables,$projectTitle){
	    
	    $root_path = $_SERVER['DOCUMENT_ROOT'];//new project location.
	    
	    //whether user needs login functionality or not
	    if(!empty($_REQUEST['loginRequirment']))
	       $loginRequired = $_REQUEST['loginRequirment'];
	    else
	        $loginRequired = NULL;
	    
	    //for session checking in login based project
	   if($loginRequired != NULL){
	        $varSessionCheck = 'if(!isset($_SESSION["id"])){	
	header("Location:".$root_dir."/'.$projectTitle.'");
    exit();
}';
	    }else{
	        $varSessionCheck = '';
	    }    
	    
	    
	    $basicFilesObj = new basicFiles();//object of basic files class
	    $adminModelObj = new admin_Model($this->registry->db);//object of admin model class
	   
	    //declaration of neccessary file paths
	    $controllerFile = $root_path.'/'.$projectTitle.'/controller/adminController.php';
	    $modelFile = $root_path.'/'.$projectTitle.'/model/admin_Model.class.php';
	    $homeViewFile = $root_path.'/'.$projectTitle.'/views/home.php';
	    $queryDataFile = $root_path.'/'.$projectTitle.'/model/queryData.class.php';
	    
	    
	      
	   //project file creation starts here.
	    if (!file_exists($root_path.'/'.$projectTitle)) {
	        mkdir($root_path.'/'.$projectTitle, 0777, true);
	        $this->copyFilesFromRootProject($root_path, $projectTitle);
	        $this->createLibraryFiles($basicFilesObj, $root_path, $projectTitle,$loginRequired);
	    }else{
	        $this->projectExists($projectTitle);
	    }
	    
	    
	    
	    //upload folder
	    if (!file_exists($root_path.'/'.$projectTitle.'/public/uploads')) {
	        mkdir($root_path.'/'.$projectTitle.'/public/uploads', 0777, true);
	    }
	    
	    
	    //model folder files
	    /**start**/
	    if (!file_exists($root_path.'/'.$projectTitle.'/model')) {
	        mkdir($root_path.'/'.$projectTitle.'/model', 0777, true);
	    }
	    $st = $basicFilesObj->createElementClassFile();
	    $file = fopen($root_path.'/'.$projectTitle.'/model/element.class.php', "w");
	    fwrite($file, $st);
	    fclose($file);
	    
	    $st = $basicFilesObj->createIndexModel($db);
	    $file = fopen($root_path.'/'.$projectTitle.'/model/index_Model.class.php', "w");
	    fwrite($file, $st);
	    fclose($file);
	    /**end**/
	    
	    //views folder files
	    /**start**/
	    if (!file_exists($root_path.'/'.$projectTitle.'/views')) {
	        mkdir($root_path.'/'.$projectTitle.'/views', 0777, true);
	    }
	    
	    $st = $basicFilesObj->createError404View();
	    $file = fopen($root_path.'/'.$projectTitle.'/views/error404.php', "w");
	    fwrite($file, $st);
	    fclose($file);
	    
	    $st = $basicFilesObj->createHeader($projectTitle);
	    $file = fopen($root_path.'/'.$projectTitle.'/views/header.php', "w");
	    fwrite($file, $st);
	    fclose($file);
	    
	    $st = $basicFilesObj->createFooter();
	    $file = fopen($root_path.'/'.$projectTitle.'/views/footer.php', "w");
	    fwrite($file, $st);
	    fclose($file);
	    
	    
	    $st = $basicFilesObj->createMenuView($loginRequired);
	    $file = fopen($root_path.'/'.$projectTitle.'/views/menu.php', "w");
	    fwrite($file, $st);
	    fclose($file);
	    /**end**/
	    
	    
	    //root files
	    /**start**/
	    //Root index file
	    $st = $basicFilesObj->createRootIndex();
	    $file = fopen($root_path.'/'.$projectTitle.'/index.php', "w");
	    fwrite($file, $st);
	    fclose($file);
	    
	    //connection file
	    $st = $basicFilesObj->createConfigurationFile($rdbms,$host,$db,$username,$password);
	    $file = fopen($root_path.'/'.$projectTitle.'/db_config.php', "w");
	    fwrite($file, $st);
	    fclose($file);
	    
	    //htaccess
	    $file = fopen($root_path."/".$projectTitle."/.htaccess","w");
	    if($file == false){
	        echo "error";die;
	    }
	    fwrite($file,"RewriteEngine on\n\nRewriteCond %{REQUEST_FILENAME} !-f\nRewriteCond %{REQUEST_FILENAME} !-d\n\nRewriteRule ^(.*)$ index.php?rt=$1 [L,QSA]");
	    fclose($file);
	    /**end**/
	    
	  
	    for($i=0;$i<count($tables);$i++){ 
	        $col = array();
	        $idArr = array();
	        $id = '';
	        $fieldType = array();
	        if(isset($_REQUEST['fieldType'])){
	            $fieldType = $_REQUEST['fieldType'];
	        }else{
	            $fieldType = array();
	        }
	        if(isset($_REQUEST[$tables[$i]])){
	            $col = $_REQUEST[$tables[$i]];
	            $idArr = $_REQUEST['id'];
	            $id = $idArr[$i];
	            
	        }else{
	            $columns = $adminModelObj->list_columns($tables[$i], $db);
	               
	           for($j=1,$k=0;$j<count($columns);$j++,$k++){
	               $col[$k] = $columns[$j]['Field'].'-'.$columns[$j]['Type'].'-'.$columns[$j]['Null'];
	            }
	           $id = $columns[0]['Field'];
	       }
	       
	        //main controller class file
	       $st = $basicFilesObj->createController($projectTitle,$tables[$i], $id);
	      
	        if(!file_exists($controllerFile)){
	            $file = fopen($controllerFile, "a+");
	            fwrite($file, $basicFilesObj->controllerStart($projectTitle, $varSessionCheck));
	            fwrite($file, $st);
	            fclose($file);
	        }else if(file_exists($controllerFile)){
	            $file = fopen($controllerFile, "a+");
	            fwrite($file, $st);
	            fclose($file);
	        }
	        
	        //main model class file
	        $st = $basicFilesObj->createModel($tables[$i],$col,$id);
	        if(!file_exists($modelFile)){
	            $file = fopen($modelFile, "a+");
	            fwrite($file,'<?php
	error_reporting(0);
	class admin_Model{
		protected $pdo = null;
		private $table;
		public function __construct(PDO $pdo,$table=null) {
			$this->pdo = $pdo;
			$this->table = $table;
		}');
	            fwrite($file, $st);
	            
	            
	            fclose($file);
	        }else if(file_exists($modelFile)){
	            $file = fopen($modelFile, "a+");
	            
	            fwrite($file, $st);
	            fclose($file);
	        }
	        
	        $st = $basicFilesObj->createQueryDataFile($tables[$i], $col,$fieldType,$id);
	        if(!file_exists($queryDataFile)){
	           $file = fopen($queryDataFile, "a+");
	           fwrite($file, '<?php
	Class queryData{
            
		function __construct(){}
         ');
	           fwrite($file, $st);
	           fclose($file);
	        }else if(file_exists($queryDataFile)){
	            $file = fopen($queryDataFile, "a+");
	            fwrite($file, $st);
	            fclose($file);
	        }
	        
	        //view home file
	        $st = $basicFilesObj->createHomeView($tables[$i]);
	        if(!file_exists($homeViewFile)){
	            $file = fopen($homeViewFile, "a+");
	            fwrite($file,'
<table class="table table-stripped">');
	            fwrite($file, $st);
	            fclose($file);
	        }else{
	            $file = fopen($homeViewFile, "a+");
	            fwrite($file, $st);
	           fclose($file);
	        }
	       
	        
	        //form view file
	      
	        $st = $basicFilesObj->createFormView($projectTitle,$tables[$i],$col,$fieldType,$id);
	        $file = fopen($root_path.'/'.$projectTitle.'/views/'.$tables[$i].'Form.php', "a+");
	        fwrite($file, $st);
	        fclose($file);
	        
	        //table view file
	        $st = $basicFilesObj->createTableView($tables[$i],$col,$id,$fieldType);
	        $file = fopen($root_path.'/'.$projectTitle.'/views/'.$tables[$i].'ViewForm.php', "a+");
	        fwrite($file, $st);
	        fclose($file);
	        
	       
	    }
	    
	    if(!empty($_REQUEST['loginRequirment'])){
	        
	        $res = $adminModelObj->createUserTable($rdbms, $host, $username, $password, $db);
	        if(!$res){
	            echo "user tables not created";
	        }
	        
	        $st = $basicFilesObj->createPasswordResetView();
	        $file = fopen($root_path.'/'.$projectTitle.'/views/changePass.php', "a+");
	        fwrite($file, $st);
	        fclose($file);
			
			$st = $basicFilesObj->createUserView();
	        $file = fopen($root_path.'/'.$projectTitle.'/views/list_users.php', "a+");
	        fwrite($file, $st);
	        fclose($file); 
	        
	        $st = $basicFilesObj->createIndexView();
	        $file = fopen($root_path.'/'.$projectTitle.'/views/index.php', "a+");
	        fwrite($file, $st);
	        fclose($file);
	        
	        $st = $basicFilesObj->createAddUserView();
	        $file = fopen($root_path.'/'.$projectTitle.'/views/userForm.php', "a+");
	        fwrite($file, $st);
	        fclose($file);
	       
	        
	        $file = fopen($controllerFile, "a+");
	        fwrite($file,$basicFilesObj->addLoginToController());
	        fclose($file);
	        

	        $file = fopen($modelFile, "a+");
	        fwrite($file,$basicFilesObj->addLoginToModel($db));
	        fclose($file);
	    }
	    else{
	        
	        $file = fopen($controllerFile, "a+");
	        fwrite($file,'}
?>
');
	        fclose($file);
	        
	        $file = fopen($modelFile, "a+");
	        $file = fopen($modelFile, "a+");
	        fwrite($file,'}
?>
');
	        fclose($file);
	        
	    }

	    $file = fopen($queryDataFile, "a+");
	    fwrite($file,'}
?>
');
	    fclose($file);
	    
	    $file = fopen($homeViewFile, "a+");
	        fwrite($file, '</table>');
	        fclose($file);
	        
	    $this->creationSuccess($projectTitle);
	}
    
	public function creationSuccess($projectTitle){
	    $this->registry->template->show('header');
	    echo '<br/><br/>
       <table class="table table-striped">
		<tr>
			<td align="left"><a href="/database2form">
				<img src="'.$this->root_dir1.'/public/images/home.png" height="31" width="31">
			</a></td>
			<td align="right" colspan="2"><a href="javascript:history.back()">
				<img src="'.$this->root_dir1.'public/images/goBack.png" height="31" width="101">
			</a></td>
		</tr>
		<tr>
			<td align="center" width="100%"><h3>Form Successfully created</h3></td>
		    <td></td>
        </tr>
		<tr>
			<td align="center" width="100%"><a href="/'.$projectTitle.'" target="_blank" class="btn btn-primary">Go to Created Form</a></td>
		    <td></td>
        </tr>
	</table>';
	    $this->registry->template->show('footer');
	}
	
	public function projectExists($projectTitle){
	    
	    $this->registry->template->show('header');
	    echo '<br/><br/>
       <table class="table table-striped">
		<tr>
			<td align="left"><a href="/database2form">
				<img src="'.$this->root_dir1.'/public/images/home.png" height="31" width="31">
			</a></td>
			<td align="right" colspan="2"><a href="javascript:history.back()">
				<img src="'.$this->root_dir1.'public/images/goBack.png" height="31" width="101">
			</a></td>
		</tr>
		<tr>
			<td align="center" width="100%"><h3>Form already exists</h3></td>
		    <td></td>
        </tr>
		<tr>
			<td align="center" width="100%"><a href="/'.$projectTitle.'" target="_blank" class="btn btn-primary">Go to Created Form</a></td>
		    <td></td>
        </tr>
	</table>';
	    $this->registry->template->show('footer');
	    exit;
	}
	
}
?>