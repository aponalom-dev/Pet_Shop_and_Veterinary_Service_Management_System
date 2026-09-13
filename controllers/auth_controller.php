<?php
require_once __DIR__ . '/../helpers/helpers.php';

require_once __DIR__ . '/../models/user_model.php';
require_once __DIR__ . '/account_controller.php';

function login_controller($conn) {
    $login_identifier = $_COOKIE["remember_login"] ?? "";
    $error_message = "";

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        csrf_check();
        $login_identifier = $_POST["login_identifier"] ?? "";
        $password = $_POST["password"] ?? "";
        $login_identifier = is_string($login_identifier) ? trim($login_identifier) : "";
        $password = is_string($password) ? $password : "";

        if (empty($login_identifier) || empty($password)) {
            $error_message = "Please enter your username/email and password.";
        } else {
            $user = find_user_for_login($conn, $login_identifier);

            if (!$user || !password_verify($password, $user["password"])) {
                $error_message = "Invalid username/email or password.";
            } elseif ($user["status"] !== "active") {
                $error_message = "Your account is currently inactive.";
            } elseif ($user["role"] === "doctor" && !$user["doctor_id"]) {
                $error_message = "Your doctor profile is not available.";
            } elseif ($user["role"] === "delivery" && (!$user["agent_id"] || $user["agent_status"] !== "Active")) {
                $error_message = "Your delivery account is not active.";
            } else {
                session_regenerate_id(true);
                $_SESSION["user_id"] = $user["user_id"];
                $_SESSION["full_name"] = $user["full_name"];
                $_SESSION["username"] = $user["username"];
                $_SESSION["email"] = $user["email"];
                $_SESSION["role"] = $user["role"];

                if (isset($_POST["remember_me"])) {
                    setcookie("remember_login", $login_identifier, time() + (86400 * 30), "/");
                } else {
                    setcookie("remember_login", "", time() - 3600, "/");
                }

                if ($user["role"] === "customer" && ($_SESSION["redirect_after_login"] ?? "") === "confirm_appointment") {
                    unset($_SESSION["redirect_after_login"]);
                    header("Location: " . route_url("confirm_appointment"));
                    exit;
                }

                switch ($user["role"]) {
                    case "admin":
                        header("Location: " . route_url("admin/dashboard"));
                        exit;
                    case "customer":
                        header("Location: " . route_url("customer/dashboard"));
                        exit;
                    case "doctor":
                        header("Location: " . route_url("doctor/dashboard"));
                        exit;
                    case "delivery":
                        header("Location: " . route_url("delivery/dashboard"));
                        exit;
                    default:
                        session_unset();
                        session_destroy();
                        $error_message = "Invalid account role.";
                }
            }
        }
    }

    require __DIR__ . '/../views/auth/login.php';
}

function register_controller($conn) {
    $account = blank_account_form();
    $error_message = "";
    $success_message = "";

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        csrf_check();
        $account = posted_account_form();
        $error_message = new_account_error($conn, $account, false, true);
        if ($error_message === "") {
            if (create_account($conn, $account)) {
                $success_message = "Account created successfully. You can now login.";
                $account = blank_account_form();
            } else {
                $error_message = "Registration failed. Please try again.";
            }
        }
    }

    require __DIR__ . '/../views/auth/register.php';
}

function logout_controller() {
    $_SESSION = [];
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(
            session_name(),
            "",
            time() - 42000,
            $params["path"],
            $params["domain"],
            $params["secure"],
            $params["httponly"]
        );
    }
    session_destroy();
    header("Location: " . route_url("home"));
    exit;
}
