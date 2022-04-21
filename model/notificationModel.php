<?php
require_once PROJECT_ROOT_PATH . "/Model/Database.php";
 
class NotificationModel extends Database {
    
  public function getNotifications($username, $limit, $offset) {
    return $this->select(
      "SELECT description, date, time, checked
      FROM notification 
      WHERE recipient = ?
      ORDER BY checked, id DESC
      LIMIT ?, ?;", ["sii", [$username, $offset, $limit]]
    );
  }

  public function getNewNotificationsNumber($username) {
    return $this->select(
      "SELECT COUNT(id) as number
      FROM notification
      WHERE recipient = ? AND checked = false", ["s", [$username]]
    );
  }

  public function clearUnread($username) {
    return $this->action(
      "UPDATE notification
      SET checked = true
      WHERE recipient = ?", ["s", [$username]]
    );
  }

}

?>