# 🏀 Ball’In – E-commerce Web Application

## 📌 Overview
**Ball’In** is a PHP/MySQL e-commerce application with user roles, product management, store management, shopping cart, and order system.  
It supports **persistent carts**, **store stock management**, **order checkout with store selection**, and a complete **authentication system** including password reset with **email (PHPMailer + XAMPP Sendmail)**.  
The app is fully **responsive** and includes **AJAX live search** and **Select2 dropdowns** for better UX.

---

## 🚀 Features

### 👤 User Management
- Roles:
  - `user`: browse, add to cart, and order products
  - `admin`: manage users and global products
  - `shop`: manage their own store’s stock and products
- Register, login, logout
- Forgot password / reset password with secure token
- Social login support (Google, Facebook – can be added via OAuth)

### 🛍️ Product & Store Management
- Add/edit/delete products (admins)
- Shop owners can:
  - Manage their store products
  - Update stock
  - Add existing products to their store
- Categories with hierarchical parent/child support

### 🛒 Cart & Orders
- Add products to cart (persistent across sessions)
- Checkout:
  - Choose store for each product
  - Validate only if stock is available
  - Order creation decreases stock automatically
- Orders history with:
  - Product details
  - Quantity × price
  - Store name and clickable Google Maps link

### 📧 Email System
- Password reset with **PHPMailer + Sendmail (XAMPP)**
- Token stored in DB, valid for 30 minutes
- Reset link sent to user’s email

### 🎨 Frontend
- Modern UI with **HTML5 / CSS3 / Flexbox / Grid**
- Responsive across desktop, tablet, and mobile
- AJAX product search (`search.php`)
- Select2 dropdown with live search for store product management
- Smooth hover effects and animations

---

## 🗄️ Database

The SQL schema is provided in the project:  
`/ballin_db.sql`

It includes:
- `users` (with roles)
- `categories`
- `products`
- `stores`
- `product_store` (stock per store)
- `carts` + `cart_items`
- `orders` + `order_items`
- `password_resets` (for forgot password)

👉 Import `ballin_db.sql` into **phpMyAdmin** to initialize the database.

---

## 📂 Project Structure

```bash
BallIn/
│── app/
│   ├── controllers/
│   │   ├── AuthController.php
│   │   ├── CartController.php
│   │   ├── InscriptionController.php
│   │   ├── OrderController.php
│   │   ├── ProductController.php
│   │   ├── ShopController.php
│   │   └── UserController.php
│   ├── models/
│   │   ├── Cart.php
│   │   ├── Category.php
│   │   ├── Order.php
│   │   ├── Product.php
│   │   ├── Shop.php
│   │   ├── Store.php
│   │   └── User.php
│   └── functions/
│   │   ├── redirection.php
│   │   ├── searchProduct.php
│
│── public/
│   ├── index.php
│   ├── login.php
│   ├── logout.php
│   ├── signup.php
│   ├── register.php
│   ├── forgot-password.php
│   ├── reset-password.php
│   ├── cart.php
│   ├── checkout.php
│   ├── contact.php
│   ├── shop.php
│   ├── store/products.php (shop management)
│   ├── search.php (AJAX search)
│   └── css/
│   │   ├── global.css
│   │   ├── searchMenu.css
│   │   ├── users.css
│
│── resources/
│   └── views/
│       ├── header.php
│       ├── footer.php
│       └── head.php
│
│── config/
│   ├── config.php
│
│── vendor/ (Composer dependencies, PHPMailer)
│── ballin_db.sql
│── README.md
```

---

## ⚙️ Installation Guide

### 1. Requirements
- PHP 8+
- MySQL (XAMPP or similar)
- Composer
- PHPMailer (installed via Composer)
- Sendmail (configured with XAMPP for Gmail SMTP)

### 2. Setup

```bash
# Clone project
git clone https://github.com/your-username/BallIn.git
cd BallIn

# Install dependencies
composer install
```

### 3. Database
- Open **phpMyAdmin**  
- Import `ballin_db.sql`

### 4. Config Database
Edit `app/config/config.php`:

```php
$conn = new mysqli("localhost", "root", "", "ballin_db");
```

### 5. Configure Sendmail (XAMPP)

Edit `xampp/sendmail/sendmail.ini`:

```ini
smtp_server=smtp.gmail.com
smtp_port=587
smtp_ssl=tls
auth_username=your.email@gmail.com
auth_password=your-app-password
```

Edit `xampp/php/php.ini`:

```ini
[mail function]
sendmail_path = "C:\\xampp\\sendmail\\sendmail.exe -t"
```

Then restart Apache.

---

## 🔑 Password Reset Flow
1. User enters email in `forgot-password.php`
2. Token stored in `password_resets` table
3. Email sent with PHPMailer (reset link)
4. User clicks link → `reset-password.php?token=...`
5. User sets new password, token is deleted

---

## 📱 Responsive Design
Uses **Flexbox** and **CSS Grid** with **media queries**.

Example breakpoints:

```css
@media (max-width: 768px) {
    .product-grid { grid-template-columns: 1fr; }
    header h1 { font-size: 2rem; }
}
@media (max-width: 480px) {
    nav ul { flex-direction: column; }
    .container { width: 95%; }
}
```

---

## ✅ Future Improvements
- Payment integration (Stripe/PayPal)
- Order confirmation emails
- Admin dashboard with analytics
- API endpoints for mobile app

---

## 📌 Run the App
Start XAMPP (Apache + MySQL) then open:

👉 [http://localhost/BallIn/public/](http://localhost/BallIn/public/)

---

## 👨‍💻 Author
Developed as a full-stack PHP/MySQL project with focus on:
- Clean MVC structure
- Secure authentication
- Store & order management
- Responsive UI/UX

---