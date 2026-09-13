# PawCare – Pet Shop and Veterinary Service Management System

PawCare is a PHP/MySQL web application for managing a pet shop and related veterinary services. Customers can browse pets and products, maintain a cart, place orders, view order history, and book veterinary appointments. Administrators manage inventory, sales information, delivery tracking, and customer reviews. Doctors manage appointments and medical records, while delivery users process assigned orders.

The project is implemented as a server-rendered PHP application using HTML5, CSS3, JavaScript, MySQL, and PHP sessions. It is a role-based monolithic web application designed to run locally through XAMPP.

## 1. Installation and Setup

### Prerequisites

- XAMPP with Apache, PHP, MySQL, and phpMyAdmin
- A browser
- PHP 8.x and MySQL compatible with the SQL files

### Installation

1. Copy the project directory into the XAMPP web root, normally `C:\xampp\htdocs\`.
2. Start **Apache** and **MySQL** from the XAMPP Control Panel.
3. Open phpMyAdmin at `http://localhost/phpmyadmin`.
4. Create an empty database named `pawcare_db`, select it, then import `database.sql`. This is the complete dump and is the recommended setup.
5. Alternatively, import `docs/database/schema.sql` first and then `docs/database/sample_data.sql`.
6. Confirm that the database name is `pawcare_db`.
7. Open the application at:

   ```text
   http://localhost/Pet_Shop_and_Veterinary_Service_Management_System/index.php
   ```

The connection settings are in `config/config.php`:

```php
$host = "localhost";
$username = "root";
$password = "";
$database = "pawcare_db";
```

If the local MySQL username, password, host, or database name differs, update that file before starting the application.

### Database setup files

| File | Purpose |
|---|---|
| `database.sql` | Complete phpMyAdmin dump containing schema and sample records |
| `docs/database/schema.sql` | Database and table definitions |
| `docs/database/sample_data.sql` | Sample records for the schema in `schema.sql` |
| `docs/database/pawcare_schema_drop_and_create.sql` | Alternative generated schema script |
| `docs/database/pawcare_sample_data.sql` | Alternative generated sample-data script |
| `docs/database/database_guide.md` | Repository setup notes |

## 2. Folder Structure

```text
Pet_Shop_and_Veterinary_Service_Management_System/
├── index.php                  # Only application entry point and route switch
├── database.sql               # Complete database dump
├── README.md
├── config/
│   └── config.php             # Session and MySQLi configuration
├── helpers/
│   └── helpers.php            # Route and asset-base URL helpers
├── models/                    # SQL queries and data operations only
│   ├── user_model.php
│   ├── doctor_model.php
│   ├── doctor_work_model.php
│   ├── appointment_model.php
│   ├── catalog_model.php
│   ├── cart_model.php
│   ├── checkout_model.php
│   ├── order_model.php
│   ├── admin_model.php
│   └── delivery_model.php
├── controllers/
│   ├── home_controller.php
│   ├── auth_controller.php
│   ├── public_controller.php
│   ├── admin_*_controller.php
│   ├── customer_*_controller.php
│   ├── doctor_*_controller.php
│   └── delivery_*_controller.php
├── views/                     # HTML/PHP presentation without database queries
│   ├── partials/meta.php
│   ├── home/index.php
│   ├── auth/
│   ├── public/
│   ├── admin/
│   ├── customer/
│   ├── doctor/
│   └── delivery/
├── assets/
│   ├── css/                   # Page-specific and shared stylesheets
│   ├── js/                    # Client-side scripts
│   ├── images/                # Logos, wallpapers, and public images
│   └── uploads/               # Pet, product, and profile images
└── docs/
    ├── database/              # Alternative SQL scripts and guide
    └── Project_Report/        # Report, ER, and use-case diagrams
```

## 3. Architecture and Application Flow

PawCare follows a plain-PHP MVC structure. Every application request enters `index.php?page=...`; the router calls a controller. Controllers check roles, validate input, call model functions, and load views. All SQL and MySQLi calls are in `models/`; views render markup and do not query the database.

AJAX follows the model project's `index.php?page=ajax&action=...` pattern. `controllers/ajax_controller.php` validates the action and role, calls model functions, and returns JSON through `json_out()` in `helpers/helpers.php`. The registration page checks username availability; the admin inventory page searches pets/products without reloading; the admin overview refreshes statistics every 15 seconds; and delivery users search their assigned orders without reloading. Private JSON actions check the corresponding role and record ownership.

```text
Browser → index.php?page=... → controller → model → MySQL
                                     └────────────→ view → HTML response
```

The shared authentication route is `index.php?page=login`. Successful authentication stores `user_id`, `full_name`, `username`, `email`, and `role` in the PHP session, then redirects by role:

The home page has one **Sign In — All Roles** entry for admins, customers, doctors, and delivery agents. The old `doctor/login` and `delivery/login` URLs redirect to this shared form; role-specific dashboard pages also send guests there. The login form uses a CSRF token, and the server checks password, account status, and role before redirecting.

| Role | Dashboard |
|---|---|
| `admin` | `index.php?page=admin/dashboard` |
| `customer` | `index.php?page=customer/dashboard` |
| `doctor` | `index.php?page=doctor/dashboard` |
| `delivery` | `index.php?page=delivery/dashboard` |

## 4. Main Request Flows

### Customer shopping and ordering

```text
index.php?page=customer/dashboard
    ↓
Add or remove pet/product in carts
    ↓
index.php?page=customer/billing
    ↓
Create orders and order_items
    ↓
index.php?page=customer/order_success
```

Cart and order queries use the logged-in customer ID, preventing a customer from reading or modifying another customer's cart or order through normal page requests.

### Veterinary appointment

```text
index.php?page=specialist_doctors
    ↓
index.php?page=doctor_profile&doctor_id=...
    ↓
index.php?page=book_appointment&doctor_id=...
    ↓
index.php?page=confirm_appointment
    ↓
appointments table
    ↓
index.php?page=appointment_token&appointment_id=...
```

Customers are redirected to login when required. The selected appointment is temporarily held in the session before confirmation.

### Doctor appointment and medical-record workflow

```text
index.php?page=doctor/appointments
    ↓
Doctor confirms, completes, or cancels an appointment
    ↓
index.php?page=doctor/medical_records
    ↓
medical_records table
```

### Delivery workflow

```text
index.php?page=delivery/assigned_orders
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

The delivery pages use the `delivery_agents` table supplied by the database scripts. The sample data assigns two delivery users to Pathao Fast and Jhinku BD.

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
| Authentication | `controllers/auth_controller.php`, `models/user_model.php`, `views/auth/` |
| Role-based authorization | Role guards in the controllers for `admin`, `customer`, `doctor`, and `delivery` routes |
| Database connectivity | `config/config.php` |
| Database schema and relationships | `database.sql`, `docs/database/schema.sql` |
| Pet and product catalogue | `controllers/customer_dashboard_controller.php`, `controllers/admin_inventory_controller.php`, `models/catalog_model.php` |
| Cart and checkout | `controllers/customer_dashboard_controller.php`, `controllers/customer_billing_controller.php`, `models/cart_model.php`, `models/checkout_model.php` |
| Order history and details | `controllers/customer_orders_controller.php`, `controllers/customer_order_details_controller.php`, `models/order_model.php` |
| Veterinary appointment booking | `controllers/public_controller.php`, `models/appointment_model.php` |
| Doctor administration of appointments | `controllers/doctor_appointments_controller.php`, `models/doctor_work_model.php` |
| Medical records | `controllers/doctor_medical_records_controller.php`, `models/doctor_work_model.php` |
| Delivery processing | `controllers/delivery_assigned_orders_controller.php`, `models/delivery_model.php` |
| Sales and reviews | `controllers/admin_sales_analytics_controller.php`, `controllers/admin_customer_reviews_controller.php`, `models/admin_model.php` |
| Presentation layer | `views/`, `assets/css/`, and `assets/js/` |
| AJAX and JSON | `controllers/ajax_controller.php`, `helpers/helpers.php`, `assets/js/ajax.js`, `assets/js/register.js`, `assets/js/admin-inventory.js`, `assets/js/admin-dashboard.js`, `assets/js/delivery-orders.js` |
| Project diagrams and proposal | `docs/Project_Report/` |

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

No CSRF-token system, rate limiter, or environment-variable secret management was identified in the inspected source. The database credentials are currently stored directly in `config/config.php`; do not expose that file or use production credentials in this repository.

## 8. Configuration

The application has no `.env` file or package/dependency manifest. Configure the following values in `config/config.php`:

| Setting | Current value | Purpose |
|---|---|---|
| `$host` | `localhost` | MySQL host |
| `$username` | `root` | Database user |
| `$password` | empty string | Local database password |
| `$database` | `pawcare_db` | Application database |

Uploaded and static media are expected under `assets/uploads/` and `assets/images/`. The views use a base URL for relative asset paths, so the project should be served from its directory under the web root.

## 9. Database

The database is named `pawcare_db` and uses MySQL tables with foreign keys and enumerated status fields.

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
| `delivery_agents` | Delivery-user company assignments |
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

`docs/database/schema.sql` defines the tables above, including `delivery_agents` for delivery-company assignments.

## 10. Sample and Test Accounts

The sample SQL includes the following usernames and email addresses. It stores password hashes rather than plaintext passwords. The local admin sample password is noted below; passwords for the other sample accounts are not documented, so register new test accounts if needed.

| Role | Username | Email |
|---|---|---|
| Admin | `admin` | `admin@pawcare.com` |
| Customer | `rahim_ahmed` | `customer1@pawcare.com` |
| Customer | `sadia_islam` | `customer2@pawcare.com` |
| Doctor | `dr_hasan` | `doctor1@pawcare.com` |
| Doctor | `dr_nusrat` | `doctor2@pawcare.com` |
| Delivery | `karim_delivery` | `delivery1@pawcare.com` |
| Delivery | `rafi_delivery` | `delivery2@pawcare.com` |

Public registration offers customer, doctor, and delivery roles. Doctor registration also creates a doctor profile; delivery registration creates a delivery-agent record. The admin role cannot be selected on the public form. Signed-in admins use **Manage Accounts** to create any of the four account types and to search, filter, edit, suspend/reactivate, or delete accounts. Editing can set a new password while leaving it blank keeps the old one; this also lets a local admin set passwords for sample doctor accounts. Both account forms check required fields and duplicate identities on the server, and changes require a CSRF token. Account deletion or a role change can be refused when appointments or orders depend on the account.

For the local `database.sql` sample, the admin login is `admin` / `admin`. Change this sample password before deploying the site outside your local XAMPP environment.

## 11. Important Settings and Customization

- Modify catalogue records and stock through the administrator inventory pages or the `pets` and `products` tables.
- Modify doctor availability, specialization, fee, and biography in the `doctors` table.
- Doctor portraits are stored in `assets/uploads/profiles/`. The sample dump assigns `Apon.jpg` to Dr. Hasan, `Era.jpg` to Dr. Nusrat, and `Hadir.jpg.jpg` to Dr. Sumiya Rahman Hadir; the existing Syed Shahriar Mustafa doctor account uses `Mostofa.jpg` in the running database. Sumiya's qualification, fee, and schedule are placeholders, so her profile is marked unavailable until they are confirmed. On an existing database, run `docs/database/doctor_photo_assignments.sql` to apply the original portrait assignments without replacing other records. Admins can choose a different existing portrait from **Manage Accounts > Edit**. Missing photos display `default.svg` rather than a broken image.
- Keep uploaded profile, pet, and product filenames consistent with the corresponding `assets/uploads/` subdirectories.
- Use `database.sql` for a reproducible local sample installation.
- For a clean database reset, use the schema script carefully because it drops and recreates `pawcare_db`.

## 12. Additional Notes

- This repository contains PHP source, static assets, uploaded sample media, SQL files, and academic project diagrams.
- There is no Composer configuration, Node package manifest, automated test suite, or separate frontend/backend server identified in the project.
- The delivery portal requires the `delivery_agents` table; use the updated database scripts when setting up a new database.
- `docs/Project_Report/README.md.txt` is an additional project document; the root `README.md` is the primary setup and technical guide.

## Copyright and Project Attribution

The existing project documentation identifies the following project team:

- Mahabub Alom Apon (Group Leader)
- Sumiya Rahman Hadir (Member)
- Syed Shahriar Mustafa (Member)
- Tahomina Era (Member)

No separate software license or copyright notice was identified in the inspected repository.
