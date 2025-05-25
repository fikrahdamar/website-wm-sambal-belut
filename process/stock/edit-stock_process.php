<?php
session_start();
require_once '../../includes/conn.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../../login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? null;
    $menus_id = $_POST['menus_id'] ?? null;
    $stocks_name = trim($_POST['stocks_name'] ?? '');
    $stock_in = $_POST['stock_in'] ?? 0;
    $stock_out = $_POST['stock_out'] ?? 0;

    if ($id && $menus_id && $stocks_name !== '') {
        $stmt = $conn->prepare("UPDATE stocks SET menus_id = ?, stocks_name = ?, stock_in = ?, stock_out = ? WHERE id = ?");
        $stmt->execute([$menus_id, $stocks_name, $stock_in, $stock_out, $id]);

        header("Location: ../../admin/stock/index.php?success=edited");
        exit();
    } else {
        header("Location: ../../admin/stock/edit.php?id=" . urlencode($id) . "&error=invalid_input");
        exit();
    }
} else {
    header("Location: ../../admin/stock/index.php");
    exit();
}
