<?php
require_once __DIR__ . '/../helpers/helpers.php';
require_once __DIR__ . '/../models/delivery_model.php';

function delivery_index_controller($conn) {
    $companies = ["Pathao Fast", "PetPanda Go", "Speed Fast", "Jhinku BD"];
    $company_data = delivery_company_cards($conn, $companies);
    require __DIR__ . '/../views/delivery/index.php';
}
