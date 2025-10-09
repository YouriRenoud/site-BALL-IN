-- Création de la base et sélection
CREATE DATABASE IF NOT EXISTS ballin_db;
USE ballin_db;

DROP TABLE IF EXISTS order_items;
DROP TABLE IF EXISTS product_store;
DROP TABLE IF EXISTS cart_items;
DROP TABLE IF EXISTS password_resets;
DROP TABLE IF EXISTS orders;
DROP TABLE IF EXISTS products;
DROP TABLE IF EXISTS categories;
DROP TABLE IF EXISTS stores;
DROP TABLE IF EXISTS carts;
DROP TABLE IF EXISTS users;

-- Table users
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    birth_date DATE,
    address VARCHAR(255),
    role ENUM('user','admin', 'shop') DEFAULT 'user',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Table categories
CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    parent_id INT DEFAULT NULL,
    FOREIGN KEY (parent_id) REFERENCES categories(id) ON DELETE SET NULL
);

-- Table products
CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    image_url VARCHAR(255),
    category_id INT,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL
);

-- Table stores
CREATE TABLE stores (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    address VARCHAR(255) NOT NULL,
    google_maps_link VARCHAR(255),
    user_id INT,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
);

-- Table de liaison product_store
CREATE TABLE product_store (
    product_id INT,
    store_id INT,
    stock_quantity INT DEFAULT 0,
    PRIMARY KEY (product_id, store_id),
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    FOREIGN KEY (store_id) REFERENCES stores(id) ON DELETE CASCADE
);

CREATE TABLE carts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE cart_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cart_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT DEFAULT 1,
    FOREIGN KEY (cart_id) REFERENCES carts(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    store_id INT NOT NULL,
    quantity INT NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    FOREIGN KEY (store_id) REFERENCES stores(id) ON DELETE CASCADE
);

CREATE TABLE password_resets (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    token VARCHAR(255) NOT NULL,
    expires_at DATETIME NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- ================================
-- Données de test
-- ================================

-- Utilisateurs
INSERT INTO users (username, email, password_hash, birth_date, address, role)
VALUES 
('admin', 'admin@ballin.com', '$2y$10$0Alq1nd88sZT5getWZEUiuAYNtCp5NpWBQ6XtsG4D6W1RlM3vAiny', '1990-01-01', '10 Rue du Panier, Toulouse', 'admin'),
('johndoe', 'john@gmail.com', '$2y$10$aIoEKPaFNJo1sP/WZnAxWu1IzwKocPHmp1QHSrpGEA3PhrEG.KPp2', '2000-05-10', '25 Avenue du Sport, Paris', 'user'),
('b4b', 'b4b@gmail.com', '$2y$10$Y4r/Uh4wTf4DDYn2GyP0NugnHXGDxvHU4tBYnAikmJrcE9cs8HplW', '1995-03-15', '30 Rue de la Paix, Paris', 'shop'),
('gameTime', 'gametime@gmail.com', '$2y$10$o/IRkCx5dsQ7AcvIINALtuixx48G0L9uXg4lXFEUPxvk9ZW9o2jPK', '1998-07-22', '40 Rue des Jeux, Toulouse', 'shop'),
('Lhasir', 'yourirenoudgrappe@gmail.com', '$2y$10$yc5GJFsMAV4EgbJXs66bU.E/Qww7gtHqLqWRRYvlO7KtPhdVz3b6i', '2003-11-20', '34 Rue de Franche Comté, Narbonne', 'user');

-- Catégories
INSERT INTO categories (name) VALUES
('Basketballs'),
('Shoes'),
('Apparel'),
('Accessories');

-- Produits
INSERT INTO products (name, description, price, image_url, category_id)
VALUES
('Spalding NBA Official Ball', 'Official size and weight basketball.', 39.99, 'images/products/ball1.jpg', 1),
('Nike Air Zoom', 'High performance basketball shoes.', 89.99, 'images/products/shoes1.jpg', 2),
('Jordan Jersey', 'Authentic NBA jersey.', 69.99, 'images/products/jersey1.jpg', 3),
('Headband Pack', 'Sweat-absorbent headbands.', 14.99, 'images/products/headband.jpg', 4);

-- Magasins
INSERT INTO stores (name, address, google_maps_link, user_id)
VALUES
('Game Time', '6 Rue Temponières, 31000 Toulouse, France', 'https://maps.app.goo.gl/H25deJR9VqwxZN2X6', 4),
('Basket4Ballers', '31 Rue de Rivoli, 75004 Paris, France', 'https://maps.app.goo.gl/B2RZJ3mk96kRSASVA', 3);

-- Disponibilités produit/magasin
INSERT INTO product_store (product_id, store_id, stock_quantity)
VALUES
(1, 1, 20),
(1, 2, 15),
(2, 1, 10),
(3, 2, 5),
(4, 1, 25),
(4, 2, 30);
