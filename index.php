<?php
// Front controller: routes requests to their page controllers.
$page = $_GET['page'] ?? 'home';
$connect_database = !in_array($page, ['home', 'pet_reviews', 'logout'], true);
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/helpers/helpers.php';
require_once __DIR__ . '/controllers/home_controller.php';
require_once __DIR__ . '/controllers/auth_controller.php';
require_once __DIR__ . '/controllers/public_controller.php';

switch ($page) {
    case 'home':
        home_controller();
        break;

    case 'login':
        login_controller($conn);
        break;

    case 'register':
        register_controller($conn);
        break;

    case 'logout':
        logout_controller();
        break;

    case 'specialist_doctors':
        specialist_doctors_controller($conn);
        break;

    case 'doctor_profile':
        doctor_profile_controller($conn);
        break;

    case 'pet_reviews':
        pet_reviews_controller();
        break;

    case 'ajax':
        require_once __DIR__ . '/controllers/ajax_controller.php';
        ajax_controller($conn);
        break;

    case 'appointment_token':
        appointment_token_controller($conn);
        break;

    case 'book_appointment':
        book_appointment_controller($conn);
        break;

    case 'confirm_appointment':
        confirm_appointment_controller($conn);
        break;

    case 'customer/billing':
        require_once __DIR__ . '/controllers/customer_billing_controller.php';
        customer_billing_controller($conn);
        break;

    case 'customer/change_password':
        require_once __DIR__ . '/controllers/customer_change_password_controller.php';
        customer_change_password_controller($conn);
        break;

    case 'customer/dashboard':
        require_once __DIR__ . '/controllers/customer_dashboard_controller.php';
        customer_dashboard_controller($conn);
        break;

    case 'customer/edit_profile':
        require_once __DIR__ . '/controllers/customer_edit_profile_controller.php';
        customer_edit_profile_controller($conn);
        break;

    case 'customer/orders':
        require_once __DIR__ . '/controllers/customer_orders_controller.php';
        customer_orders_controller($conn);
        break;

    case 'customer/order_details':
        require_once __DIR__ . '/controllers/customer_order_details_controller.php';
        customer_order_details_controller($conn);
        break;

    case 'customer/order_success':
        require_once __DIR__ . '/controllers/customer_order_success_controller.php';
        customer_order_success_controller($conn);
        break;

    case 'customer/profile':
        require_once __DIR__ . '/controllers/customer_profile_controller.php';
        customer_profile_controller($conn);
        break;

    case 'admin/customer_reviews':
        require_once __DIR__ . '/controllers/admin_customer_reviews_controller.php';
        admin_customer_reviews_controller($conn);
        break;

    case 'admin/dashboard':
        require_once __DIR__ . '/controllers/admin_dashboard_controller.php';
        admin_dashboard_controller($conn);
        break;

    case 'admin/dashboard_overview':
        require_once __DIR__ . '/controllers/admin_dashboard_overview_controller.php';
        admin_dashboard_overview_controller($conn);
        break;

    case 'admin/delivery_tracking':
        require_once __DIR__ . '/controllers/admin_delivery_tracking_controller.php';
        admin_delivery_tracking_controller($conn);
        break;

    case 'admin/inventory':
        require_once __DIR__ . '/controllers/admin_inventory_controller.php';
        admin_inventory_controller($conn);
        break;

    case 'admin/manage_inventory':
        require_once __DIR__ . '/controllers/admin_manage_inventory_controller.php';
        admin_manage_inventory_controller($conn);
        break;

    case 'admin/sales_analytics':
        require_once __DIR__ . '/controllers/admin_sales_analytics_controller.php';
        admin_sales_analytics_controller($conn);
        break;

    case 'doctor/appointments':
        require_once __DIR__ . '/controllers/doctor_appointments_controller.php';
        doctor_appointments_controller($conn);
        break;

    case 'doctor/dashboard':
        require_once __DIR__ . '/controllers/doctor_dashboard_controller.php';
        doctor_dashboard_controller($conn);
        break;

    case 'doctor/login':
        header("Location: " . route_url("login"));
        exit;

    case 'doctor/medical_records':
        require_once __DIR__ . '/controllers/doctor_medical_records_controller.php';
        doctor_medical_records_controller($conn);
        break;

    case 'delivery/assigned_orders':
        require_once __DIR__ . '/controllers/delivery_assigned_orders_controller.php';
        delivery_assigned_orders_controller($conn);
        break;

    case 'delivery/dashboard':
        require_once __DIR__ . '/controllers/delivery_dashboard_controller.php';
        delivery_dashboard_controller($conn);
        break;

    case 'delivery/history':
        require_once __DIR__ . '/controllers/delivery_history_controller.php';
        delivery_history_controller($conn);
        break;

    case 'delivery/index':
        require_once __DIR__ . '/controllers/delivery_index_controller.php';
        delivery_index_controller($conn);
        break;

    case 'delivery/login':
        header("Location: " . route_url("login"));
        exit;

    case 'delivery/order_details':
        require_once __DIR__ . '/controllers/delivery_order_details_controller.php';
        delivery_order_details_controller($conn);
        break;

    case 'delivery/profile':
        require_once __DIR__ . '/controllers/delivery_profile_controller.php';
        delivery_profile_controller($conn);
        break;

    default:
        http_response_code(404);
        home_controller();
        break;
}
