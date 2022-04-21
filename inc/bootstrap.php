<?php
define("PROJECT_ROOT_PATH", __DIR__ . "/../");
 
// include main configuration file
require_once PROJECT_ROOT_PATH . "/inc/config.php";
 
// include the base controller file
require_once PROJECT_ROOT_PATH . "/controller/api/baseController.php";
 
// include the use model file
require_once PROJECT_ROOT_PATH . "/model/productModel.php";
require_once PROJECT_ROOT_PATH . "/model/userModel.php";
require_once PROJECT_ROOT_PATH . "/model/saleModel.php";
require_once PROJECT_ROOT_PATH . "/model/notificationModel.php";

?>