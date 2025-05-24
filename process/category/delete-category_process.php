<?php
session_start();
require_once '../../includes/conn.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../../login.php");
    exit();
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $checkMenus = $conn->prepare("SELECT COUNT(*) FROM menus WHERE category_id = ?");
    $checkMenus->execute([$id]);
    $menuCount = $checkMenus->fetchColumn();

    if ($menuCount > 0) {
        header("Location: ../../admin/category/index.php?error=in_use");
        exit();
    }

    $stmt = $conn->prepare("DELETE FROM menu_categories WHERE id = ?");
    $stmt->execute([$id]);

    header("Location: ../../admin/category/index.php?success=deleted");
    exit();

} else {
    header("Location: ../../admin/category/index.php");
    exit();

}
