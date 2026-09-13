<?php

function admin_inventory_list($conn, $type) {
    $items = [];
    if ($type === "pet") {
        $sql = "SELECT p.*, pc.category_name FROM pets p LEFT JOIN pet_categories pc ON p.category_id = pc.category_id ORDER BY p.pet_id DESC";
    } else {
        $sql = "SELECT p.*, pc.category_name FROM products p LEFT JOIN product_categories pc ON p.category_id = pc.category_id ORDER BY p.product_id DESC";
    }
    $result = mysqli_query($conn, $sql);
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $items[] = $row;
        }
    }
    return $items;
}

function search_admin_inventory($conn, $type, $term) {
    if ($type === "pet") {
        $sql = "SELECT p.pet_id, p.pet_name, pc.category_name, p.breed, p.price, p.stock, p.status FROM pets p LEFT JOIN pet_categories pc ON p.category_id = pc.category_id WHERE p.pet_name LIKE ? OR pc.category_name LIKE ? OR p.breed LIKE ? ORDER BY p.pet_id DESC";
    } else {
        $sql = "SELECT p.product_id, p.product_name, pc.category_name, p.price, p.stock, p.status FROM products p LEFT JOIN product_categories pc ON p.category_id = pc.category_id WHERE p.product_name LIKE ? OR pc.category_name LIKE ? OR p.brand LIKE ? ORDER BY p.product_id DESC";
    }
    $search = "%" . $term . "%";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "sss", $search, $search, $search);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $items = [];
    while ($row = mysqli_fetch_assoc($result)) $items[] = $row;
    mysqli_stmt_close($stmt);
    return $items;
}

function admin_overview_data($conn) {
    $data = [
        "total_revenue" => 0,
        "total_orders" => 0,
        "pets_in_stock" => 0,
        "low_stock" => 0,
        "recent_orders" => []
    ];
    $queries = [
        "total_revenue" => "SELECT COALESCE(SUM(total_amount), 0) AS total_revenue FROM orders WHERE payment_status = 'Paid'",
        "total_orders" => "SELECT COUNT(*) AS total_orders FROM orders",
        "pets_in_stock" => "SELECT COALESCE(SUM(stock), 0) AS pets_in_stock FROM pets WHERE status = 'Available'",
        "low_stock" => "SELECT COUNT(*) AS low_stock FROM products WHERE stock <= 5"
    ];
    foreach ($queries as $key => $sql) {
        $result = mysqli_query($conn, $sql);
        if ($result) {
            $row = mysqli_fetch_assoc($result);
            $data[$key] = $row[$key];
        }
    }
    $sql = "SELECT o.order_id, o.total_amount, o.payment_status, u.full_name FROM orders o JOIN users u ON o.customer_id = u.user_id ORDER BY o.order_id DESC LIMIT 5";
    $result = mysqli_query($conn, $sql);
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $data["recent_orders"][] = $row;
        }
    }
    return $data;
}

function admin_sales_data($conn) {
    $total_revenue = 0;
    $total_orders = 0;
    $paid_orders = 0;
    $average_order_value = 0;

    $result = mysqli_query($conn, "SELECT COALESCE(SUM(total_amount), 0) AS total_revenue FROM orders WHERE payment_status = 'Paid'");
    if ($result) $total_revenue = mysqli_fetch_assoc($result)["total_revenue"];

    $result = mysqli_query($conn, "SELECT COUNT(*) AS total_orders FROM orders");
    if ($result) $total_orders = mysqli_fetch_assoc($result)["total_orders"];

    $result = mysqli_query($conn, "SELECT COUNT(*) AS paid_orders FROM orders WHERE payment_status = 'Paid'");
    if ($result) $paid_orders = mysqli_fetch_assoc($result)["paid_orders"];

    if ($paid_orders > 0) $average_order_value = $total_revenue / $paid_orders;

    $category_sales = [];

    $sql = "SELECT
                CASE
                    WHEN oi.item_type = 'pet' THEN pc.category_name
                    WHEN oi.item_type = 'product' THEN prc.category_name
                END AS category_name,
                SUM(oi.quantity) AS total_sold
            FROM order_items oi
            JOIN orders o ON oi.order_id = o.order_id
            LEFT JOIN pets p ON oi.item_type = 'pet' AND oi.item_id = p.pet_id
            LEFT JOIN pet_categories pc ON p.category_id = pc.category_id
            LEFT JOIN products pr ON oi.item_type = 'product' AND oi.item_id = pr.product_id
            LEFT JOIN product_categories prc ON pr.category_id = prc.category_id
            WHERE o.payment_status = 'Paid'
            GROUP BY category_name
            HAVING category_name IS NOT NULL
            ORDER BY total_sold DESC";

    $result = mysqli_query($conn, $sql);

    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $category_sales[] = $row;
        }
    }

    $top_items = [];

    $sql = "SELECT
                oi.item_type,
                oi.item_id,
                SUM(oi.quantity) AS total_sold,
                CASE
                    WHEN oi.item_type = 'pet' THEN p.pet_name
                    WHEN oi.item_type = 'product' THEN pr.product_name
                END AS item_name,
                CASE
                    WHEN oi.item_type = 'pet' THEN pc.category_name
                    WHEN oi.item_type = 'product' THEN prc.category_name
                END AS category_name,
                CASE
                    WHEN oi.item_type = 'pet' THEN p.image
                    WHEN oi.item_type = 'product' THEN pr.image
                END AS item_image
            FROM order_items oi
            JOIN orders o ON oi.order_id = o.order_id
            LEFT JOIN pets p ON oi.item_type = 'pet' AND oi.item_id = p.pet_id
            LEFT JOIN pet_categories pc ON p.category_id = pc.category_id
            LEFT JOIN products pr ON oi.item_type = 'product' AND oi.item_id = pr.product_id
            LEFT JOIN product_categories prc ON pr.category_id = prc.category_id
            WHERE o.payment_status = 'Paid'
            GROUP BY oi.item_type, oi.item_id, item_name, category_name, item_image
            ORDER BY total_sold DESC
            LIMIT 6";

    $result = mysqli_query($conn, $sql);

    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $top_items[] = $row;
        }
    }

    $recent_sales = [];

    $sql = "SELECT
                o.order_id,
                u.full_name,
                o.total_amount,
                o.payment_method,
                o.order_status,
                o.order_date
            FROM orders o
            JOIN users u ON o.customer_id = u.user_id
            WHERE o.payment_status = 'Paid'
            ORDER BY o.order_id DESC
            LIMIT 5";

    $result = mysqli_query($conn, $sql);

    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $recent_sales[] = $row;
        }
    }

    $category_names = [];
    $category_values = [];

    foreach ($category_sales as $category) {
        $category_names[] = $category["category_name"];
        $category_values[] = (int)$category["total_sold"];
    }
    return [
        "total_revenue" => $total_revenue,
        "total_orders" => $total_orders,
        "paid_orders" => $paid_orders,
        "average_order_value" => $average_order_value,
        "category_sales" => $category_sales,
        "top_items" => $top_items,
        "recent_sales" => $recent_sales,
        "category_names" => $category_names,
        "category_values" => $category_values
    ];
}

function admin_add_inventory($conn, $type, $item_id, $price, $quantity) {
    if ($type === "pet") {
        $sql = "UPDATE pets SET price = ?, stock = stock + ?, status = 'Available' WHERE pet_id = ?";
    } else {
        $sql = "UPDATE products SET price = ?, stock = stock + ? WHERE product_id = ?";
    }
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "dii", $price, $quantity, $item_id);
    mysqli_stmt_execute($stmt);
    $changed = mysqli_stmt_affected_rows($stmt) > 0;
    mysqli_stmt_close($stmt);
    return $changed;
}

function admin_remove_inventory($conn, $type, $item_id, $quantity) {
    if ($type === "pet") {
        $sql = "UPDATE pets SET stock = stock - ? WHERE pet_id = ? AND stock >= ?";
    } elseif ($type === "product") {
        $sql = "UPDATE products SET stock = stock - ? WHERE product_id = ? AND stock >= ?";
    } else {
        return false;
    }
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "iii", $quantity, $item_id, $quantity);
    mysqli_stmt_execute($stmt);
    $changed = mysqli_stmt_affected_rows($stmt) > 0;
    mysqli_stmt_close($stmt);
    return $changed;
}

function admin_update_review_status($conn, $review_id, $status) {
    $stmt = mysqli_prepare($conn, "UPDATE reviews SET status = ? WHERE review_id = ?");
    mysqli_stmt_bind_param($stmt, "si", $status, $review_id);
    mysqli_stmt_execute($stmt);
    $changed = mysqli_stmt_affected_rows($stmt) >= 0;
    mysqli_stmt_close($stmt);
    return $changed;
}

function admin_update_delivery_status($conn, $delivery_id, $status) {
    if ($status === "Delivered") {
        $sql = "UPDATE deliveries SET delivery_status = ?, delivered_at = NOW() WHERE delivery_id = ?";
    } else {
        $sql = "UPDATE deliveries SET delivery_status = ?, delivered_at = NULL WHERE delivery_id = ?";
    }
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "si", $status, $delivery_id);
    mysqli_stmt_execute($stmt);
    $changed = mysqli_stmt_affected_rows($stmt) >= 0;
    mysqli_stmt_close($stmt);
    return $changed;
}

function admin_delivery_data($conn) {
    $partners = [
        "Pathao Fast",
        "PetPanda Go",
        "Speed Fast",
        "Jhinku BD",
        "Shop Pickup"
    ];

    $partner_stats = [];

    foreach ($partners as $partner) {
        $partner_stats[$partner] = [
            "orders" => 0,
            "delivered" => 0
        ];
    }

    $sql = "SELECT delivery_method, COUNT(*) AS total_orders
            FROM orders
            GROUP BY delivery_method";

    $result = mysqli_query($conn, $sql);

    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            if (isset($partner_stats[$row["delivery_method"]])) {
                $partner_stats[$row["delivery_method"]]["orders"] = (int)$row["total_orders"];
            }
        }
    }

    $sql = "SELECT o.delivery_method, COUNT(*) AS delivered_count
            FROM deliveries d
            JOIN orders o ON d.order_id = o.order_id
            WHERE d.delivery_status = 'Delivered'
            GROUP BY o.delivery_method";

    $result = mysqli_query($conn, $sql);

    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            if (isset($partner_stats[$row["delivery_method"]])) {
                $partner_stats[$row["delivery_method"]]["delivered"] = (int)$row["delivered_count"];
            }
        }
    }

    $total_orders = 0;

    foreach ($partner_stats as $partner) {
        $total_orders += $partner["orders"];
    }

    $deliveries = [];

    $sql = "SELECT
                d.delivery_id,
                d.order_id,
                d.delivery_status,
                d.assigned_at,
                d.delivered_at,
                d.delivery_note,
                o.delivery_method,
                o.order_status,
                o.total_amount,
                u.full_name AS customer_name,
                agent.full_name AS agent_name
            FROM deliveries d
            JOIN orders o ON d.order_id = o.order_id
            JOIN users u ON o.customer_id = u.user_id
            JOIN users agent ON d.delivery_agent_id = agent.user_id
            ORDER BY d.delivery_id DESC";

    $result = mysqli_query($conn, $sql);

    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $deliveries[] = $row;
        }
    }

    $partner_names = [];
    $partner_values = [];

    foreach ($partner_stats as $name => $data) {
        $partner_names[] = $name;
        $partner_values[] = $data["orders"];
    }
    return [
        "partners" => $partners,
        "partner_stats" => $partner_stats,
        "total_orders" => $total_orders,
        "deliveries" => $deliveries,
        "partner_names" => $partner_names,
        "partner_values" => $partner_values
    ];
}

function admin_reviews_data($conn) {
    $reviews = [];

    $sql = "SELECT
                r.review_id,
                r.item_type,
                r.item_id,
                r.rating,
                r.comment,
                r.status,
                r.created_at,
                u.full_name AS customer_name,
                CASE
                    WHEN r.item_type = 'pet' THEN p.pet_name
                    WHEN r.item_type = 'product' THEN pr.product_name
                END AS item_name
            FROM reviews r
            JOIN users u ON r.customer_id = u.user_id
            LEFT JOIN pets p ON r.item_type = 'pet' AND r.item_id = p.pet_id
            LEFT JOIN products pr ON r.item_type = 'product' AND r.item_id = pr.product_id
            ORDER BY r.created_at DESC";

    $result = mysqli_query($conn, $sql);

    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $reviews[] = $row;
        }
    }

    $champion_pet = null;

    $champion_sql = "SELECT
                        p.pet_id,
                        p.pet_name,
                        p.breed,
                        p.image,
                        COALESCE(rv.average_rating, 0) AS average_rating,
                        COALESCE(rv.total_reviews, 0) AS total_reviews,
                        COALESCE(sl.total_sold, 0) AS total_sold
                     FROM pets p
                     LEFT JOIN (
                        SELECT
                            item_id,
                            ROUND(AVG(rating), 1) AS average_rating,
                            COUNT(*) AS total_reviews
                        FROM reviews
                        WHERE item_type = 'pet' AND status = 'Visible'
                        GROUP BY item_id
                     ) rv ON p.pet_id = rv.item_id
                     LEFT JOIN (
                        SELECT
                            oi.item_id,
                            SUM(oi.quantity) AS total_sold
                        FROM order_items oi
                        JOIN orders o ON oi.order_id = o.order_id
                        WHERE oi.item_type = 'pet' AND o.payment_status = 'Paid'
                        GROUP BY oi.item_id
                     ) sl ON p.pet_id = sl.item_id
                     WHERE rv.total_reviews > 0
                     ORDER BY rv.average_rating DESC, sl.total_sold DESC, rv.total_reviews DESC
                     LIMIT 1";

    $champion_result = mysqli_query($conn, $champion_sql);

    if ($champion_result && mysqli_num_rows($champion_result) > 0) {
        $champion_pet = mysqli_fetch_assoc($champion_result);
    }
    return [
        "reviews" => $reviews,
        "champion_pet" => $champion_pet
    ];
}
