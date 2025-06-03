-- create database
CREATE DATABASE lwstore;

USE lwstore;

-- create table

CREATE TABLE users (
    id_user INT PRIMARY KEY AUTO_INCREMENT,
    login VARCHAR(50) NOT NULL UNIQUE,
    name VARCHAR(100) NOT NULL,
    password VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    cpf VARCHAR(11) NOT NULL,
    telephone VARCHAR(15) NOT NULL,
    address VARCHAR(255) NOT NULL,
    gender CHAR(1) NOT NULL,
    birthdate DATE NOT NULL,
    type_user VARCHAR(20) NOT NULL,
    photo VARCHAR(255)
);

CREATE TABLE sellers (
    id_seller INT PRIMARY KEY AUTO_INCREMENT,
    fk_id_user INT,

    CONSTRAINT fk_user FOREIGN KEY (fk_id_user) REFERENCES users(id_user)
);

CREATE TABLE customers (
    id_customer INT PRIMARY KEY AUTO_INCREMENT,
    fk_id_user INT,

    CONSTRAINT fk_user FOREIGN KEY (fk_id_user) REFERENCES users(id_user)
);

CREATE TABLE cart (
    id_cart INT PRIMARY KEY AUTO_INCREMENT,
    fk_id_user INT,
    status VARCHAR(100) NOT NULL
);

CREATE TABLE itens_cart (
    id_itens_cart INT PRIMARY KEY AUTO_INCREMENT,
    fk_id_cart INT,
    fk_id_product INT,
    amount INT NOT NULL,
    unit_value DECIMAL(10, 2) NOT NULL
);

CREATE TABLE product (
    product_code VARCHAR(20) PRIMARY KEY,
    name VARCHAR(100) NOT NULL, 
    price DECIMAL(10, 2) NOT NULL,
    amount INT NOT NULL,
    type_packaging VARCHAR(100) NOT NULL,
    description VARCHAR(255) NOT NULL,
    photo VARCHAR(255)
);

CREATE TABLE sales(
    id_sale INT PRIMARY KEY AUTO_INCREMENT,
    fk_id_user INT,
    fk_id_product INT,
    sale_date DATE NOT NULL,
    total_price DECIMAL(10, 2) NOT NULL
)