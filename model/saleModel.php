<?php
require_once PROJECT_ROOT_PATH . "/Model/Database.php";
 
class SaleModel extends Database {
    
  public function addSale($username, $product) {

    $quantityGT0 = $this->select(
      "SELECT quantity, seller
      FROM product
      WHERE id = ?
      AND quantity > 0", ["i", [$product]] 
    );


    // check if the product isn't sold out
    if (count($quantityGT0)) {

      // send notification to seller that a product was sold
      $this->action(
        "INSERT INTO notification (recipient, description, date, time)
        VALUES (?, 'Someone bought one of the product you are selling!', CURRENT_DATE(), CURRENT_TIME());", ["s", [$quantityGT0[0]['seller']]]
      );

      // if there is only one left notify the seller
      if ($quantityGT0[0]['quantity'] == 1) {
        $this->action(
          "INSERT INTO notification (recipient, description, date, time)
          VALUES (?, 'A product you are selling is currently SOLD OUT!', CURRENT_DATE(), CURRENT_TIME());", ["s", [$quantityGT0[0]['seller']]]
        );
      }

      // send notification to the buyer that the item was boght succesfully
      $this->action(
        "INSERT INTO notification (recipient, description, date, time)
        VALUES (?, 'The transaction was successfull and the item is currently beeing prepared for delivery', CURRENT_DATE(), CURRENT_TIME());", ["s", [$username]]
      );

      // update the quantity
      $this->action(
        "UPDATE product
        SET quantity = quantity - 1
        WHERE id = ?;", ["i", [$product]]
      );
  
      // add the sale
      return $this->action(
        "INSERT INTO sale (product, buyer, date, time)
        VALUES (?, ?, CURRENT_DATE(), CURRENT_TIME());", ["is", [$product, $username]]
      );

    }

    return false;
    
  }

  public function getSalesOf($username, $limit, $offset) {
    return $this->select(
      "SELECT product.name, product.price, product.image as product_image, user.username as seller, sale.date, sale.time
      FROM sale
      JOIN product ON sale.product = product.id
      JOIN user ON product.seller = user.username
      WHERE (sale.buyer = ? OR product.seller = ?)
      ORDER BY product.id DESC 
      LIMIT ?, ?;", ["ssii", [$username, $username, $offset, $limit]]
    );
  }

}

?>