CREATE DATABASE leitor_facial;
USE leitor_facial;

CREATE TABLE faces (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_name VARCHAR(100),
    image_path VARCHAR(255),
    detection_result TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
