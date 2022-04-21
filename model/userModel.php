<?php
require_once PROJECT_ROOT_PATH . "/Model/Database.php";
 
class UserModel extends Database {
    
  public function checkCredentials($username, $password) {
    return $this->select(
      "SELECT 1
      FROM user
      WHERE username = ? AND password = ?;", ["ss", [$username, $password]]
    );
  }

  public function getUser($username) {
    return $this->select(
      "SELECT password, email, credit_card_number, image
      FROM user
      WHERE username = ?;", ["s", [$username]]
    );
  }

  public function editUser($username, $password, $email, $creditCardNumber, $image) {
    return $this->action(
      "UPDATE user
      SET password = ?, email = ?, credit_card_number = ?, image = ?
      WHERE username = ?;", ["sssss", [$password, $email, $creditCardNumber, $image, $username]]
    );
  }

  public function addUser($username, $password, $email, $creditCardNumber, $image) {
    return $this->action(
      "INSERT INTO user
      (username, password, email, credit_card_number, image)
      VALUES (?, ?, ?, ?, ?);", ["sssss", [$username, $password, $email, $creditCardNumber, $image]]
    );
  }

  public function checkExist($username) {
    return $this->select(
      "SELECT 1
      FROM user
      WHERE username = ?", ["s", [$username]]
    );
  }

}

?>