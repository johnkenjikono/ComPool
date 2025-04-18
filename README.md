# 🏆 ComPool - Pool Money and Compete!

ComPool is a mobile and web application that allows users to create and manage pooled money groups with friends.

---

## 📂 Project Overview
- This project is **HW #3** (mobile frontend connected to the backend).
- Web frontend and backend are continued from **HW #2**, located in the same repo.

---

## 🚀 Setting Up the Project

### 🔹 1️⃣ Prerequisites
Make sure the following tools are installed:
- **XAMPP** (for running PHP and MySQL)
- **Node.js** and **npm**
- **Expo**
- **Android Studio** (for emulator) or a physical Android/iOS device

---

### 🔹 2️⃣ Backend Setup (database same as HW2)

#### 📁 Database Creation in phpMyAdmin
- Start **XAMPP**, ensure **Apache** and **MySQL** are running
- Open `http://localhost/phpmyadmin/`

#### 🛠 Create Database and Tables
```sql
CREATE DATABASE IF NOT EXISTS `app-db`;
USE `app-db`;

CREATE TABLE IF NOT EXISTS users (
    username VARCHAR(50) PRIMARY KEY,
    password VARCHAR(255) NOT NULL
);

CREATE TABLE IF NOT EXISTS groups (
    id INT AUTO_INCREMENT PRIMARY KEY,
    group_name VARCHAR(100) NOT NULL,
    username VARCHAR(50) NOT NULL,
    group_size INT NOT NULL CHECK (group_size > 0),
    members TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
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
│   └── GroupController.php
│   └── UserController.php
│── Model/
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

All backend endpoints are available locally via:
```
http://<YOUR-IP>/index.php/
```

✅ **Create a new file `my-app/config.js` in the project with your real IP address to connect to the database.**
Copy and paste this into the new file (Our file is in the gitignore since it holds sensitive info)
```
// config.js
export const BASE_URL = 'http://yourIPaddress/index.php';
```
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


### 📄 Rest API Endpoints Used
- `POST /user/create` — Register new user
- `GET /user/list?username=...` — Check if user exists
- `POST /group/create` — Create group
- `GET /group/list` — Get all groups
- `DELETE /group/delete?id=...` — Delete group
- `PUT /group/update?id=...` — Update group

📌 **All data is JSON encoded.**

---

## 🧪 Postman Screenshots
Screenshots provided for:
- GET
- POST

### Pierce
![Pierce](images/PiercePostman1.png)
![Pierce](images/PiercePostman2.png)
### Cory
![Cory](images/CoryPostman1.jpeg)
![Cory](images/CoryPostman2.jpeg)
### Kenji
![Kenji](images/KenjiPostman1.png)
![Kenji](images/KenjiPostman2.png)

---

## ✅ Features Summary

### 🔐 User Authentication
- Login and Registration
- Passwords hashed (PHP backend)
- Re-entry confirmation on registration
- Duplicate username protection

### 👥 Group Management (CRUD)
- View all groups
- Create new groups
- Edit group (only if creator)
- Delete group (only if creator)

### 🧠 Validations
- Group size must be a positive integer
- Group creator is auto-included
- Cannot select more members than group size
- Password must be ≥ 10 characters

### 🧠 Persistent Login
- AsyncStorage keeps user logged in
- Auto-login on app restart
- Logout clears session

---

## 📁 File Organization
```
my-app/
├── screens/
│   ├── HomeScreen.js
│   ├── AboutScreen.js
│   ├── LoginScreen.js
│   ├── RegisterScreen.js
│   ├── GroupScreen.js
│   └── CreateGroupScreen.js
├── api/
│   └── userApi.js
│   
├── styles.js
├── config.js
├── App.js
```

---

Split: 36/32/32
(Pierce/Cory/Kenji)


[//]: # (Used Chatgpt to adhere to general design principals and best practices (specifically helped a lot with fixing our sql queries so that they were parameterized), as well as some other fixes (beautifying the README) and optimizations.)
