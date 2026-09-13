<?php

function username_exists($conn, $username) {
    $stmt = mysqli_prepare($conn, "SELECT user_id FROM users WHERE username = ? LIMIT 1");
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    $exists = mysqli_num_rows(mysqli_stmt_get_result($stmt)) > 0;
    mysqli_stmt_close($stmt);
    return $exists;
}

function find_user_for_login($conn, $identifier) {
    $sql = "SELECT u.user_id, u.full_name, u.username, u.email, u.password, u.role, u.status,
                   d.doctor_id, da.agent_id, da.status AS agent_status
            FROM users u
            LEFT JOIN doctors d ON d.user_id = u.user_id
            LEFT JOIN delivery_agents da ON da.user_id = u.user_id
            WHERE u.username = ? OR u.email = ? LIMIT 1";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ss", $identifier, $identifier);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $user = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);
    return $user;
}

function customer_identity_exists($conn, $username, $email, $phone, $exclude_id = 0) {
    $sql = "SELECT user_id FROM users WHERE (username = ? OR email = ? OR phone = ?) AND user_id != ? LIMIT 1";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "sssi", $username, $email, $phone, $exclude_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $exists = mysqli_num_rows($result) > 0;
    mysqli_stmt_close($stmt);
    return $exists;
}

function search_admin_accounts($conn, $term, $role) {
    $like = '%' . $term . '%';
    $sql = "SELECT user_id, full_name, username, email, phone, role, status
            FROM users
            WHERE (? = '' OR role = ?)
              AND (? = '' OR full_name LIKE ? OR username LIKE ? OR email LIKE ? OR phone LIKE ?)
            ORDER BY user_id DESC";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'sssssss', $role, $role, $term, $like, $like, $like, $like);
    mysqli_stmt_execute($stmt);
    $accounts = mysqli_fetch_all(mysqli_stmt_get_result($stmt), MYSQLI_ASSOC);
    mysqli_stmt_close($stmt);
    return $accounts;
}

function get_admin_account($conn, $user_id) {
    $sql = "SELECT u.user_id, u.full_name, u.username, u.email, u.phone, u.role, u.status, u.address, u.profile_image,
                   d.specialization, d.qualification, d.experience_years, d.consultation_fee,
                   d.available_days, d.available_time, d.bio, a.company_name
            FROM users u
            LEFT JOIN doctors d ON d.user_id = u.user_id
            LEFT JOIN delivery_agents a ON a.user_id = u.user_id
            WHERE u.user_id = ? LIMIT 1";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $user_id);
    mysqli_stmt_execute($stmt);
    $account = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
    mysqli_stmt_close($stmt);
    return $account;
}

function set_admin_account_status($conn, $user_id, $status) {
    $stmt = mysqli_prepare($conn, "UPDATE users SET status = ? WHERE user_id = ?");
    mysqli_stmt_bind_param($stmt, 'si', $status, $user_id);
    $saved = mysqli_stmt_execute($stmt);
    $changed = mysqli_stmt_affected_rows($stmt);
    mysqli_stmt_close($stmt);
    return $saved && $changed > 0;
}

function delete_admin_account($conn, $user_id) {
    $stmt = mysqli_prepare($conn, "DELETE FROM users WHERE user_id = ?");
    mysqli_stmt_bind_param($stmt, 'i', $user_id);
    $saved = mysqli_stmt_execute($stmt);
    $changed = mysqli_stmt_affected_rows($stmt);
    mysqli_stmt_close($stmt);
    return $saved && $changed > 0;
}

function update_admin_account($conn, $user_id, $account) {
    $current = get_admin_account($conn, $user_id);
    if (!$current || !mysqli_begin_transaction($conn)) return false;

    $full_name = $account['full_name'];
    $username = $account['username'];
    $email = $account['email'];
    $phone = $account['phone'];
    $address = $account['address'];
    $role = $account['role'];
    $status = $account['status'];
    $stmt = mysqli_prepare($conn, "UPDATE users SET full_name = ?, username = ?, email = ?, phone = ?, address = ?, role = ?, status = ? WHERE user_id = ?");
    mysqli_stmt_bind_param($stmt, 'sssssssi', $full_name, $username, $email, $phone, $address, $role, $status, $user_id);
    $saved = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    if (!$saved) { mysqli_rollback($conn); return false; }

    if ($account['password'] !== '') {
        $hash = password_hash($account['password'], PASSWORD_DEFAULT);
        $stmt = mysqli_prepare($conn, "UPDATE users SET password = ? WHERE user_id = ?");
        mysqli_stmt_bind_param($stmt, 'si', $hash, $user_id);
        $saved = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        if (!$saved) { mysqli_rollback($conn); return false; }
    }

    if ($role === 'doctor') {
        $profile_image = $account['profile_image'];
        $stmt = mysqli_prepare($conn, "UPDATE users SET profile_image = ? WHERE user_id = ?");
        mysqli_stmt_bind_param($stmt, 'si', $profile_image, $user_id);
        $saved = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        if (!$saved) { mysqli_rollback($conn); return false; }
    }

    if ($current['role'] === 'doctor' && $role !== 'doctor') {
        $stmt = mysqli_prepare($conn, "DELETE FROM doctors WHERE user_id = ?");
        mysqli_stmt_bind_param($stmt, 'i', $user_id);
        $saved = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        if (!$saved) { mysqli_rollback($conn); return false; }
    }
    if ($current['role'] === 'delivery' && $role !== 'delivery') {
        $stmt = mysqli_prepare($conn, "DELETE FROM delivery_agents WHERE user_id = ?");
        mysqli_stmt_bind_param($stmt, 'i', $user_id);
        $saved = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        if (!$saved) { mysqli_rollback($conn); return false; }
    }

    if ($role === 'doctor') {
        $specialization = $account['specialization'];
        $qualification = $account['qualification'];
        $experience_years = (int)$account['experience_years'];
        $consultation_fee = (float)$account['consultation_fee'];
        $available_days = $account['available_days'];
        $available_time = $account['available_time'];
        $bio = $account['bio'];
        if ($current['role'] === 'doctor') {
            $sql = "UPDATE doctors SET specialization = ?, qualification = ?, experience_years = ?, consultation_fee = ?, available_days = ?, available_time = ?, bio = ? WHERE user_id = ?";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, 'ssidsssi', $specialization, $qualification, $experience_years, $consultation_fee, $available_days, $available_time, $bio, $user_id);
        } else {
            $sql = "INSERT INTO doctors (user_id, specialization, qualification, experience_years, consultation_fee, available_days, available_time, bio) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, 'issidsss', $user_id, $specialization, $qualification, $experience_years, $consultation_fee, $available_days, $available_time, $bio);
        }
        $saved = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        if (!$saved) { mysqli_rollback($conn); return false; }
    }

    if ($role === 'delivery') {
        $company_name = $account['company_name'];
        if ($current['role'] === 'delivery') {
            $stmt = mysqli_prepare($conn, "UPDATE delivery_agents SET company_name = ? WHERE user_id = ?");
            mysqli_stmt_bind_param($stmt, 'si', $company_name, $user_id);
        } else {
            $stmt = mysqli_prepare($conn, "INSERT INTO delivery_agents (user_id, company_name) VALUES (?, ?)");
            mysqli_stmt_bind_param($stmt, 'is', $user_id, $company_name);
        }
        $saved = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        if (!$saved) { mysqli_rollback($conn); return false; }
    }

    if (!mysqli_commit($conn)) { mysqli_rollback($conn); return false; }
    return true;
}

function create_account($conn, $account) {
    if (!mysqli_begin_transaction($conn)) return false;

    $full_name = $account['full_name'];
    $username = $account['username'];
    $email = $account['email'];
    $phone = $account['phone'];
    $hashed_password = password_hash($account['password'], PASSWORD_DEFAULT);
    $role = $account['role'];
    $address = $account['address'];
    $status = 'active';
    $sql = "INSERT INTO users (full_name, username, email, phone, password, role, address, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    if (!$stmt) {
        mysqli_rollback($conn);
        return false;
    }
    mysqli_stmt_bind_param($stmt, 'ssssssss', $full_name, $username, $email, $phone, $hashed_password, $role, $address, $status);
    $saved = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    if (!$saved) {
        mysqli_rollback($conn);
        return false;
    }
    $user_id = mysqli_insert_id($conn);

    if ($role === 'doctor') {
        $specialization = $account['specialization'];
        $qualification = $account['qualification'];
        $experience_years = (int)$account['experience_years'];
        $consultation_fee = (float)$account['consultation_fee'];
        $available_days = $account['available_days'];
        $available_time = $account['available_time'];
        $bio = $account['bio'];
        $sql = "INSERT INTO doctors (user_id, specialization, qualification, experience_years, consultation_fee, available_days, available_time, bio) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $sql);
        if (!$stmt) {
            mysqli_rollback($conn);
            return false;
        }
        mysqli_stmt_bind_param($stmt, 'issidsss', $user_id, $specialization, $qualification, $experience_years, $consultation_fee, $available_days, $available_time, $bio);
        $saved = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        if (!$saved) {
            mysqli_rollback($conn);
            return false;
        }
    }

    if ($role === 'delivery') {
        $company_name = $account['company_name'];
        $sql = "INSERT INTO delivery_agents (user_id, company_name) VALUES (?, ?)";
        $stmt = mysqli_prepare($conn, $sql);
        if (!$stmt) {
            mysqli_rollback($conn);
            return false;
        }
        mysqli_stmt_bind_param($stmt, 'is', $user_id, $company_name);
        $saved = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        if (!$saved) {
            mysqli_rollback($conn);
            return false;
        }
    }

    if (!mysqli_commit($conn)) {
        mysqli_rollback($conn);
        return false;
    }
    return true;
}

function find_customer_account($conn, $customer_id) {
    $sql = "SELECT user_id, full_name, username, email, phone, address, password FROM users WHERE user_id = ? LIMIT 1";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $customer_id);
    mysqli_stmt_execute($stmt);
    $customer = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
    mysqli_stmt_close($stmt);
    return $customer;
}

function customer_email_is_taken($conn, $email, $customer_id) {
    $sql = "SELECT user_id FROM users WHERE email = ? AND user_id != ? LIMIT 1";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "si", $email, $customer_id);
    mysqli_stmt_execute($stmt);
    $taken = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
    mysqli_stmt_close($stmt);
    return (bool) $taken;
}

function update_customer_profile($conn, $customer_id, $full_name, $email, $phone, $address) {
    $sql = "UPDATE users SET full_name = ?, email = ?, phone = ?, address = ? WHERE user_id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ssssi", $full_name, $email, $phone, $address, $customer_id);
    $saved = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return $saved;
}

function update_customer_password($conn, $customer_id, $new_password_hash) {
    $sql = "UPDATE users SET password = ? WHERE user_id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "si", $new_password_hash, $customer_id);
    $saved = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return $saved;
}
