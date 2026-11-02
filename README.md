# Tata CLiQ (tatacliq.com) Web Application Replica

An educational, functional replica of **Tata CLiQ** built using **HTML5/XHTML, CSS3, JavaScript, PHP, and MongoDB Atlas**. This project demonstrates responsive visual design, client- and server-side validation, event handling, session & cookie management, text-file CRUD, and real-time MongoDB database operations.

---

## 1. Quick Start / How to Run

### Option A: One-Click Execution (Windows)
Double-click the **`start_server.bat`** file located in the root project folder. It will start the PHP built-in web server at:
```text
http://localhost:8000
```
Open your browser and navigate to `http://localhost:8000`.

### Option B: Command Line (PowerShell / CMD)
```powershell
# From the project root (c:\Users\hp\web_activity)
php -S localhost:8000
```

### Pre-Seeded Default Credentials
- **Email**: `arjun@tatacliq.com`
- **Password**: `Cliq@2026`
- **Tata CLiQ Member ID**: `CLIQ-1001-IN`

*(To re-seed or reset sample products, users, and orders, visit `http://localhost:8000/seed.php`)*

---

## 2. Directory Structure

```text
web_activity/
│
├── config/
│   ├── db.php                  # MongoDB Atlas connection & helper queries (BSON/Driver)
│   └── session.php             # Session handler, auth checks & Last-Visit cookie logic
│
├── assets/
│   ├── css/
│   │   └── style.css           # Tata CLiQ branding (magenta #da1c5c, dark header, responsive)
│   ├── js/
│   │   ├── validation.js       # Client Regex validation (Name, Email, Password, CLIQ ID)
│   │   ├── events.js           # Mouse coords (client/screen), contextual help box, keyboard '/'
│   │   └── filter.js           # Real-time instant product filtering without page reload
│   └── images/                 # Local assets and brand logos
│
├── includes/
│   ├── header.php              # Navbar, search, last-visit banner, cart counter badge
│   └── footer.php              # Nested category lists (Syllabus), footer navigation
│
├── data/
│   ├── feedback.txt            # Flat text file for secondary PHP File CRUD module
│   └── tatacliq_mongodb_export.json # Exported MongoDB collections (Users, Products, Orders)
│
├── seed.php                    # One-click script to seed MongoDB with >=12 products, user, order
├── export_db.php               # Exports live MongoDB data to data/tatacliq_mongodb_export.json
├── index.php                   # Page 1: Homepage (hero banners, categories, featured deals)
├── catalog.php                 # Page 2: Product Catalogue (PHP associative array sorting, search)
├── product_details.php         # Page 3: Product Details (Dynamic stock alert, specifications)
├── cart.php                    # Page 4: Cart & Simulated Checkout (totals math, discounts, CRUD)
├── login.php                   # Authentication: Sign in & Registration with Dual Validation
├── orders.php                  # Protected Page: Authenticated user mock orders & cancellation
├── feedback.php                # Secondary Module: PHP Text-File CRUD (Create, Read, Update, Delete)
├── interaction_demo.php        # Page 5: Mandatory DOM Events (Coordinates, Tooltips, Panels)
├── start_server.bat            # One-click Windows server launcher
└── memory.md                   # Project planning memory log
```

---

## 3. Database Schema & Data Models (MongoDB)

### Collection 1: `users`
| Field | Type | Description |
| :--- | :--- | :--- |
| `_id` | ObjectId | MongoDB unique document identifier (Primary Key) |
| `name` | String | User full name |
| `email` | String | Unique email address |
| `password_hash` | String | Bcrypt hash generated via `password_hash()` |
| `cliq_id` | String | Structured Identifier matching `^CLIQ-[0-9]{4}-[A-Z]{2}$` |
| `created_at` | DateTime | Timestamp of account registration |

### Collection 2: `products`
| Field | Type | Description |
| :--- | :--- | :--- |
| `_id` | ObjectId | Unique product identifier (Primary Key) |
| `title` | String | Product name |
| `brand` | String | Brand name (Titan, Sony, Apple, Peter England, Puma, etc.) |
| `category` | String | Men Fashion, Women Fashion, Watches, Footwear, Electronics, Luxury |
| `price` | Number | Discounted selling price (INR) |
| `mrp` | Number | Maximum Retail Price (INR) |
| `discount_percent`| Number | Computed or assigned discount percentage |
| `stock` | Number | Warehouse inventory (triggers In Stock / Low Stock / Out of Stock) |
| `rating` | Number | Buyer average rating (e.g., 4.7) |
| `reviews_count` | Number | Total buyer ratings count |
| `image` | String | Product preview image URL |
| `description` | String | Detailed overview |
| `features` | Array | Array of feature bullet points |

### Collection 3: `orders`
| Field | Type | Description |
| :--- | :--- | :--- |
| `_id` | ObjectId | Unique order identifier (Primary Key) |
| `order_id` | String | User-facing order code (e.g., `TC-ORD-165687`) |
| `user_id` | String | User reference |
| `user_name` | String | Customer name |
| `user_email` | String | Customer email |
| `cliq_id` | String | Customer structured identifier |
| `items` | Array | Array of objects `[{product_id, title, price, qty, image}]` |
| `subtotal` | Number | Total before discounts |
| `discount` | Number | Promo coupon discount amount |
| `delivery_fee` | Number | Standard delivery charges |
| `total_amount` | Number | Final bill amount |
| `payment_method`| String | Simulated payment mode (Tata Pay UPI, Card, NetBanking, COD) |
| `status` | String | `Confirmed` or `Cancelled` |
| `created_at` | DateTime | Order placement timestamp |

---

## 4. Mandatory Syllabus Coverage Verification

| Area | Syllabus Requirement | Where & How Implemented |
| :--- | :--- | :--- |
| **HTML/XHTML & CSS** | Structured pages, forms, tables, nested lists, responsive layouts, consistent navigation. | **Pages**: 7 interconnected views with consistent Tata CLiQ navbar & footer.<br>**Tables**: Cart & Orders summary tables (`cart.php`, `orders.php`).<br>**Nested Lists**: Category tree in `includes/footer.php`.<br>**Responsive**: Media queries in `assets/css/style.css`. |
| **JavaScript Validation** | Validate name, email, password, structured ID with regex; show errors. | **`assets/js/validation.js`**:<br>&bull; Name: `/^[A-Za-z\s]{3,40}$/`<br>&bull; Email: RFC regex<br>&bull; Password: Strong regex with upper, lower, digit, symbol<br>&bull; Identifier: `/^CLIQ-[0-9]{4}-[A-Z]{2}$/`<br>Inline real-time feedback. |
| **Events & DOM** | Mouse/keyboard interactions, cursor coordinates, contextual help box, overlapping panels, dynamic filtering. | **`assets/js/events.js` & `interaction_demo.php`**:<br>&bull; `clientX/Y` and `screenX/Y` coordinate tracker<br>&bull; Floating contextual tooltip positioned at cursor/element<br>&bull; Hover-to-front overlapping cards with `z-index` and 3D elevation<br>&bull; Keyboard shortcut `/` to focus search bar<br>&bull; Dynamic instant filter in `assets/js/filter.js`. |
| **PHP Server-Side** | Form processing and server-side validation; associative arrays and selectable sorting. | **`catalog.php`**: `usort()` on associative arrays for Price Low-High, Price High-Low, Rating, and Discount.<br>**`login.php`**: Server validation & sanitization. |
| **PHP File Handling** | Text-file CRUD for secondary module (notices/feedback/logs). | **`feedback.php` & `data/feedback.txt`**: Dedicated secondary module performing Create (`fopen`/`fwrite`), Read (`file`), Update (`write_all`), and Delete separately from MongoDB. |
| **Cookies & Sessions** | Last-visit cookie, login session, protected pages, logout and session destruction. | **`config/session.php`**: `tatacliq_last_visit` cookie displayed in top banner.<br>Protected route in `orders.php` (`require_login`).<br>Clean session destroy in `login.php?action=logout`. |
| **Database Operations** | 3 related tables/collections, search and full CRUD. | **MongoDB Atlas**: Collections `users`, `products`, `orders`.<br>&bull; **Create**: Place order, register user, add feedback.<br>&bull; **Read**: Product catalog, search query, order history.<br>&bull; **Update**: Update cart quantities, cancel orders.<br>&bull; **Delete**: Remove cart items, cancel order status. |
| **Advanced Integration**| At least 2 advanced functions (stock alerts, cart totals). | 1. **Dynamic Stock Alerts**: In Stock, Low Stock warning (`stock <= 3`), and Out of Stock disable.<br>2. **Real-time Cart Calculations**: Subtotal, 10% coupon promo (`CLIQ2026`), delivery fee thresholds, grand total. |

---

## 5. On-Spot Modifications Cheat Sheet

Every key customizable logic section has been highlighted in the code with clear banner comments:

1. **Change MongoDB URL or Database Name**:
   - File: `config/db.php` &rarr; Lines 14–15 (`$MONGO_URI`, `$DB_NAME`).
2. **Change Last-Visit Cookie Expiry or Format**:
   - File: `config/session.php` &rarr; Lines 18–19 (`$COOKIE_EXPIRY`, `date(...)`).
3. **Change Brand Colors or Styling**:
   - File: `assets/css/style.css` &rarr; Lines 35–45 (`:root { --cliq-primary: #da1c5c; ... }`).
4. **Modify Regular Expression Validation Rules**:
   - File: `assets/js/validation.js` &rarr; Lines 14–33 (`VALIDATION_RULES`).
5. **Change Mouse / Keyboard Event Behaviors**:
   - File: `assets/js/events.js` &rarr; Lines 18–45.
6. **Change Sorting Conditions or Add New Sort Field**:
   - File: `catalog.php` &rarr; Lines 40–70 (`switch ($sort_by)`).
7. **Adjust Stock Alert Threshold**:
   - File: `product_details.php` &rarr; Lines 75–95 (`$product['stock'] <= 3`).
8. **Modify Promo Discount / Delivery Fee Rules**:
   - File: `cart.php` &rarr; Lines 170–190 (`coupon 'CLIQ2026'`, `delivery_fee`).
9. **Change File CRUD Storage or Delimiter**:
   - File: `feedback.php` &rarr; Lines 18–20 (`$FILE_PATH`, `$DELIMITER = '|'`).
