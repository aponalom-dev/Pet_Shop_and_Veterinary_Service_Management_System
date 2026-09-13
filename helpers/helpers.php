<?php

function role_base_url($role) {
    $directory = str_replace("\\", "/", dirname($_SERVER["SCRIPT_NAME"]));
    if (basename($directory) === $role) {
        return rtrim($directory, "/") . "/";
    }
    return rtrim($directory, "/") . "/" . $role . "/";
}

function route_url($page, $query = "") {
    $directory = str_replace("\\", "/", dirname($_SERVER["SCRIPT_NAME"]));
    $role = basename($directory);
    if (in_array($role, ["admin", "customer", "doctor", "delivery"], true)) {
        $directory = dirname($directory);
    }
    $url = rtrim($directory, "/") . "/index.php?page=" . urlencode($page);
    if ($query !== "") {
        $url .= "&" . $query;
    }
    return $url;
}

function json_out($data, $code = 200) {
    http_response_code($code);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($data);
    exit;
}

function csrf_token() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function csrf_field() {
    echo '<input type="hidden" name="csrf_token" value="' . csrf_token() . '">';
}

function csrf_check() {
    $token = $_POST['csrf_token'] ?? '';
    if (!is_string($token) || !hash_equals(csrf_token(), $token)) {
        http_response_code(403);
        die('Security check failed (invalid CSRF token). Please go back and try again.');
    }
}

function profile_image_file($filename) {
    $name = basename(str_replace('\\', '/', trim((string)$filename)));
    if ($name === '' || $name === 'default.png' ||
        !preg_match('/\.(jpg|jpeg|png|webp)$/i', $name) ||
        !is_file(__DIR__ . '/../assets/uploads/profiles/' . $name)) {
        return 'default.svg';
    }
    return $name;
}

function profile_image_choices() {
    $directory = __DIR__ . '/../assets/uploads/profiles/';
    $files = scandir($directory);
    $choices = [];
    if (!$files) return $choices;
    foreach ($files as $file) {
        if (preg_match('/\.(jpg|jpeg|png|webp)$/i', $file) && is_file($directory . $file)) {
            $choices[] = $file;
        }
    }
    sort($choices);
    return $choices;
}
