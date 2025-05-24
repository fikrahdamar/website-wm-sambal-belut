<?php
session_start();
require_once '../../includes/conn.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../../login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $name = trim($_POST['name']);

    if (!empty($name) && !empty($id)) {
        $stmt = $conn->prepare("UPDATE menu_categories SET menu_categoris_name = ? WHERE id = ?");
        $stmt->execute([$name, $id]);

        header("Location: ../../admin/category/index.php?success=edited");
        exit();

    } else {
        header("Location: ../../admin/category/edit.php?id=$id&error=empty");
        exit();

    }
} else {
    header("Location: ../../admin/category/index.php");
    exit();
}
