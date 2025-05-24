<?php
session_start();
require_once '../../includes/conn.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../../login.php");
    exit();
}


if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: ../../admin/menu/index.php");
    exit();
}

$menuId = (int) $_GET['id'];

try {
    
    $stmt = $conn->prepare("DELETE FROM menus WHERE id = :id");
    $stmt->bindParam(':id', $menuId, PDO::PARAM_INT);
    
    if ($stmt->execute()) {
        header("Location: ../../admin/menu/index.php?success=deleted");
    } else {
        header("Location: ../../admin/menu/index.php?error=delete_failed");
    }
} catch (PDOException $e) {
    
    echo "Error: " . $e->getMessage();
}