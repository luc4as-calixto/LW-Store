-- create database
CREATE DATABASE lwstore

USE lwstore 

-- create table

CREATE TABLE usuarios (
    id_usuario INT PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(100) NOT NULL,
    senha VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    cpf VARCHAR(11) NOT NULL,
    telefone VARCHAR(15) NOT NULL,
    endereco VARCHAR(255) NOT NULL,
    genero CHAR(1) NOT NULL,
    data_nascimento DATE NOT NULL,
    tipo_usuario VARCHAR(20) NOT NULL,
    foto VARCHAR(255)
)

CREATE TABLE vendedor (
    id_vendedor INT PRIMARY KEY AUTO_INCREMENT,
    fk_id_usuario INT,
    foto VARCHAR(255),  

    CONSTRAINT fk_usuario FOREIGN KEY (fk_id_usuario) REFERENCES usuarios(id_usuario)
)

CREATE TABLE carrinho (
    id_carrinho INT PRIMARY KEY AUTO_INCREMENT,
    fk_id_usuario INT,
    status VARCHAR(100) NOT NULL
)

CREATE TABLE itens_carrinho (
    id_itens_carrinho INT PRIMARY KEY AUTO_INCREMENT,
    fk_id_carrinho INT,
    fk_id_produto INT,
    quantidade INT NOT NULL,
    valor_unitario DECIMAL(10, 2) NOT NULL
)

CREATE TABLE produto (
    codigo_produto INT PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(100) NOT NULL, 
    preco DECIMAL(10, 2) NOT NULL,
    quantidade INT NOT NULL,
    tipo_embalagem VARCHAR(100) NOT NULL,
    descricao VARCHAR(255) NOT NULL,
    foto VARCHAR(255)
)

CREATE TABLE venda(
    id_venda INT PRIMARY KEY AUTO_INCREMENT,
    fk_id_usuario INT,
    fk_id_produto INT,
    data_venda DATE NOT NULL,
    valor_total DECIMAL(10, 2) NOT NULL
)