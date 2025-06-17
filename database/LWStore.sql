-- create database
CREATE DATABASE lwstore;

USE lwstore;

-- create table
CREATE TABLE
    users (
        id_user INT PRIMARY KEY AUTO_INCREMENT,
        login VARCHAR(50) NOT NULL UNIQUE,
        password VARCHAR(100) NOT NULL,
        type_user VARCHAR(20) NOT NULL
    );

CREATE TABLE
    sellers (
        id_seller INT PRIMARY KEY AUTO_INCREMENT,
        name VARCHAR(100) NOT NULL,
        email VARCHAR(100) NOT NULL,
        cpf VARCHAR(11) NOT NULL,
        telephone VARCHAR(15) NOT NULL,
        address VARCHAR(255) NOT NULL,
        gender CHAR(1) NOT NULL,
        birthdate DATE NOT NULL,
        photo VARCHAR(255),
        fk_id_user INT,
        CONSTRAINT fk_user FOREIGN KEY (fk_id_user) REFERENCES users (id_user)
    );

CREATE TABLE
    customers (
        id_customer INT PRIMARY KEY AUTO_INCREMENT,
        name VARCHAR(100) NOT NULL,
        email VARCHAR(100) NOT NULL,
        cpf VARCHAR(11) NOT NULL,
        telephone VARCHAR(15) NOT NULL,
        address VARCHAR(255) NOT NULL,
        gender CHAR(1) NOT NULL,
        birthdate DATE NOT NULL,
        photo VARCHAR(255)
    );

CREATE TABLE
    cart (
        id_cart INT PRIMARY KEY AUTO_INCREMENT,
        fk_id_user INT,
        status VARCHAR(100) NOT NULL
    );

CREATE TABLE
    itens_cart (
        id_itens_cart INT PRIMARY KEY AUTO_INCREMENT,
        fk_id_cart INT,
        fk_id_product INT,
        amount INT NOT NULL,
        unit_value DECIMAL(10, 2) NOT NULL
    );

CREATE TABLE
    product (
        product_id INT PRIMARY KEY AUTO_INCREMENT,
        product_code VARCHAR(50) NOT NULL UNIQUE,
        name VARCHAR(100) NOT NULL,
        price DECIMAL(10, 2) NOT NULL,
        amount INT NOT NULL,
        type_packaging VARCHAR(100) NOT NULL,
        description VARCHAR(255) NOT NULL,
        photo VARCHAR(255)
    );

CREATE TABLE
    sales (
        id_sale INT PRIMARY KEY AUTO_INCREMENT,
        id_customer INT NOT NULL,
        id_user INT NOT NULL,
        date_sale DATETIME NOT NULL,
        FOREIGN KEY (id_customer) REFERENCES customers (id_customer),
        FOREIGN KEY (id_user) REFERENCES users (id_user)
    );

CREATE TABLE
    sale_items (
        id_item INT PRIMARY KEY AUTO_INCREMENT,
        id_sale INT NOT NULL,
        product_id INT NOT NULL,
        quantity INT NOT NULL,
        price_unit DECIMAL(10, 2) NOT NULL,
        FOREIGN KEY (id_sale) REFERENCES sales (id_sale),
        FOREIGN KEY (product_id) REFERENCES product (product_id)
    );