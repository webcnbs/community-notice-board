-- schema.sql
CREATE DATABASE IF NOT EXISTS cnbs CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE cnbs;

CREATE TABLE users (
  user_id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) UNIQUE NOT NULL,
  email VARCHAR(100) UNIQUE NOT NULL,
  password VARCHAR(255) NOT NULL,
  role ENUM('resident','manager','admin') DEFAULT 'resident',
  status ENUM('pending','active','disabled') DEFAULT 'pending',
  remember_token VARCHAR(255) NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE categories (
  category_id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(50) UNIQUE NOT NULL,
  description VARCHAR(255),
  color_code VARCHAR(7) DEFAULT '#888888'
);

CREATE TABLE notices (
  notice_id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(150) NOT NULL,
  content TEXT NOT NULL,
  category_id INT NOT NULL,
  priority ENUM('High','Medium','Low') DEFAULT 'Low',
  user_id INT NOT NULL,
  expiry_date DATE NULL,
  views INT DEFAULT 0,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (category_id) REFERENCES categories(category_id),
  FOREIGN KEY (user_id) REFERENCES users(user_id)
);

CREATE TABLE comments (
  comment_id INT AUTO_INCREMENT PRIMARY KEY,
  notice_id INT NOT NULL,
  user_id INT NOT NULL,
  content TEXT NOT NULL,
  status ENUM('pending','approved','rejected') DEFAULT 'pending',
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (notice_id) REFERENCES notices(notice_id),
  FOREIGN KEY (user_id) REFERENCES users(user_id)
);

CREATE TABLE bookmarks (
  bookmark_id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  notice_id INT NOT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY uniq_user_notice (user_id, notice_id),
  FOREIGN KEY (user_id) REFERENCES users(user_id),
  FOREIGN KEY (notice_id) REFERENCES notices(notice_id)
);

CREATE TABLE notifications (
  notification_id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  message VARCHAR(255) NOT NULL,
  is_read TINYINT(1) DEFAULT 0,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(user_id)
);

CREATE TABLE audit_logs (
  log_id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NULL,
  action VARCHAR(100) NOT NULL,
  details TEXT,
  ip_address VARCHAR(45),
  timestamp DATETIME DEFAULT CURRENT_TIMESTAMP
);