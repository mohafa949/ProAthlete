CREATE DATABASE proathlete;
USE proathlete;

-- Admin table
CREATE TABLE admins (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100) NOT NULL
);

-- Products table
CREATE TABLE products (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    category ENUM('homme', 'femme', 'enfant') NOT NULL,
    image VARCHAR(255),
    stock INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Orders table
CREATE TABLE orders (
    id INT PRIMARY KEY AUTO_INCREMENT,
    product_id INT,
    customer_name VARCHAR(100) NOT NULL,
    customer_email VARCHAR(100) NOT NULL,
    customer_address TEXT NOT NULL,
    customer_phone VARCHAR(20),
    quantity INT DEFAULT 1,
    order_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status ENUM('en attente', 'traité', 'expédié') DEFAULT 'en attente',
    FOREIGN KEY (product_id) REFERENCES products(id)
);

-- Insert default admin (password: admin123)
INSERT INTO admins (username, password, email) 
VALUES ('admin', '$2y$10$YourHashedPasswordHere', 'admin@proathlete.com');

-- Insert sample products
INSERT INTO products (name, description, price, category, image, stock) VALUES
('Chaussures de Running', 'Chaussures de course confortables pour toutes les surfaces', 89.99, 'homme', 'running_shoes.jpg', 50),
('Maillot de Football', 'Maillot officiel de football haute qualité', 49.99, 'homme', 'football_jersey.jpg', 30),
('Raquette de Tennis', 'Raquette professionnelle légère', 129.99, 'femme', 'tennis_racket.jpg', 20),
('Ballon de Basketball', 'Ballon officiel taille 7', 34.99, 'enfant', 'basketball.jpg', 100),
('Survêtement Sportif', 'Survêtement confortable pour entraînement', 69.99, 'femme', 'tracksuit.jpg', 40),
('Sac de Sport', 'Sac spacieux avec compartiments multiples', 39.99, 'homme', 'sports_bag.jpg', 60);

-- Créer la table des catégories
CREATE TABLE IF NOT EXISTS categories (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(50) UNIQUE NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insérer les catégories par défaut
INSERT INTO categories (name) VALUES 
('homme'), 
('femme'), 
('enfant')
ON DUPLICATE KEY UPDATE name=name;