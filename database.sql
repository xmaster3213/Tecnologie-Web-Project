CREATE DATABASE shop;

USE shop;

CREATE TABLE user(
  username varchar(20) PRIMARY KEY,
  password varchar(16) NOT NULL,
  email varchar(40) NOT NULL,
  credit_card_number varchar(16) NOT NULL,
  image varchar(500) NOT NULL
);

CREATE TABLE product(
  id int unsigned PRIMARY KEY AUTO_INCREMENT,
  seller varchar(20) NOT NULL,
  name varchar(30) NOT NULL,
  description varchar(2000) NOT NULL,
  price float NOT NULL,
  image varchar(500) NOT NULL,
  quantity int unsigned NOT NULL,

  CONSTRAINT fk_product_user
    FOREIGN KEY (seller) REFERENCES user(username)
      ON DELETE NO ACTION
      ON UPDATE NO ACTION
);

CREATE TABLE sale(
  id int unsigned PRIMARY KEY AUTO_INCREMENT,
  product int unsigned NOT NULL,
  buyer varchar(20) NOT NULL,
  date date NOT NULL,
  time time NOT NULL,

  CONSTRAINT fk_sale_product
    FOREIGN KEY (product) REFERENCES product(id)
      ON DELETE NO ACTION
      ON UPDATE NO ACTION,

  CONSTRAINT fk_sale_user
    FOREIGN KEY (buyer) REFERENCES user(username)
      ON DELETE NO ACTION
      ON UPDATE NO ACTION
);

CREATE TABLE notification(
  id int unsigned PRIMARY KEY AUTO_INCREMENT,
  recipient varchar(20) NOT NULL,
  description varchar(2000) NOT NULL,
  date date NOT NULL,
  time time NOT NULL,
  checked boolean NOT NULL DEFAULT false,

  CONSTRAINT fk_notification_user
    FOREIGN KEY (recipient) REFERENCES user(username)
      ON DELETE NO ACTION
      ON UPDATE NO ACTION
);