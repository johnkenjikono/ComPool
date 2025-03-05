# ComPool - Pool Money and Compete!

# This is HW #2 all HW #1 files are in the HW 1 folder

# Assuming XAMPP is installed here is the steps to set up MySQL

Setting up users table in app-db:

CREATE DATABASE IF NOT EXISTS `app-db`;
USE `app-db`;

CREATE TABLE IF NOT EXISTS users (
    username VARCHAR(50) PRIMARY KEY,
    password VARCHAR(255) NOT NULL
);

Setting up groups table

CREATE TABLE IF NOT EXISTS groups (
    id INT AUTO_INCREMENT PRIMARY KEY,
    group_name VARCHAR(100) NOT NULL,
    username VARCHAR(50) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (username) REFERENCES users(username) ON DELETE CASCADE
);


