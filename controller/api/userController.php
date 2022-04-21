<?php
class UserController extends BaseController {
  
  /**
   * "/user/checkCredential" Endpoint - Check if the user credential are in the database
   */
  public function checkCredentialsAction() {
    $strErrorDesc = '';
    $requestMethod = $_SERVER["REQUEST_METHOD"];

    if (strtoupper($requestMethod) == 'POST') {
      try {
        $userModel = new UserModel();

        $username = NULL;
        if (isset($_POST['username'])) {
          $username = $_POST['username'];
        }

        $password = NULL;
        if (isset($_POST['password'])) {
          $password = $_POST['password'];
        }

        if ($username == NULL || $password == NULL) {
          throw new ValueError;
        }

        $result = $userModel->checkCredentials($username, $password);        
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

  public function getAction() {
    $strErrorDesc = '';
    $requestMethod = $_SERVER["REQUEST_METHOD"];
    $arrQueryStringParams = $this->getQueryStringParams();

    if (strtoupper($requestMethod) == 'GET') {
      try {
        $userModel = new UserModel();

        $username = NULL;
        if (isset($arrQueryStringParams['username'])) {
          $username = $arrQueryStringParams['username'];
        }

        if ($username == NULL) {
          throw new ValueError;
        }

        $result = $userModel->getUser($username);        
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

  public function editAction() {
    $strErrorDesc = '';
    $requestMethod = $_SERVER["REQUEST_METHOD"];

    if (strtoupper($requestMethod) == 'POST') {
      try {
        $userModel = new UserModel();

        $username = NULL;
        if (isset($_POST['username'])) {
          $username = $_POST['username'];
        }

        $password = NULL;
        if (isset($_POST['password'])) {
          $password = $_POST['password'];
        }

        $email = NULL;
        if (isset($_POST['email'])) {
          $email = $_POST['email'];
        }

        $creditCard = NULL;
        if (isset($_POST['creditCard'])) {
          $creditCard = $_POST['creditCard'];
        }
        
        $image = NULL;
        if (isset($_POST['image'])) {
          $image = $_POST['image'];
        }

        if ($username == NULL || $password == NULL || $email == NULL || $creditCard == NULL || $image == NULL) {
          throw new ValueError;
        }

        $result = $userModel->editUser($username, $password, $email, $creditCard, $image);        
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

  public function addAction() {
    $strErrorDesc = '';
    $requestMethod = $_SERVER["REQUEST_METHOD"];

    if (strtoupper($requestMethod) == 'POST') {
      try {
        $userModel = new UserModel();

        $username = NULL;
        if (isset($_POST['username'])) {
          $username = $_POST['username'];
        }

        $password = NULL;
        if (isset($_POST['password'])) {
          $password = $_POST['password'];
        }

        $email = NULL;
        if (isset($_POST['email'])) {
          $email = $_POST['email'];
        }

        $creditCard = NULL;
        if (isset($_POST['creditCard'])) {
          $creditCard = $_POST['creditCard'];
        }
        
        $image = NULL;
        if (isset($_POST['image'])) {
          $image = $_POST['image'];
        }

        if ($username == NULL || $password == NULL || $email == NULL || $creditCard == NULL || $image == NULL) {
          throw new ValueError;
        }

        if ($userModel->checkExist($username)) {
          throw new ValueError;
        }

        $result = $userModel->addUser($username, $password, $email, $creditCard, $image);        
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