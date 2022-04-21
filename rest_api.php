<?php
require __DIR__ . "/inc/bootstrap.php";
 
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = explode( '/', $uri );
 
if ((!isset($uri[2])) || !isset($uri[3])) {
  header("HTTP/1.1 404 Not Found");
  exit();
}

if ($uri[2] == 'product') {
  require PROJECT_ROOT_PATH . "/controller/api/productController.php";
 
  $objFeedController = new ProductController();
  $strMethodName = $uri[3] . 'Action';
  $objFeedController->{$strMethodName}();
} else if($uri[2] == 'user') {
  require PROJECT_ROOT_PATH . "/controller/api/userController.php";
 
  $objFeedController = new UserController();
  $strMethodName = $uri[3] . 'Action';
  $objFeedController->{$strMethodName}();
} else if($uri[2] == 'sale') {
  require PROJECT_ROOT_PATH . "/controller/api/saleController.php";
 
  $objFeedController = new SaleController();
  $strMethodName = $uri[3] . 'Action';
  $objFeedController->{$strMethodName}();
} else if($uri[2] == 'notification') {
  require PROJECT_ROOT_PATH . "/controller/api/notificationController.php";
 
  $objFeedController = new NotificationController();
  $strMethodName = $uri[3] . 'Action';
  $objFeedController->{$strMethodName}();
} else {
  header("HTTP/1.1 404 Not Found");
  exit();
}




?>