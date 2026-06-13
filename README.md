# 📚 OCBES — Online Community Book Exchange System

A prototype web application that lets community members list books they own, browse and search books shared by others, and request to borrow them — built as a Final Year Project.

## ✨ Features

- **User Authentication & Profile Management** — register, login, logout, and manage your profile
- **Book Listing & Management** — add, edit, and delete books; mark them as *Available* or *Lent*
- **Search** — find books by title or author
- **Borrow Request System** — send borrow requests, view incoming/outgoing requests, and accept/reject/cancel them
- **Admin Support** — promote any user to admin via the database

## 🛠️ Tech Stack

- **Backend:** PHP (OOP, PDO with prepared statements, exception handling)
- **Database:** MySQL
- **Frontend:** PHP templates (forms, shared header/footer layout)

## 📂 Project Structure

```
public/
  index.php              # Home / book listing & search
  _layout/               # Shared header & footer
  auth/                  # Login, register, logout
  books/                 # My books — add, edit, delete
  requests/              # Borrow requests — create, view, accept/reject/cancel
  profile.php            # User profile management
  uploads/books/         # Uploaded book images (gitignored)
src/
  config.php             # Database & app configuration
  db.php                 # PDO database connection
  auth.php               # Authentication helper functions
  classes/
    User.php
    Book.php
    BookImage.php
    BorrowRequest.php
schema.sql               # Database schema (users, books, borrow_requests)
```

## 🚀 Getting Started

### Prerequisites
- PHP 7.4+ (with PDO MySQL extension)
- MySQL / MariaDB

### Setup

1. **Clone the repository**
   ```bash
   git clone https://github.com/<your-username>/OCBES.git
   cd OCBES
   ```

2. **Create the database and user**
   ```sql
   CREATE DATABASE book_exchange CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
   CREATE USER 'book_user'@'localhost' IDENTIFIED BY 'book_pass';
   GRANT ALL PRIVILEGES ON book_exchange.* TO 'book_user'@'localhost';
   FLUSH PRIVILEGES;
   ```

3. **Import the schema**
   ```bash
   mysql -u book_user -p book_exchange < schema.sql
   ```

4. **Configure database credentials**

   Update `src/config.php` with your database name, username, and password.

5. **Run the development server**
   ```bash
   php -S localhost:8080 -t public
   ```

   Visit **http://localhost:8080**

### Making an Admin

After registering a user, set `is_admin = 1` on that user's row in the `users` table to grant admin access.

## 📸 Screenshots

## 📸 Screenshots

### Home Page
![Home Page](Screenshot/Home%20page.PNG)

### Discover Books
![Discover Books](Screenshot/Discover%20books.PNG)

### My Books
![My Books](Screenshot/My%20books.PNG)

### Add New Book
![Add New Book](Screenshot/Add%20new%20book.PNG)

## 📌 Status

This is a **prototype** built for academic purposes (Final Year Project), demonstrating core OOP design, database integration, and CRUD workflows in PHP.

## 📄 License

This project is open source and available for educational use.
