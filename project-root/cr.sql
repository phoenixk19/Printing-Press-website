-- Users Table
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    phone VARCHAR(20),
    role ENUM('customer','admin') DEFAULT 'customer',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Orders Table
CREATE TABLE orders (
    order_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    order_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    document_type VARCHAR(100),
    paper_size VARCHAR(50),
    quantity INT,
    document_link VARCHAR(255),
    color ENUM('bw','color'),
    sides ENUM('single','double'),
    paper_type VARCHAR(50),
    paper_thickness VARCHAR(50),
    binding VARCHAR(50),
    lamination VARCHAR(50),
    corners ENUM('square','rounded'),
    hole_punch BOOLEAN DEFAULT 0,
    perforation BOOLEAN DEFAULT 0,
    foil_stamping BOOLEAN DEFAULT 0,
    notes TEXT,
    status ENUM('pending','processing','completed') DEFAULT 'pending',
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- Testimonials Table
CREATE TABLE testimonials (
    testimonial_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    name VARCHAR(100) NOT NULL,
    designation VARCHAR(100),
    content TEXT NOT NULL,
    approved BOOLEAN DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- News Table
CREATE TABLE news (
    news_id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    image VARCHAR(255),
    date DATE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Completed Orders Table
CREATE TABLE completed_orders (
    completed_id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    completion_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    invoice_path VARCHAR(255),
    FOREIGN KEY (order_id) REFERENCES orders(order_id)
);
