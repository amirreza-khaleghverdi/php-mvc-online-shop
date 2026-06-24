# 🛍️ OSI Shop — PHP MVC Online Shop

> A full-featured e-commerce web application built with PHP using the MVC architecture pattern, featuring role-based access for customers, couriers, and admins.

---

## 📌 Project Overview

OSI Shop is a university project that simulates a real-world online shopping system. It supports three types of users — customers who browse and buy products, couriers who manage deliveries, and admins who control everything. The project was built with raw PHP (no frameworks) to demonstrate core backend development concepts.

---

## 🏗️ Architecture

This project follows the **MVC (Model-View-Controller)** pattern:

```
OSI-Shop/
│
├── index.php                        # Front controller / Router
│
├── Model/                           # Data layer — database queries
│   ├── Database.php                 # PDO connection
│   ├── Login.php                    # Customer & courier register/login
│   ├── Product.php                  # Product queries
│   ├── Cart.php                     # Cart operations
│   ├── Orders.php                   # Order management
│   ├── OrderItems.php               # Order item management
│   ├── Courier.php                  # Courier shipment logic
│   ├── AdminLogin.php               # Admin auth + customer/courier management
│   └── AdminData.php                # Admin CRUD for all entities
│
├── Controller/                      # Logic layer — handles requests
│   ├── LoginController/
│   │   ├── Login.php                # Login logic
│   │   └── Register.php             # Register logic
│   ├── HomeController/
│   │   └── Homepage.php             # Homepage
│   ├── CartController/
│   │   └── Cartpage.php             # Cart operations
│   ├── ViewController/
│   │   └── Viewpage.php             # Product detail page
│   ├── CheckoutController/
│   │   └── Checkout.php             # Checkout & order confirmation
│   ├── DashboardController/
│   │   └── Dashboard.php            # Customer dashboard
│   ├── ViewOrderDetails/
│   │   └── ViewOrdersDetails.php    # Order details
│   ├── CourierController/
│   │   └── Courier.php              # Courier dashboard & status updates
│   └── AdminController/
│       └── Admin.php                # Full admin panel
│
├── View/                            # Presentation layer — HTML/PHP templates
│   ├── Homepage.php
│   ├── Login.php
│   ├── Register.php
│   ├── Cartpage.php
│   ├── Viewpage.php
│   ├── Checkout.php
│   ├── Dashboard.php
│   ├── OrderDetails.php
│   ├── courier_shipments.php
│   ├── Admin.php
│   └── Admin/
│       ├── AdminLogin.php
│       ├── AdminDashboard.php
│       ├── AdminProducts.php
│       ├── AdminEditProduct.php
│       ├── AdminCustomers.php
│       ├── AdminCouriers.php
│       ├── AdminOrders.php
│       ├── AdminInventory.php
│       ├── AdminShipments.php
│       ├── AdminWarehouses.php
│       └── AdminDeliveryStatus.php
│
└── Templates/
    └── style.css                    # Global stylesheet
```

---

## 🗄️ Database Schema

The project uses **MariaDB/MySQL** with the following tables:

| Table | Description |
|---|---|
| `customers` | Customer accounts |
| `courier` | Courier accounts |
| `admin` | Admin accounts |
| `product` | Product catalog |
| `cart` | Shopping cart items |
| `orders` | Customer orders |
| `orderitem` | Items within each order |
| `inventory` | Stock per warehouse |
| `warehouse` | Warehouse locations |
| `shipment` | Shipments assigned to couriers |
| `deliverystatus` | Shipment status history |

### Key Relationships:
- `cart` → references `customers` and `product`
- `orders` → references `customers`
- `orderitem` → references `orders` and `product`
- `inventory` → references `product` and `warehouse`
- `shipment` → references `orders` and `courier`
- `deliverystatus` → references `shipment`

---

## 👥 User Roles

### 🛒 Customer
- Browse latest products on homepage
- View product details
- Add products to cart
- Update/remove cart items
- Checkout and confirm orders
- View order history and status in dashboard
- View order details

### 🚚 Courier
- View assigned shipments
- Update shipment status step by step:
  `picked_up` → `in_transit` → `sorting_center` → `out_for_delivery` → `delivered` / `failed_attempt`
- Status cannot go backwards
- Finalized shipments (`delivered` / `failed_attempt`) cannot be changed

### 🛠️ Admin
- **Products:** Add, edit, delete products
- **Customers:** View, inline edit, delete (blocked if they have orders)
- **Couriers:** View, inline edit, delete (blocked if they have shipments)
- **Orders:** View all orders, update order status
- **Inventory:** View and update stock levels per warehouse
- **Shipments:** Add shipments, assign couriers, update delivery status
- **Warehouses:** Add and edit warehouses
- **Delivery Status History:** View full log of all status updates

---

## 🔐 Security Features

- **Password Hashing** — all passwords hashed with `password_hash()` using `PASSWORD_BCRYPT`
- **SQL Injection Prevention** — all queries use PDO prepared statements with `bindValue()`
- **XSS Prevention** — all output escaped with `htmlspecialchars()`
- **Role-based Access Control** — each controller checks session role before allowing access
- **Admin Protection** — admin session is separate (`admin_id`) from user session (`user_id`)
- **Data Integrity** — foreign key constraints prevent deletion of records with dependencies

---

## 🛒 Order Flow

```
Customer browses products
        ↓
Adds items to cart
        ↓
Clicks Pay Now → order created as 'pending'
        ↓
Confirms order → stock reduced, cart cleared, order → 'completed'
        ↓
Admin creates shipment and assigns courier
        ↓
Courier updates status step by step
        ↓
Order delivered ✅
```

---

## ⚙️ Setup Instructions

### Requirements
- XAMPP (Apache + MySQL/MariaDB + PHP 8+)
- PHP 8.0 or higher
- MySQL / MariaDB

### Steps

**1. Clone the repository:**
```bash
git clone https://github.com/yourusername/osi-shop.git
```

**2. Move to XAMPP:**
```
Copy the project folder to: C:/xampp/htdocs/website/
```

**3. Import the database:**
- Open phpMyAdmin at `http://localhost/phpmyadmin`
- Create a new database named `project_db`
- Import `database.sql`

**4. Configure database connection:**

Edit `Model/Database.php`:
```php
$host = 'localhost';
$db   = 'project_db';
$user = 'root';
$pass = '';
```

**5. Create an admin account:**

Run in phpMyAdmin:
```sql
INSERT INTO admin (Name, Passkey) VALUES ('admin', 'your_passkey_here');
```

**6. Start XAMPP and open:**
```
http://localhost/website/
```

---

## 🧪 Test Accounts

After setting up you can register directly from the site as:
- **Customer** → go to Register → select Customer
- **Courier** → go to Register → select Courier
- **Admin** → go to `http://localhost/website/index.php?action=showAdminLogin`

---

## 🛠️ Tech Stack

| Technology | Usage |
|---|---|
| PHP 8 | Backend logic |
| PDO | Database access |
| MySQL / MariaDB | Database |
| HTML5 / CSS3 | Frontend |
| XAMPP | Local development server |
| Sessions | Authentication & state management |

---
