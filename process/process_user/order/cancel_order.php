<?php
// cancel_order.php
session_start();
require_once '../../includes/conn.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'member') {
    header("Location: ../../login.php");
    exit();
}

if (isset($_GET['id'])) {
    $order_id = $_GET['id'];
    $user_id = $_SESSION['user_id'];

    // Pastikan order milik user dan belum diproses
    $stmt = $conn->prepare("SELECT * FROM orders WHERE id = ? AND users_id = ? AND status = 'pending'");
    $stmt->execute([$order_id, $user_id]);
    $order = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($order) {
        $stmt = $conn->prepare("UPDATE orders SET status = 'canceled' WHERE id = ?");
        $stmt->execute([$order_id]);
        header("Location: ../order/history.php?cancel=success");
        exit();
    } else {
        header("Location: ../order/history.php?cancel=failed");
        exit();
    }
} else {
    header("Location: ../order/history.php");
    exit();
}
?>
