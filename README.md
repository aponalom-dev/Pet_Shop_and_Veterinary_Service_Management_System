# PawCare – Pet Shop and Veterinary Service Management System

PawCare is a PHP/MySQL web application for managing a pet shop and related veterinary services. Customers can browse pets and products, maintain a cart, place orders, view order history, and book veterinary appointments. Administrators manage inventory, sales information, delivery tracking, and customer reviews. Doctors manage appointments and medical records, while delivery users process assigned orders.

The project is implemented as a server-rendered PHP application using HTML5, CSS3, JavaScript, MySQL/MariaDB, and PHP sessions. It is a role-based monolithic web application designed to run locally through XAMPP.

## 1. Installation and Setup

### Prerequisites

- XAMPP with Apache, PHP, MySQL/MariaDB, and phpMyAdmin
- A browser
- PHP 8.x and MariaDB/MySQL compatible with the SQL files

### Installation

1. Copy the project directory into the XAMPP web root, normally `C:\xampp\htdocs\`.
2. Start **Apache** and **MySQL** from the XAMPP Control Panel.
3. Open phpMyAdmin at `http://localhost/phpmyadmin`.
4. Import `database/pawcare_db.sql`. This is the repository's complete dump and is the recommended setup.
5. Alternatively, import `database/schema.sql` first and then `database/sample_data.sql`.
6. Confirm that the database name is `pawcare_db`.
7. Open the application at:

   ```text
   http://localhost/WT_Summer_2025-26_G_08_Paw_Care-_Pet_Shop_and_Veterinary_Service_Management_System/
   ```

The connection settings are hard-coded in `config/database.php`:

```php
$host = "localhost";
$username = "root";
$password = "";
$database = "pawcare_db";
```

If the local MySQL username, password, host, or database name differs, update that file before starting the application. `test_connection.php` can be used to check the configured connection.

### Database setup files

| File | Purpose |
|---|---|
| `database/pawcare_db.sql` | Complete phpMyAdmin dump containing schema and sample records |
| `database/schema.sql` | Database and table definitions |
| `database/sample_data.sql` | Sample records for the schema in `schema.sql` |
| `database/pawcare_schema_drop_and_create.sql` | Alternative generated schema script |
| `database/pawcare_sample_data.sql` | Alternative generated sample-data script |
| `database/database_guide.md` | Repository setup notes |

## 2. Folder Structure

```text
WT_Summer_2025-26_G_08_Paw_Care-_Pet_Shop_and_Veterinary_Service_Management_System/
├── index.php                  # Public landing page
├── login.php                  # Shared login for application roles
├── register.php               # Customer registration
├── logout.php                 # Session logout
├── specialist_doctors.php     # Public doctor directory
├── doctor_profile.php         # Public doctor profile and reviews
├── book_appointment.php       # Appointment form
├── confirm_appointment.php    # Appointment confirmation and insertion
├── appointment_token.php      # Appointment information page
├── pet_reviews.php            # Public review-area page
├── config/
│   └── database.php           # MySQLi connection
├── includes/
│   └── session.php            # Session initialization
├── admin/                     # Administrator dashboard and management pages
├── customer/                  # Customer shopping, profile, and order pages
├── doctor/                    # Doctor dashboard, appointments, and records
├── delivery/                  # Delivery portal and order-status pages
├── database/                  # SQL schema, dump, sample data, and guide
├── assets/
│   ├── css/                   # Page-specific and shared stylesheets
│   ├── js/                    # Client-side scripts
│   └── images/                # Logos, wallpapers, and public images
├── uploads/
│   ├── pets/                  # Pet images
│   ├── products/              # Product images
│   └── profiles/              # User/doctor profile images
└── Project_Report/            # Proposal, ER diagram, and use-case diagram
```

## 3. Architecture and Application Flow

PawCare uses a server-rendered, role-based PHP architecture. PHP pages receive browser requests, use the shared MySQLi connection, perform validation and database operations, and render HTML with CSS and JavaScript assets.

```text
Browser
   ↓
PHP page / form submission
   ↓
Session and role checks
   ↓
Input validation and MySQLi queries
   ↓
MySQL database
   ↓
Rendered HTML response and client-side JavaScript
```

The shared authentication entry point is `login.php`. Successful authentication stores `user_id`, `full_name`, `username`, `email`, and `role` in the PHP session, then redirects by role:

| Role | Dashboard |
|---|---|
| `admin` | `admin/dashboard.php` |
| `customer` | `customer/dashboard.php` |
| `doctor` | `doctor/dashboard.php` |
| `delivery` | `delivery/dashboard.php` |

## 4. Main Request Flows

### Customer shopping and ordering

```text
customer/dashboard.php
    ↓
Add or remove pet/product in carts
    ↓
customer/billing.php
    ↓
Create orders and order_items
    ↓
customer/order_success.php
```

Cart and order queries use the logged-in customer ID, preventing a customer from reading or modifying another customer's cart or order through normal page requests.

### Veterinary appointment

```text
specialist_doctors.php
    ↓
doctor_profile.php?doctor_id=...
    ↓
book_appointment.php?doctor_id=...
    ↓
confirm_appointment.php
    ↓
appointments table
    ↓
appointment_token.php?appointment_id=...
```

Customers are redirected to login when required. The selected appointment is temporarily held in the session before confirmation.

### Doctor appointment and medical-record workflow

```text
doctor/appointments.php
    ↓
Doctor confirms, completes, or cancels an appointment
    ↓
doctor/medical_records.php
    ↓
medical_records table
```

### Delivery workflow

```text
delivery/assigned_orders.php
    ↓
Start delivery
    ↓
delivery status = Out for Delivery
    ↓
Mark delivered
    ↓
delivery status = Delivered
and order status = Delivered
```

The delivery pages currently query a `delivery_agents` table. That table is referenced by PHP but is not defined in the SQL files included in this repository; delivery setup therefore requires reconciling the schema before that portal can be used reliably.

## 5. Roles and Features

| Role | Main responsibility | Implemented features |
|---|---|---|
| Customer | Shop for pets/products and use veterinary services | Registration, login, product and pet browsing, cart management, billing, orders, order details/receipts, profile editing, password change, doctor browsing, appointment booking, appointment token |
| Administrator | Operate the shop | Dashboard, inventory overview, inventory management, sales analytics, delivery tracking, customer-review management |
| Doctor | Manage veterinary work | Doctor login/dashboard, appointment filtering and status changes, medical-record viewing, doctor profile |
| Delivery | Process assigned deliveries | Delivery login, assigned-order view, delivery history, profile, start-delivery and mark-delivered actions |

The public area also contains the home page, doctor directory, public doctor profiles, and the review-area page.

## 6. Academic Requirement Mapping

| Requirement | Implementation location |
|---|---|
| Authentication | `login.php`, `register.php`, `logout.php`, `includes/session.php` |
| Role-based authorization | Role guards at the top of `admin/*.php`, `customer/*.php`, `doctor/*.php`, and `delivery/*.php` |
| Database connectivity | `config/database.php` |
| Database schema and relationships | `database/schema.sql`, `database/pawcare_db.sql` |
| Pet and product catalogue | `customer/dashboard.php`, `admin/inventory.php`, `admin/manage_inventory.php` |
| Cart and checkout | `customer/dashboard.php`, `customer/billing.php` |
| Order history and details | `customer/orders.php`, `customer/order_details.php`, `customer/order_success.php` |
| Veterinary appointment booking | `specialist_doctors.php`, `doctor_profile.php`, `book_appointment.php`, `confirm_appointment.php` |
| Doctor administration of appointments | `doctor/appointments.php` |
| Medical records | `doctor/medical_records.php` |
| Delivery processing | `delivery/assigned_orders.php`, `delivery/history.php`, `admin/delivery_tracking.php` |
| Sales and reviews | `admin/sales_analytics.php`, `admin/customer_reviews.php` |
| Presentation layer | `assets/css/`, PHP templates, and `assets/js/` |
| Project diagrams and proposal | `Project_Report/` |

## 7. Security and Validation

The following mechanisms are visible in the inspected implementation:

| Concern | Implemented defence |
|---|---|
| Password storage | Registration uses `password_hash(..., PASSWORD_DEFAULT)`; login and password changes use `password_verify()` |
| SQL injection reduction | Most user-controlled database operations use MySQLi prepared statements and bound parameters |
| Authentication | Login checks account status and verifies the password before creating the authenticated session |
| Session fixation reduction | `session_regenerate_id(true)` is called after successful login |
| Authorization | Role-specific pages check `$_SESSION["user_id"]` and the expected role before rendering or updating data |
| Ownership checks | Customer order/cart queries and doctor appointment updates include the authenticated user's ID |
| Input validation | Registration validates required fields, email, username format/length, phone format, and password length; appointment and status inputs are also checked |
| Output escaping | Dynamic HTML output commonly uses `htmlspecialchars()`; multiline medical text is escaped before `nl2br()` |
| Remember-login cookie | The login identifier is optionally stored in a `remember_login` cookie; the password is not stored in that cookie |
| Transactional delivery updates | Delivery status and related order status are updated inside a MySQL transaction |

No CSRF-token system, rate limiter, or environment-variable secret management was identified in the inspected source. The database credentials are currently stored directly in `config/database.php`; do not expose that file or use production credentials in this repository.

## 8. Configuration

The application has no `.env` file or package/dependency manifest. Configure the following values in `config/database.php`:

| Setting | Current value | Purpose |
|---|---|---|
| `$host` | `localhost` | MySQL/MariaDB host |
| `$username` | `root` | Database user |
| `$password` | empty string | Local database password |
| `$database` | `pawcare_db` | Application database |

Uploaded and static media are expected under `uploads/` and `assets/images/`. The PHP pages use relative paths, so the project should be served from its directory under the web root.

## 9. Database

The database is named `pawcare_db` and uses MySQL/MariaDB tables with foreign keys and enumerated status fields.

| Table | Purpose |
|---|---|
| `users` | Login identities, roles, profile data, and account status |
| `doctors` | Doctor specialization, qualification, availability, and fee |
| `pet_categories` / `pets` | Pet catalogue and stock |
| `product_categories` / `products` | Shop-product catalogue and stock |
| `carts` | Customer cart items |
| `orders` / `order_items` | Customer orders and their line items |
| `appointments` | Customer-to-doctor appointment bookings |
| `medical_records` | Diagnosis and treatment information for appointments |
| `deliveries` | Order delivery assignment and status |
| `reviews` | Customer ratings and comments for pets/products |

Important relationships include:

- `doctors.user_id` references `users.user_id`.
- `pets.category_id` and `products.category_id` reference their category tables.
- `carts.customer_id` and `orders.customer_id` reference `users.user_id`.
- `order_items.order_id` references `orders.order_id`.
- `appointments` references both the customer and doctor.
- `medical_records.appointment_id` references `appointments.appointment_id`.
- `deliveries.order_id` references `orders.order_id`.
- `reviews.customer_id` references `users.user_id`.

`schema.sql` defines the tables above. The delivery PHP code additionally joins `delivery_agents`, but that table is absent from the supplied schema scripts and should be added or the code updated as part of delivery-portal maintenance.

## 10. Sample and Test Accounts

The sample SQL includes the following usernames and email addresses. The SQL stores password hashes rather than plaintext passwords, and the repository does not document the corresponding plaintext passwords; use newly registered customer accounts or establish test passwords separately in a local database.

| Role | Username | Email |
|---|---|---|
| Admin | `pawcare_admin` | `admin@pawcare.com` |
| Customer | `rahim_ahmed` | `customer1@pawcare.com` |
| Customer | `sadia_islam` | `customer2@pawcare.com` |
| Doctor | `dr_hasan` | `doctor1@pawcare.com` |
| Doctor | `dr_nusrat` | `doctor2@pawcare.com` |
| Delivery | `karim_delivery` | `delivery1@pawcare.com` |
| Delivery | `rafi_delivery` | `delivery2@pawcare.com` |

Public customer registration creates accounts with the `customer` role. Administrative, doctor, and delivery accounts are represented by sample database records rather than a public role-selection form.

## 11. Important Settings and Customization

- Modify catalogue records and stock through the administrator inventory pages or the `pets` and `products` tables.
- Modify doctor availability, specialization, fee, and biography in the `doctors` table.
- Keep uploaded profile, pet, and product filenames consistent with the corresponding `uploads/` subdirectories.
- Use `database/pawcare_db.sql` for a reproducible local sample installation.
- For a clean database reset, use the schema script carefully because it drops and recreates `pawcare_db`.

## 12. Additional Notes

- This repository contains PHP source, static assets, uploaded sample media, SQL files, and academic project diagrams.
- There is no Composer configuration, Node package manifest, automated test suite, or separate frontend/backend server identified in the project.
- The delivery portal has a schema dependency mismatch (`delivery_agents`) that should be resolved before presenting delivery features as fully deployable.
- `Project_Report/README.md.txt` is an additional project document; the root `README.md` is the primary setup and technical guide.

## Copyright and Project Attribution

The existing project documentation identifies the following project team:

- Mahabub Alom Apon (Group Leader)
- Sumiya Rahman Hadir (Member)
- Syed Shahriar Mustafa (Member)
- Tahomina Era (Member)

No separate software license or copyright notice was identified in the inspected repository.
