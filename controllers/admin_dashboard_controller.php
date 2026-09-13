<?php
require_once __DIR__ . '/../helpers/helpers.php';
require_once __DIR__ . '/account_controller.php';

function admin_dashboard_controller($conn) {

    if (!isset($_SESSION["user_id"]) || ($_SESSION["role"] ?? "") !== "admin") {
        header("Location: " . route_url("login"));
        exit;
    }

    $admin_name = $_SESSION["full_name"] ?? "Master";
    $username = $_SESSION["username"] ?? "Admin";
    $account = blank_account_form();
    $photo_choices = profile_image_choices();
    $error_message = "";
    $success_message = "";
    $editing_id = 0;

    if ($_SERVER["REQUEST_METHOD"] === "POST" && (isset($_POST["save_account"]) || isset($_POST["set_status"]) || isset($_POST["delete_account"]))) {
        csrf_check();
        $target_id = (int)($_POST["account_id"] ?? 0);
        $target = $target_id > 0 ? get_admin_account($conn, $target_id) : false;
        if (!$target) {
            $error_message = "That account no longer exists.";
        } elseif (isset($_POST["save_account"])) {
            $editing_id = $target_id;
            $account = posted_account_form();
            if (!in_array($account["status"], ["active", "inactive"], true)) {
                $error_message = "Choose a valid account status.";
            } elseif ($target_id === (int)$_SESSION["user_id"] && ($account["role"] !== "admin" || $account["status"] !== "active")) {
                $error_message = "You cannot remove your own admin access.";
            } else {
                $error_message = new_account_error($conn, $account, true, false, $target_id);
            }
            if ($error_message === "" && $account["role"] === "doctor" &&
                $account["profile_image"] !== "default.png" &&
                !in_array($account["profile_image"], $photo_choices, true)) {
                $error_message = "Choose a valid doctor photo.";
            }
            if ($error_message === "") {
                if (update_admin_account($conn, $target_id, $account)) {
                    if ($target_id === (int)$_SESSION["user_id"]) {
                        $_SESSION["full_name"] = $account["full_name"];
                        $_SESSION["username"] = $account["username"];
                        $_SESSION["email"] = $account["email"];
                        $admin_name = $account["full_name"];
                        $username = $account["username"];
                    }
                    $success_message = "Account updated successfully.";
                    $editing_id = 0;
                    $account = blank_account_form();
                } else {
                    $error_message = "Account could not be updated. Linked appointments or orders may prevent a role change.";
                }
            }
        } elseif ($target_id === (int)$_SESSION["user_id"]) {
            $error_message = "You cannot suspend or delete your own account.";
        } elseif (isset($_POST["set_status"])) {
            $new_status = $target["status"] === "active" ? "inactive" : "active";
            if (set_admin_account_status($conn, $target_id, $new_status)) {
                $success_message = $new_status === "active" ? "Account reactivated." : "Account suspended.";
            } else {
                $error_message = "Account status could not be changed.";
            }
        } elseif (delete_admin_account($conn, $target_id)) {
            $success_message = "Account deleted.";
        } else {
            $error_message = "Account could not be deleted. It may have linked appointments or orders.";
        }
    }

    if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["create_account"])) {
        csrf_check();
        $account = posted_account_form();
        $error_message = new_account_error($conn, $account, true, false);
        if ($error_message === "") {
            if (create_account($conn, $account)) {
                $success_message = "Account created successfully.";
                $account = blank_account_form();
            } else {
                $error_message = "Account could not be created.";
            }
        }
    }

    if ($_SERVER["REQUEST_METHOD"] !== "POST" && isset($_GET["edit"])) {
        $editing_id = (int)$_GET["edit"];
        $stored = $editing_id > 0 ? get_admin_account($conn, $editing_id) : false;
        if (!$stored) {
            $editing_id = 0;
            $error_message = "That account no longer exists.";
        } else {
            $account = blank_account_form();
            foreach ($account as $field => $value) {
                if (isset($stored[$field])) {
                    $account[$field] = (string)$stored[$field];
                }
            }
        }
    }

    $accounts = search_admin_accounts($conn, "", "");

    require __DIR__ . '/../views/admin/dashboard.php';
}
