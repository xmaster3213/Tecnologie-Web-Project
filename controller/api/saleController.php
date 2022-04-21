<?php
class SaleController extends BaseController {
  /**
   * "/sale/checkCredential" Endpoint - Get list of users
   */

  public function addAction() {
    $strErrorDesc = '';
    $requestMethod = $_SERVER["REQUEST_METHOD"];
    $arrQueryStringParams = $this->getQueryStringParams();

    if (strtoupper($requestMethod) == 'GET') {
      try {
        $saleModel = new SaleModel();

        $username = NULL;
        if (isset($arrQueryStringParams['username'])) {
          $username = $arrQueryStringParams['username'];
        }

        $product = NULL;
        if (isset($arrQueryStringParams['product']) && $arrQueryStringParams['product']) {
          $product = $arrQueryStringParams['product'];
        }

        if ($username == NULL || $product == NULL) {
          throw new ValueError;
        }
        
        $result = $saleModel->addSale($username, $product);
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

  public function listAction() {
    $strErrorDesc = '';
    $requestMethod = $_SERVER["REQUEST_METHOD"];
    $arrQueryStringParams = $this->getQueryStringParams();

    if (strtoupper($requestMethod) == 'GET') {
      try {
        $saleModel = new SaleModel();

        $intLimit = 50;
        if (isset($arrQueryStringParams['limit']) && $arrQueryStringParams['limit']) {
          $intLimit = $arrQueryStringParams['limit'];
        }

        $offset = 0;
        if (isset($arrQueryStringParams['offset']) && $arrQueryStringParams['offset']) {
          $offset = $arrQueryStringParams['offset'];
        }

        $username = NULL;
        if (isset($arrQueryStringParams['username'])) {
          $username = $arrQueryStringParams['username'];
        }

        if ($username == NULL) {
          throw new ValueError;
        }
        
        $result = $saleModel->getSalesOf($username, $intLimit, $offset);
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