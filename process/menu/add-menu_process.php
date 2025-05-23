<?php
session_start();
require_once '../../includes/conn.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../../login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $description = $_POST['description'] ?? '';
    $price = $_POST['price'] ?? 0;
    $category_id = $_POST['category_id'] ?? 0;
    $available = isset($_POST['available']) ? 1 : 0;

    try {
        $stmt = $conn->prepare("INSERT INTO menus (name, description, price, category_id, available) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$name, $description, $price, $category_id, $available]);
        header("Location: /admin/menu/index.php?success=1");
    } catch (PDOException $e) {
        
        echo "Gagal menyimpan: " . $e->getMessage();
    }
} else {
    header("Location: add.php");
    exit();
}
