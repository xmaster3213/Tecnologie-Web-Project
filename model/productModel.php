<?php
require_once PROJECT_ROOT_PATH . "/Model/Database.php";
 
class ProductModel extends Database {
    
  public function getProducts($limit, $offset) {
    return $this->select(
      "SELECT product.id, product.name, product.description, product.price, product.image as product_image, product.quantity, user.username as seller, user.image as seller_image
      FROM product 
      JOIN user ON product.seller = user.username
      ORDER BY product.id DESC 
      LIMIT ?, ?;", ["ii", [$offset, $limit]]
    );
  }

  public function getProductsWithKeywords($limit, $offset, $words) {
    return $this->select(
      "SELECT product.id, product.name, product.description, product.price, product.image as product_image, product.quantity, user.username as seller, user.image as seller_image
      FROM product 
      JOIN user ON product.seller = user.username
      WHERE 
        CONCAT(',', REPLACE(product.name, ' ', ','), ',')
        REGEXP CONCAT(',', REPLACE(?, ' ', 's?,|,'), 's?,')   
      ORDER BY product.id DESC 
      LIMIT ?, ?;", ["sii", [$words, $offset, $limit]]
    );
  }

  public function getProduct($id) {
    return $this->select(
      "SELECT product.id, product.name, product.description, product.price, product.image as product_image, product.quantity, user.username as seller, user.image as seller_image
      FROM product 
      JOIN user ON product.seller = user.username
      WHERE product.id = ?;", ["i", [$id]]
    );
  }

  public function getProductsMadeBy($username, $limit = 50, $offset = 0) {
    return $this->select(
      "SELECT product.id, product.name, product.description, product.price, product.image as product_image, product.quantity, user.username as seller, user.image as seller_image
      FROM product 
      JOIN user ON product.seller = user.username
      WHERE user.username = ?
      ORDER BY product.id DESC 
      LIMIT ?, ?;", ["sii", [$username, $offset, $limit]]
    );
  }

  public function editProduct($id, $name, $description, $price, $image, $quantity) {
    return $this->action(
      "UPDATE product
      SET name = ?, description = ?, price = ?, image = ?, quantity = ?
      WHERE id = ?;", ["ssdsii", [$name, $description, $price, $image, $quantity, $id]]
    );
  }

  public function addProduct($seller, $name, $description, $price, $image, $quantity) {
    return $this->action(
      "INSERT INTO product (seller, name, description, price, image, quantity)
      VALUES (?, ?, ?, ?, ?, ?);", ["sssdsi", [$seller, $name, $description, $price, $image, $quantity]]
    );
  }

}

?>