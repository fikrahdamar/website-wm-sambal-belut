<?php
session_start();
require_once '../../includes/conn.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../../login.php");
    exit();
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $stmt = $conn->prepare("DELETE FROM stocks WHERE id = ?");
    $stmt->execute([$id]);

    header("Location: ../../admin/stock/index.php?success=deleted");
    exit();
} else {
    header("Location: ../../admin/stock/index.php?error=invalid_id");
    exit();
}
