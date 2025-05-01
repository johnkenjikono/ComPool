# 🏆 ComPool - Pool Money and Compete!

ComPool is a mobile and web application that allows users to create and manage pooled money groups with friends.

---

## 📂 Project Overview
- This the final Project
- Web/Mobile frontend and backend are built from previous HWs, located in the same repo.
- Mobile app files (my-app)
- Web Development files (HW1)
---

## 📱 Running Tests (Problem 1)
Open terminal and cd into ComPool then:
```
cd HW1
```
```
cd test-project
```
run:
```
php vendor/bin/phpunit tests
```
To run individual tests these are the following commands for each test respectively:
```
php vendor/bin/phpunit tests/TestGet_UserListTest.php
php vendor/bin/phpunit tests/TestPost_CreateUserTest.php
php vendor/bin/phpunit tests/TestPost_FailedLoginTest.php
php vendor/bin/phpunit tests/TestPost_LoginUserTest.php
```

---

## Generative AI (Problem 2)



---
## 🚀 Setting Up the Project (Mobile)

### 🔹 1️⃣ Prerequisites
Make sure the following tools are installed:
- **XAMPP** (for running PHP and MySQL)
- **Node.js** and **npm**
- **Expo**
- **Android Studio** (for emulator) or a physical Android/iOS device

---

### 🔹 2️⃣ Backend Setup (database small changes from HW2)

#### 📁 Database Creation in phpMyAdmin
- Start **XAMPP**, ensure **Apache** and **MySQL** are running
- Open `http://localhost/phpmyadmin/`

#### 🛠 Create Database and Tables
```sql
-- Create the database
CREATE DATABASE IF NOT EXISTS `app-db`;
USE `app-db`;

-- Create the users table
CREATE TABLE IF NOT EXISTS users (
    username VARCHAR(50) PRIMARY KEY,
    password VARCHAR(255) NOT NULL,
    balance DECIMAL(10,2) DEFAULT 1000.00
);

-- Create the groups table
CREATE TABLE IF NOT EXISTS groups (
    id INT AUTO_INCREMENT PRIMARY KEY,
    group_name VARCHAR(100) NOT NULL,
    username VARCHAR(50) NOT NULL,
    group_size INT NOT NULL CHECK (group_size > 0),
    members TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    funds DECIMAL(10,2) DEFAULT 0.00,
    FOREIGN KEY (username) REFERENCES users(username) ON DELETE CASCADE
);

```


#### 🧩 Backend code:
The folder RestAPI has all files for the backend code

#### 📁 Place REST API in HTDOCS

1. Locate the `RestAPI/` folder in this repository.

2. Copy the **entire contents** of the `RestAPI/` folder into your `htdocs/` folder inside your local XAMPP directory.

```
C:/xampp/htdocs/
```

the file: `inc/config.php`, has your connection to the database locally. Change it accordingly.
You can create the file and copy and paste this in. (This is not in the git ignore since we copied a safe version of our rest API into the repo)
```
<?php
define("DB_HOST", "localhost");
define("DB_USERNAME", "root");
define("DB_PASSWORD", "");
define("DB_DATABASE_NAME", "app-db");
```
The final structure should look like:

```
htdocs/
├── Controller/Api/
│   ├── BaseController.php
│   ├── ChatController.php
│   ├── GroupController.php
│   └── UserController.php

│── Model/
│   ├── ChatModel.php
│   ├── Database.php
│   ├── GroupModel.php
│   └── UserModel.php
├── index.php
├── inc/
│   ├── bootstrap.php
│   └──  config.php
```

inc/config.php has your connection to the database locally make sure you changed it accordingly

## 🔁 REST API Connection to our code

✅ **Create a new file `my-app/config.js` in the project with your real IP address to connect to the database.**
Copy and paste this into the new file (Our file is in the gitignore since it holds sensitive info)
```
// config.js
export const BASE_URL = 'http://yourIPaddress/index.php';
export const OPENAI_API_KEY = 'sk-*****...'
```
**The helpdesk feature will only work if you have a paid OpenAI API account.**
---

## 📱 Running the Mobile App
Open Andorid studio and Open our project Compool wherever you downloaded it
Start your android device

Open terminal and
cd into my-app from Compool
```
cd my-app
```
run:
```
npm install
npm run android
```

And you should see Compool running on your virtual device!
---

## 📄 REST API Endpoints Used

### 🔐 User Endpoints
- `POST /user/create` — Register new user  
- `POST /user/login` — Login and verify password  
- `GET /user/list?username=...` — Check if user exists  
- `PUT /user/updatePassword?username=...` — Update user password  
- `DELETE /user/delete?username=...` — Delete a user  
- `GET /user/balance?username=...` — Get user’s current balance

### 👥 Group Endpoints
- `POST /group/create` — Create new group  
- `GET /group/list` — Get all groups  
- `GET /group/view?id=...` — Get specific group by ID  
- `POST /group/update?id=...` — Update group info  
- `DELETE /group/delete?id=...` — Delete a group  
- `POST /group/payin` — Member pays into group fund  
- `POST /group/payout` — Leader pays out from group fund to member

📌 **All requests and responses use JSON.**

---

## ✅ Features Summary

### 🔐 User Authentication
- User registration & login with secure password hashing (PHP backend)
- Validation for password length (≥ 10 characters)
- Prevents duplicate usernames
- Persistent login using AsyncStorage
- Logout with confirmation prompt

### 👥 Group Management
- View all groups user is a part of
- Create group with specified size and member selection
- Group creator is auto-included
- Edit or delete group (only if creator)
- Client-side validations:
  - Group size must be a valid positive integer
  - Cannot add more members than group size
- Group creation screen is dynamically interactive
- Creator and members see different UI options based on role

### 💬 Group Chat
- Per-group chat feature
- Messages saved and fetched via REST API
- Available to all group members
- Clean UI for reading and sending messages

### 💰 Mock Money System
- Each user starts with a $1000 balance (simulated)
- Each group has its own fund balance
- Members can pay into the group (via "Pay In")
- Group leaders can pay out funds to any group member
- Funds update in real-time after each transaction
- Balances displayed to users

### 🧠 HelpDesk (AI Chat)
- In-app chat interface to talk to AI assistant (GPT)
- Uses OpenAI API (via `OPENAI_API_KEY` stored securely)
- Scrollable chat history
- Instant feedback and assistant support inside the app

---

Split: 33/33/33
(Pierce/Cory/Kenji)


[//]: # (Used Chatgpt to adhere to general design principals and best practices (specifically helped a lot with fixing our sql queries so that they were parameterized), as well as some other fixes (beautifying the README) and optimizations.)
