-- create database
CREATE DATABASE IF NOT EXISTS lwstore;

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

    INSERT INTO product (product_code, name, price, amount, type_packaging, description) VALUES
    ('PRD001', 'Cabo HDMI 2m', 29.90, 100, 'Caixa', 'Cabo HDMI 2 metros com suporte a 4K'),
    ('PRD002', 'Mouse Óptico USB', 45.50, 75, 'Caixa', 'Mouse com sensor óptico de alta precisão'),
    ('PRD003', 'Teclado Mecânico Gamer', 189.90, 50, 'Caixa', 'Teclado com switches vermelhos e iluminação RGB'),
    ('PRD004', 'Adaptador USB para Ethernet', 59.90, 30, 'Pacote', 'Adaptador USB 3.0 para RJ45'),
    ('PRD005', 'Pen Drive 64GB', 39.99, 120, 'Pacote', 'Pen Drive USB 3.0 de alta velocidade'),
    ('PRD006', 'Hub USB 4 portas', 25.00, 80, 'Outro', 'Hub USB com 4 portas 2.0'),
    ('PRD007', 'Monitor 24" LED', 849.00, 10, 'Caixa', 'Monitor LED 24 polegadas Full HD'),
    ('PRD008', 'Webcam Full HD', 129.90, 60, 'Caixa', 'Webcam com microfone embutido e resolução 1080p'),
    ('PRD009', 'Cadeira Gamer Azul', 999.90, 5, 'Caixa', 'Cadeira gamer com apoio lombar e ajuste de altura'),
    ('PRD010', 'Microfone de Mesa USB', 79.90, 40, 'Caixa', 'Microfone para gravações e videoconferência'),
    
    ('PRD011', 'Fone Bluetooth In-Ear', 89.90, 70, 'Caixa', 'Fone sem fio com microfone e case carregador'),
    ('PRD012', 'Suporte de Celular Articulado', 34.90, 90, 'Pacote', 'Suporte para celular com ajuste 360º'),
    ('PRD013', 'Caixa de Som Bluetooth', 149.90, 25, 'Caixa', 'Caixa de som portátil com rádio FM'),
    ('PRD014', 'Cartão SD 32GB', 32.00, 110, 'Pacote', 'Cartão de memória Classe 10'),
    ('PRD015', 'Bateria Portátil 10.000mAh', 99.90, 45, 'Outro', 'Power bank com entrada USB-C e USB-A'),
    ('PRD016', 'Impressora Térmica USB', 329.90, 20, 'Caixa', 'Impressora de recibos para PDV'),
    ('PRD017', 'Notebook 15.6" i5 8GB SSD', 2899.00, 8, 'Caixa', 'Notebook com SSD 256GB e Windows 11'),
    ('PRD018', 'Suporte de Monitor', 69.90, 35, 'Outro', 'Base ergonômica para monitor ou notebook'),
    ('PRD019', 'Cabo de Rede Cat6 5m', 19.90, 100, 'Pacote', 'Cabo Ethernet blindado categoria 6'),
    ('PRD020', 'Roteador Wi-Fi Dual Band', 249.90, 18, 'Caixa', 'Roteador com 4 antenas e suporte a 5GHz'),
    
    ('PRD021', 'HD Externo 1TB', 369.90, 22, 'Caixa', 'HD portátil USB 3.0 com 1TB de capacidade'),
    ('PRD022', 'Mousepad Gamer', 29.90, 65, 'Pacote', 'Mousepad com base emborrachada e tecido liso'),
    ('PRD023', 'Controle Xbox One', 319.90, 15, 'Caixa', 'Controle sem fio compatível com PC e console'),
    ('PRD024', 'Cabo USB Tipo C 1m', 14.90, 140, 'Pacote', 'Cabo de carregamento rápido USB-C'),
    ('PRD025', 'Adaptador HDMI para VGA', 24.90, 60, 'Outro', 'Adaptador de vídeo com alimentação externa'),
    ('PRD026', 'Suporte Articulado TV 32-55"', 119.90, 12, 'Caixa', 'Suporte para TV com inclinação ajustável'),
    ('PRD027', 'Filtro de Linha 5 Tomadas', 39.90, 33, 'Caixa', 'Filtro bivolt com proteção contra surtos'),
    ('PRD028', 'Scanner Portátil A4', 459.90, 10, 'Caixa', 'Scanner portátil com alimentação USB'),
    ('PRD029', 'Estabilizador 500VA', 199.90, 14, 'Caixa', 'Estabilizador para computadores e periféricos'),
    ('PRD030', 'HD SSD 480GB', 239.90, 28, 'Caixa', 'Disco sólido de 480GB com alto desempenho'),
    
    ('PRD031', 'Câmera IP Wi-Fi', 179.90, 17, 'Caixa', 'Câmera de segurança com visão noturna'),
    ('PRD032', 'Controle Universal TV', 19.90, 50, 'Pacote', 'Controle compatível com várias marcas de TV'),
    ('PRD033', 'Cooler para Notebook', 59.90, 40, 'Caixa', 'Base com ventoinhas para refrigeração'),
    ('PRD034', 'Switch 5 Portas Gigabit', 139.90, 16, 'Caixa', 'Switch de rede com 5 portas 10/100/1000'),
    ('PRD035', 'Pendrive 128GB', 59.90, 75, 'Pacote', 'Pendrive USB 3.1 compacto e veloz'),
    ('PRD036', 'Óculos VR', 229.90, 13, 'Caixa', 'Óculos de realidade virtual para celular'),
    ('PRD037', 'Teclado Slim USB', 49.90, 40, 'Caixa', 'Teclado padrão ABNT2 com design compacto'),
    ('PRD038', 'Mouse Gamer RGB', 79.90, 25, 'Caixa', 'Mouse com DPI ajustável e iluminação colorida'),
    ('PRD039', 'Webcam 720p com Clip', 59.90, 30, 'Outro', 'Webcam HD com clipe para monitor'),
    ('PRD040', 'Tripé para Celular', 19.90, 60, 'Pacote', 'Tripé dobrável para fotos e vídeos'),
    
    ('PRD041', 'Placa de Vídeo GTX 1660', 1499.90, 6, 'Caixa', 'Placa dedicada com 6GB GDDR5'),
    ('PRD042', 'Memória RAM 8GB DDR4', 159.90, 32, 'Pacote', 'Memória para notebook 2666MHz'),
    ('PRD043', 'Fonte 500W 80 Plus', 269.90, 20, 'Caixa', 'Fonte com certificação e cabos protegidos'),
    ('PRD044', 'Ventilador USB Portátil', 24.90, 70, 'Pacote', 'Ventilador pequeno e prático para mesa'),
    ('PRD045', 'Tablet 10" Android', 799.90, 9, 'Caixa', 'Tablet com Wi-Fi, 64GB e processador quad-core'),
    ('PRD046', 'Smartwatch Bluetooth', 179.90, 14, 'Caixa', 'Relógio inteligente com medidor de passos'),
    ('PRD047', 'Pulseira Mi Band 6', 199.90, 18, 'Pacote', 'Pulseira fitness com monitor cardíaco'),
    ('PRD048', 'Carregador Turbo 20W', 39.90, 55, 'Pacote', 'Carregador rápido com porta USB-C'),
    ('PRD049', 'Notebook Gamer Ryzen 7', 4999.90, 3, 'Caixa', 'Notebook com RTX e SSD 512GB'),
    ('PRD050', 'Extensão Elétrica 3m', 29.90, 47, 'Outro', 'Extensão com 3 tomadas e fio reforçado');
