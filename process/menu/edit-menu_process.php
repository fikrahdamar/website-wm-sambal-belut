<?php
session_start();
require_once '../../includes/conn.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../../login.php");
    exit();
}

// 
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'], $_POST['name'], $_POST['price'], $_POST['category_id'])) {
    $id = $_POST['id'];
    $name = trim($_POST['name']);
    $description = isset($_POST['description']) ? trim($_POST['description']) : '';
    $price = $_POST['price'];
    $category_id = $_POST['category_id'];
    $available = isset($_POST['available']) ? 1 : 0;

    try {
        $stmt = $conn->prepare("UPDATE menus SET name = ?, description = ?, price = ?, category_id = ?, available = ? WHERE id = ?");
        $stmt->execute([$name, $description, $price, $category_id, $available, $id]);

        header("Location: ../../admin/menu/index.php?success=edited");
        exit();
    } catch (PDOException $e) {
        echo "Terjadi kesalahan: " . $e->getMessage();
    }
} else {
    header("Location: ../../admin/menu/index.php");
    exit();
}