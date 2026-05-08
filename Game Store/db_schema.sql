CREATE DATABASE IF NOT EXISTS toy_store;
USE toy_store;

DROP TABLE IF EXISTS products;
CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    slug VARCHAR(100) NOT NULL UNIQUE,
    name VARCHAR(255) NOT NULL,
    short_description VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    category VARCHAR(100) NOT NULL,
    sku VARCHAR(100) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO products (slug, name, short_description, description, price, category, sku) VALUES
('wireless-pro-controller', 'Wireless Pro Controller', 'Best for precision play', 'Built for smooth precision, clean grip, and all-day comfort.', 69.99, 'Accessories', 'WPC-001'),
('spider-hero-collector-set', 'Spider Hero Collector Set', 'Limited edition display item', 'A dynamic collector set designed for display and play.', 39.99, 'Collectibles', 'SHC-002'),
('next-gen-rpg-bundle', 'Next-Gen RPG Bundle', 'Digital + physical edition', 'Includes digital download and exclusive physical content.', 59.99, 'Games', 'NGR-003'),
('rgb-gaming-headset', 'RGB Gaming Headset', 'Comfortable for long sessions', 'High-quality audio with a comfortable fit for marathon sessions.', 49.99, 'Accessories', 'RGB-004'),
('physical-collector-edition', 'Physical Collector Edition', 'Limited stock', 'Premium collector edition with exclusive packaging.', 89.99, 'Games', 'PCE-005'),
('pro-headset', 'Pro Headset', 'Clear sound', 'Designed for superior comfort and crystal-clear audio.', 79.99, 'Accessories', 'PH-006'),
('display-figure', 'Display Figure', 'Collector item', 'Perfect for shelves and displays with premium detail.', 29.99, 'Collectibles', 'DF-007'),
('kids-toy-pack', 'Kids Toy Pack', 'Great gift set', 'A fun toy pack built for young players and gifting.', 24.99, 'Toys', 'KTP-008');
