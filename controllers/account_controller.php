<?php
require_once __DIR__ . '/../models/user_model.php';

function blank_account_form() {
    return [
        'full_name' => '', 'username' => '', 'email' => '', 'phone' => '',
        'address' => '', 'role' => 'customer', 'password' => '', 'confirm_password' => '',
        'specialization' => '', 'qualification' => '', 'experience_years' => '0',
        'consultation_fee' => '0', 'available_days' => '', 'available_time' => '',
        'bio' => '', 'company_name' => '', 'status' => 'active',
        'profile_image' => 'default.png'
    ];
}

function posted_account_form() {
    $account = blank_account_form();
    foreach ($account as $field => $value) {
        $posted = $_POST[$field] ?? '';
        if (!is_string($posted)) {
            $posted = '';
        }
        if ($field === 'password' || $field === 'confirm_password') {
            $account[$field] = $posted;
        } else {
            $account[$field] = trim($posted);
        }
    }
    return $account;
}

function new_account_error($conn, $account, $allow_admin, $require_confirm, $editing_id = 0) {
    $roles = $allow_admin ? ['admin', 'customer', 'doctor', 'delivery'] : ['customer', 'doctor', 'delivery'];
    if (!in_array($account['role'], $roles, true)) {
        return 'Choose a valid account type.';
    }
    if ($account['full_name'] === '' || $account['username'] === '' || $account['email'] === '' ||
        $account['phone'] === '' || ($editing_id === 0 && $account['password'] === '')) {
        return 'Please fill in all account fields.';
    }
    if (strlen($account['full_name']) < 3 || strlen($account['full_name']) > 100) {
        return 'Full name must be 3-100 characters long.';
    }
    if (!filter_var($account['email'], FILTER_VALIDATE_EMAIL) || strlen($account['email']) > 100) {
        return 'Please enter a valid email address.';
    }
    if (!preg_match('/^01[0-9]{9}$/', $account['phone'])) {
        return 'Please enter a valid 11-digit phone number.';
    }
    if (!preg_match('/^[A-Za-z0-9_]{4,50}$/', $account['username'])) {
        return 'Username must be 4-50 letters, numbers or underscores.';
    }
    if ($account['password'] !== '' && strlen($account['password']) < 6) {
        return 'Password must be at least 6 characters long.';
    }
    if ($require_confirm && $account['password'] !== $account['confirm_password']) {
        return 'The two passwords do not match.';
    }
    if ($account['role'] === 'customer' && $account['address'] === '') {
        return 'Address is required for customer orders.';
    }
    if (strlen($account['address']) > 65535 || strlen($account['bio']) > 65535) {
        return 'Address or bio is too long.';
    }
    if ($account['role'] === 'doctor') {
        if ($account['specialization'] === '' || $account['qualification'] === '' ||
            $account['available_days'] === '' || $account['available_time'] === '') {
            return 'Please fill in the doctor details.';
        }
        if (strlen($account['specialization']) > 100 || strlen($account['qualification']) > 150 ||
            strlen($account['available_days']) > 100 || strlen($account['available_time']) > 100 ||
            !preg_match('/^[0-9]{1,2}$/', $account['experience_years']) ||
            !is_numeric($account['consultation_fee']) || (float)$account['consultation_fee'] < 0 ||
            (float)$account['consultation_fee'] > 99999999.99) {
            return 'Please enter valid doctor details.';
        }
    }
    if ($account['role'] === 'delivery' && !in_array($account['company_name'],
        ['Pathao Fast', 'PetPanda Go', 'Speed Fast', 'Jhinku BD'], true)) {
        return 'Choose a delivery company.';
    }
    if (customer_identity_exists($conn, $account['username'], $account['email'], $account['phone'], $editing_id)) {
        return 'Username, email or phone number is already registered.';
    }
    return '';
}
