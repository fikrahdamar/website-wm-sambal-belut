<?php
session_start();
require_once '../../includes/conn.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../../login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);

    if (!empty($name)) {
        $stmt = $conn->prepare("INSERT INTO menu_categories (menu_categoris_name) VALUES (?)");
        $stmt->execute([$name]);

        header("Location: ../../admin/category/index.php?success=added");
        exit();
    } else {
        header("Location: ../../admin/category/add.php?error=empty");
        exit();
    }
} else {
    header("Location: ../../admin/category/index.php");
    exit();
}
