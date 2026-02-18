CREATE DATABASE IF NOT EXISTS whatsapp_crm DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE whatsapp_crm;

-- Configuration Table
CREATE TABLE IF NOT EXISTS settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    setting_key VARCHAR(50) UNIQUE NOT NULL,
    setting_value TEXT,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Users Table
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'agent') DEFAULT 'agent',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Contacts Table
CREATE TABLE IF NOT EXISTS contacts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    phone_number VARCHAR(20) UNIQUE NOT NULL,
    full_name VARCHAR(100),
    last_message_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Messages Table
CREATE TABLE IF NOT EXISTS messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    contact_id INT NOT NULL,
    wa_message_id VARCHAR(255) UNIQUE,
    body TEXT,
    type VARCHAR(20) DEFAULT 'text',
    direction ENUM('in', 'out') NOT NULL,
    status ENUM('sent', 'delivered', 'read', 'failed', 'received') DEFAULT 'received',
    agent_id INT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (contact_id) REFERENCES contacts(id) ON DELETE CASCADE,
    FOREIGN KEY (agent_id) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- Seed Admin User (default password: admin)
-- In production, this should be changed immediately.
INSERT INTO users (username, password, role) VALUES ('admin', '$2y$10$vqBBWcnMDYKMq6QcJEvSWOgNBQ/KudlBC6xasznaGLXfTTuo6usRdC', 'admin')
ON DUPLICATE KEY UPDATE id=id;

-- Seed Default Settings
INSERT INTO settings (setting_key, setting_value) VALUES 
('wa_access_token', ''),
('wa_phone_number_id', ''),
('wa_business_account_id', ''),
('wa_verify_token', 'my_custom_verify_token_123')
ON DUPLICATE KEY UPDATE id=id;
