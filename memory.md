# Tata CLiQ Replica — Project Implementation Plan

## 1. Project Overview & Objectives
- **Topic**: Educational, functional replica of **Tata CLiQ** (`tatacliq.com`).
- **Core Stack**: HTML5 / XHTML, CSS3, JavaScript (Vanilla), PHP (Backend), and **MongoDB** (Database).
- **Primary Pages/Views**:
  1. `index.php` — Homepage (Hero banners, categories, featured deals, responsive navigation).
  2. `catalog.php` — Product Catalogue (Live search, category filter, PHP array sorting).
  3. `product_details.php` — Product Details (Stock alerts, specs, add to cart).
  4. `cart.php` — Cart & Mock Orders (Totals calculation, discount, simulated checkout).
  5. `login.php` & `orders.php` — Auth & Protected User Orders (Sessions & Cookie tracking).
  6. `feedback.php` — Secondary Module: Text-File CRUD (Feedback / Inquiries).
  7. `interaction_demo.php` — Mandatory Syllabus Events (Coordinates, contextual help, overlapping panels).

---

## 2. Mandatory Syllabus Compliance Matrix

| Requirement Area | Syllabus Specification | Planned Implementation |
| :--- | :--- | :--- |
| **HTML/XHTML & CSS** | Structured pages, forms, tables, nested lists, responsive layout. | Tata CLiQ branding, flexbox/grid layout, cart table, nested category list in footer. |
| **JavaScript Validation** | Name, email, password, structured ID with regex; inline error rendering. | Validates Name, Email, Password, and Tata CLiQ Member ID (`CLIQ-[0-9]{4}-[A-Z]{2}`). |
| **Events & DOM** | Mouse/cursor coordinates, contextual help box, hover-to-front overlapping panels, dynamic filtering. | Handled in `events.js` and demonstrated on `interaction_demo.php` + live search in catalog. |
| **PHP Server-Side** | Form processing, server-side validation, associative arrays, selectable sorting. | Server validation on registration/login; product catalog sorted via PHP associative arrays. |
| **PHP File Handling** | Text-file CRUD for a secondary module. | `data/feedback.txt` with full Create, Read, Update, Delete in `feedback.php`. |
| **Cookies & Sessions** | Last-visit cookie, login session, protected pages, logout/session destruction. | Cookie `last_visit` shown in header; session guards `orders.php` and checkout. |
| **Database (MongoDB)** | 3 related collections, sample records (≥10), search, full CRUD. | `users`, `products`, `orders` collections; pre-populated via `seed.php`. |
| **Advanced Integration**| At least 2 advanced functions. | 1. **Dynamic Stock Alerts** (In Stock / Low Stock / Out of Stock).<br>2. **Real-time Cart Math** (Subtotal, GST, coupons, shipping fee). |

---

## 3. Project Folder Structure

```text
web_activity/
│
├── config/
│   ├── db.php                  # MongoDB connection setup & helper functions
│   └── session.php             # Session start, auth verification & "Last-Visit" cookie logic
│
├── assets/
│   ├── css/
│   │   └── style.css           # Tata CLiQ palette (#da1c5c, #000, #f4f4f4) & responsive CSS
│   ├── js/
│   │   ├── validation.js       # Client regex validation (Name, Email, Password, Member ID)
│   │   ├── events.js           # Mouse coords, contextual help tooltip, hover-to-front panels
│   │   └── filter.js           # Dynamic live search & catalog filtering
│   └── images/                 # Local product assets and brand graphics
│
├── includes/
│   ├── header.php              # Global navigation, search bar, login status, cookie banner
│   └── footer.php              # Footer links, nested category hierarchy, copyright
│
├── data/
│   ├── feedback.txt            # Dedicated flat file for PHP File CRUD operations
│   └── seed_data.json          # Starter data for 10+ realistic Tata CLiQ products
│
├── seed.php                    # One-click script to initialize and seed MongoDB collections
│
├── index.php                   # Page 1: Homepage
├── catalog.php                 # Page 2: Product Catalogue & Dynamic Search/Sort
├── product_details.php         # Page 3: Single Product View & Stock Alert Logic
├── cart.php                    # Page 4: Shopping Cart & Simulated Checkout
├── login.php                   # Authentication: Dual client- & server-side validation
├── orders.php                  # Protected View: Session-secured mock orders
├── feedback.php                # Secondary Module: PHP Text-File CRUD (Create/Read/Update/Delete)
├── interaction_demo.php        # Page 5: Syllabus Interaction Demo (Mouse X/Y, Tooltips, Panels)
└── memory.md                   # This project plan and reference documentation
```

---

## 4. Implementation Steps

1. **Step 1: Database Setup & Data Seeder**
   - Connect via MongoDB URI.
   - Seed `users`, `products`, and `orders` with ≥10 items.
2. **Step 2: Theme, Navigation & Cookies**
   - Build `header.php`, `footer.php`, `style.css`.
   - Implement `last_visit` cookie tracking.
3. **Step 3: Client & Server-Side Authentication**
   - JS regex validation on registration and login.
   - Server-side validation, password hashing (`password_hash`), session control.
4. **Step 4: Product Catalogue, Dynamic Filtering & Sorting**
   - Query MongoDB, filter by category, sort via PHP associative arrays.
   - Instant client-side DOM filtering.
5. **Step 5: Product Details, Stock Alerts & Cart Calculations**
   - Stock badges (Available, Low Stock < 5, Out of Stock).
   - Cart subtotal, tax calculation, discount simulation, mock order creation.
6. **Step 6: Secondary Module — Text-File CRUD**
   - `feedback.txt` CRUD operations (separate from MongoDB).
7. **Step 7: DOM & Event Demonstration**
   - Mouse coordinate tracking (client X/Y, screen X/Y).
   - Contextual help box on hover/focus.
   - Overlapping hover-to-front cards.

---

## 5. Viva Voce & On-Spot Modification Strategy
Every configurable logic area will be clearly tagged in the source code with:
```php
// ========================================================
// ON-SPOT MODIFICATION SPOT: [DESCRIPTION]
// e.g., Regex rule, sorting condition, cart discount, session check
// ========================================================
```
This enables instantaneous adjustments during live evaluation.

---

## 6. Kick-Off Template
```text
Proceed with implementing the Tata CLiQ replica according to our agreed plan:
1. Mongo Connection URL: [PASTE YOUR MONGO URL HERE]
2. Driver Type: [Composer mongodb/mongodb OR Native MongoDB\Driver\Manager]
3. Please create the standard directory structure and start with Step 1 (MongoDB config, seed script with 10+ Tata CLiQ items, and session/cookie handler). Ensure code is clean, well-commented with ON-SPOT MODIFICATION tags, and easy to explain.
```

