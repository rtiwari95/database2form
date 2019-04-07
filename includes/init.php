<?php
 /*** include the controller class ***/
 include __SITE_PATH . '/application/' . 'controller_base.class.php';

 /*** include the registry class ***/
 include __SITE_PATH . '/application/' . 'registry.class.php';

 /*** include the router class ***/
 include __SITE_PATH . '/application/' . 'router.class.php';

 /*** include the template class ***/
 include __SITE_PATH . '/application/' . 'template.class.php';
 
 /*** include the validations class, if not utilised will be removed later ***/
 include __SITE_PATH . '/application/' . 'validations.class.php';
 
 include __SITE_PATH . '/controller/' . 'basicFiles.php';
 
 /*** a new registry object ***/
 $registry = new registry;

 include($_SERVER['DOCUMENT_ROOT']."/database2form/db_config.php");
  
 /*** create the database registry object ***/
  $registry->db = DBConnect::getInstance()->getConnection();
  $pdo  =   $registry->db;//Used in core/design
  
 /*** auto load model classes ***/
    function my_autoloader($class_name) {
		$filename = strtolower($class_name) . '.class.php';
		$file = __SITE_PATH . '/model/' . $filename;

		if (file_exists($file) == false){
			return false;
		}
  include ($file);
}
spl_autoload_register('my_autoloader');

?>
