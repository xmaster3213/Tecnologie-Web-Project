<?php
class ProductController extends BaseController {
  
  /**
   * "/product/list" Endpoint - Get list of products
   */
  public function listAction() {
    $strErrorDesc = '';
    $requestMethod = $_SERVER["REQUEST_METHOD"];
    $arrQueryStringParams = $this->getQueryStringParams();

    if (strtoupper($requestMethod) == 'GET') {
      try {
        $productModel = new ProductModel();

        $intLimit = 50;
        if (isset($arrQueryStringParams['limit']) && $arrQueryStringParams['limit']) {
          $intLimit = $arrQueryStringParams['limit'];
        }

        $offset = 0;
        if (isset($arrQueryStringParams['offset']) && $arrQueryStringParams['offset']) {
          $offset = $arrQueryStringParams['offset'];
        }

        $arrProducts = $productModel->getProducts($intLimit, $offset);
        $responseData = json_encode($arrProducts);
      } catch (Error $e) {
        $strErrorDesc = $e->getMessage().'Something went wrong! Please contact support.';
        $strErrorHeader = 'HTTP/1.1 500 Internal Server Error';
      }
    } else {
      $strErrorDesc = 'Method not supported';
      $strErrorHeader = 'HTTP/1.1 422 Unprocessable Entity';
    }

    // send output
    if (!$strErrorDesc) {
      $this->sendOutput(
        $responseData,
        array('Content-Type: application/json', 'HTTP/1.1 200 OK')
      );
    } else {
      $this->sendOutput(json_encode(array('error' => $strErrorDesc)), 
        array('Content-Type: application/json', $strErrorHeader)
      );
    }
  }

  public function listGivenKeywordsAction() {
    $strErrorDesc = '';
    $requestMethod = $_SERVER["REQUEST_METHOD"];
    $arrQueryStringParams = $this->getQueryStringParams();

    if (strtoupper($requestMethod) == 'GET') {
      try {
        $productModel = new ProductModel();

        $intLimit = 50;
        if (isset($arrQueryStringParams['limit']) && $arrQueryStringParams['limit']) {
          $intLimit = $arrQueryStringParams['limit'];
        }

        $offset = 0;
        if (isset($arrQueryStringParams['offset']) && $arrQueryStringParams['offset']) {
          $offset = $arrQueryStringParams['offset'];
        }

        $keywords = "";
        if (isset($arrQueryStringParams['keywords'])) {
          $keywords = $arrQueryStringParams['keywords'];
        }

        $arrProducts = $productModel->getProductsWithKeywords($intLimit, $offset, $keywords);
        $responseData = json_encode($arrProducts);
      } catch (Error $e) {
        $strErrorDesc = $e->getMessage().'Something went wrong! Please contact support.';
        $strErrorHeader = 'HTTP/1.1 500 Internal Server Error';
      }
    } else {
      $strErrorDesc = 'Method not supported';
      $strErrorHeader = 'HTTP/1.1 422 Unprocessable Entity';
    }

    // send output
    if (!$strErrorDesc) {
      $this->sendOutput(
        $responseData,
        array('Content-Type: application/json', 'HTTP/1.1 200 OK')
      );
    } else {
      $this->sendOutput(json_encode(array('error' => $strErrorDesc)), 
        array('Content-Type: application/json', $strErrorHeader)
      );
    }
  }

  /**
   * "/product/info" Endpoint - Get specific product
   */
  public function infoAction() {
    $strErrorDesc = '';
    $requestMethod = $_SERVER["REQUEST_METHOD"];
    $arrQueryStringParams = $this->getQueryStringParams();

    if (strtoupper($requestMethod) == 'GET') {
      try {
        $productModel = new ProductModel();

        $intId = NULL;
        if (isset($arrQueryStringParams['id'])) {
          $intId = $arrQueryStringParams['id'];
        }

        if ($intId == NULL) {
          throw new ValueError;
        }
        
        $product = $productModel->getProduct($intId);
        $responseData = json_encode($product);
      } catch (Error $e) {
        $strErrorDesc = $e->getMessage().'Something went wrong! Please contact support.';
        $strErrorHeader = 'HTTP/1.1 500 Internal Server Error';
      }
    } else {
      $strErrorDesc = 'Method not supported';
      $strErrorHeader = 'HTTP/1.1 422 Unprocessable Entity';
    }

    // send output
    if (!$strErrorDesc) {
      $this->sendOutput(
        $responseData,
        array('Content-Type: application/json', 'HTTP/1.1 200 OK')
      );
    } else {
      $this->sendOutput(json_encode(array('error' => $strErrorDesc)), 
        array('Content-Type: application/json', $strErrorHeader)
      );
    }
  }

  /**
   * "/product/bySellerList" Endpoint - Get list of products made by a specific user
   */
  public function bySellerListAction() {
    $strErrorDesc = '';
    $requestMethod = $_SERVER["REQUEST_METHOD"];
    $arrQueryStringParams = $this->getQueryStringParams();

    if (strtoupper($requestMethod) == 'GET') {
      try {
        $productModel = new ProductModel();

        $username = NULL;
        if (isset($arrQueryStringParams['username'])) {
          $username = $arrQueryStringParams['username'];
        }

        $limit = 50;
        if (isset($arrQueryStringParams['limit']) && $arrQueryStringParams['limit']) {
          $limit = $arrQueryStringParams['limit'];
        }

        $offset = 0;
        if (isset($arrQueryStringParams['offset']) && $arrQueryStringParams['offset']) {
          $offset = $arrQueryStringParams['offset'];
        }

        if ($username == NULL) {
          throw new ValueError;
        }
        
        $products = $productModel->getProductsMadeBy($username, $limit, $offset);
        $responseData = json_encode($products);
      } catch (Error $e) {
        $strErrorDesc = $e->getMessage().'Something went wrong! Please contact support.';
        $strErrorHeader = 'HTTP/1.1 500 Internal Server Error';
      }
    } else {
      $strErrorDesc = 'Method not supported';
      $strErrorHeader = 'HTTP/1.1 422 Unprocessable Entity';
    }

    // send output
    if (!$strErrorDesc) {
      $this->sendOutput(
        $responseData,
        array('Content-Type: application/json', 'HTTP/1.1 200 OK')
      );
    } else {
      $this->sendOutput(json_encode(array('error' => $strErrorDesc)), 
        array('Content-Type: application/json', $strErrorHeader)
      );
    }
  }

  /**
   * "/product/edit Endpoint - Edit a product
   */
  public function editAction() {
    $strErrorDesc = '';
    $requestMethod = $_SERVER["REQUEST_METHOD"];

    if (strtoupper($requestMethod) == 'POST') {
      try {
        $productModel = new ProductModel();

        $id = NULL;
        if (isset($_POST['id'])) {
          $id = $_POST['id'];
        }

        $name = NULL;
        if (isset($_POST['name'])) {
          $name = $_POST['name'];
        }

        $description = NULL;
        if (isset($_POST['description'])) {
          $description = $_POST['description'];
        }

        $price = NULL;
        if (isset($_POST['price'])) {
          $price = $_POST['price'];
        }

        $image = NULL;
        if (isset($_POST['image'])) {
          $image = $_POST['image'];
        }

        $quantity = NULL;
        if (isset($_POST['quantity'])) {
          $quantity = $_POST['quantity'];
        }

        if ($id == NULL || $name == NULL || $description == NULL || $price == NULL || $image == NULL || $quantity == NULL) {
          throw new ValueError;
        }

        $result = $productModel->editProduct($id, $name, $description, $price, $image, $quantity);        
        $responseData = json_encode($result);
      } catch (Error $e) {
        $strErrorDesc = $e->getMessage().'Something went wrong! Please contact support.';
        $strErrorHeader = 'HTTP/1.1 500 Internal Server Error';
      }
    } else {
      $strErrorDesc = 'Method not supported';
      $strErrorHeader = 'HTTP/1.1 422 Unprocessable Entity';
    }

    // send output
    if (!$strErrorDesc) {
      $this->sendOutput(
        $responseData,
        array('Content-Type: application/json', 'HTTP/1.1 200 OK')
      );
    } else {
      $this->sendOutput(json_encode(array('error' => $strErrorDesc)), 
        array('Content-Type: application/json', $strErrorHeader)
      );
    }
  }

  /**
   * "/product/edit Endpoint - Add a product
   */
  public function addAction() {
    $strErrorDesc = '';
    $requestMethod = $_SERVER["REQUEST_METHOD"];

    if (strtoupper($requestMethod) == 'POST') {
      try {
        $productModel = new ProductModel();

        $seller = NULL;
        if (isset($_POST['seller'])) {
          $seller = $_POST['seller'];
        }

        $name = NULL;
        if (isset($_POST['name'])) {
          $name = $_POST['name'];
        }

        $description = NULL;
        if (isset($_POST['description'])) {
          $description = $_POST['description'];
        }

        $price = NULL;
        if (isset($_POST['price'])) {
          $price = $_POST['price'];
        }

        $image = NULL;
        if (isset($_POST['image'])) {
          $image = $_POST['image'];
        }

        $quantity = NULL;
        if (isset($_POST['quantity'])) {
          $quantity = $_POST['quantity'];
        }

        if ($seller == NULL || $name == NULL || $description == NULL || $price == NULL || $image == NULL || $quantity == NULL) {
          throw new ValueError;
        }

        $result = $productModel->addProduct($seller, $name, $description, $price, $image, $quantity);        
        $responseData = json_encode($result);
      } catch (Error $e) {
        $strErrorDesc = $e->getMessage().'Something went wrong! Please contact support.';
        $strErrorHeader = 'HTTP/1.1 500 Internal Server Error';
      }
    } else {
      $strErrorDesc = 'Method not supported';
      $strErrorHeader = 'HTTP/1.1 422 Unprocessable Entity';
    }

    // send output
    if (!$strErrorDesc) {
      $this->sendOutput(
        $responseData,
        array('Content-Type: application/json', 'HTTP/1.1 200 OK')
      );
    } else {
      $this->sendOutput(json_encode(array('error' => $strErrorDesc)), 
        array('Content-Type: application/json', $strErrorHeader)
      );
    }
  }

}

?>