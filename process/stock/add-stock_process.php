<?php
session_start();
require_once '../../includes/conn.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../../login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $menus_id = $_POST['menus_id'] ?? null;
    $stocks_name = trim($_POST['stocks_name'] ?? '');
    $stock_in = $_POST['stock_in'] ?? 0;
    $stock_out = $_POST['stock_out'] ?? 0;

    if ($menus_id && $stocks_name !== '') {
        $stmt = $conn->prepare("INSERT INTO stocks (menus_id, stocks_name, stock_in, stock_out) VALUES (?, ?, ?, ?)");
        $stmt->execute([$menus_id, $stocks_name, $stock_in, $stock_out]);

        header("Location: ../../admin/stock/index.php?success=added");
        exit();
    } else {
        header("Location: ../../admin/stock/add.php?error=invalid_input");
        exit();
    }
} else {
    header("Location: ../../admin/stock/index.php");
    exit();
}
