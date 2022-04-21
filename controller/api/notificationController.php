<?php
class NotificationController extends BaseController {
  
  /**
   * "/user/checkCredential" Endpoint - Check if the user credential are in the database
   */
  public function listAction() {
    $strErrorDesc = '';
    $requestMethod = $_SERVER["REQUEST_METHOD"];
    $arrQueryStringParams = $this->getQueryStringParams();

    if (strtoupper($requestMethod) == 'GET') {
      try {
        $notificationModel = new NotificationModel();


        $username = NULL;
        if (isset($arrQueryStringParams['username'])) {
          $username = $arrQueryStringParams['username'];
        }

        $offset = 0;
        if (isset($arrQueryStringParams['offset'])) {
          $offset = $arrQueryStringParams['offset'];
        }

        $limit = 50;
        if (isset($arrQueryStringParams['limit']) && $arrQueryStringParams['limit']) {
          $limit = $arrQueryStringParams['limit'];
        }

        if ($username == NULL || $offset == NULL || $limit == NULL) {
          throw new ValueError;
        }

        $result = $notificationModel->getNotifications($username, $limit, $offset);        
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

  public function unreadNumberAction() {
    $strErrorDesc = '';
    $requestMethod = $_SERVER["REQUEST_METHOD"];
    $arrQueryStringParams = $this->getQueryStringParams();

    if (strtoupper($requestMethod) == 'GET') {
      try {
        $notificationModel = new NotificationModel();

        $username = NULL;
        if (isset($arrQueryStringParams['username'])) {
          $username = $arrQueryStringParams['username'];
        }

        if ($username == NULL) {
          throw new ValueError;
        }

        $result = $notificationModel->getNewNotificationsNumber($username);        
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

  public function clearUnreadAction() {
    $strErrorDesc = '';
    $requestMethod = $_SERVER["REQUEST_METHOD"];
    $arrQueryStringParams = $this->getQueryStringParams();

    if (strtoupper($requestMethod) == 'GET') {
      try {
        $notificationModel = new NotificationModel();

        $username = NULL;
        if (isset($arrQueryStringParams['username'])) {
          $username = $arrQueryStringParams['username'];
        }

        if ($username == NULL) {
          throw new ValueError;
        }

        $result = $notificationModel->clearUnread($username);        
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