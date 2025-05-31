<?php
// update_status.php
session_start();
require_once '../../includes/conn.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'member') {
    header("Location: ../../login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $order_id = $_POST['order_id'] ?? null;
    $new_status = $_POST['status'] ?? null;
    $user_id = $_SESSION['user_id'];

    if (!$order_id || !$new_status) {
        header("Location: ../order/history.php?update=failed");
        exit();
    }

    // Validasi status baru
    $allowed_status = ['pending', 'process', 'completed', 'canceled'];
    if (!in_array($new_status, $allowed_status)) {
        header("Location: ../order/history.php?update=invalid_status");
        exit();
    }

    $stmt = $conn->prepare("SELECT * FROM orders WHERE id = ? AND users_id = ?");
    $stmt->execute([$order_id, $user_id]);
    $order = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($order) {
        $stmt = $conn->prepare("UPDATE orders SET status = ? WHERE id = ?");
        $stmt->execute([$new_status, $order_id]);
        header("Location: ../order/history.php?update=success");
        exit();
    } else {
        header("Location: ../order/history.php?update=not_found");
        exit();
    }
} else {
    header("Location: ../order/history.php");
    exit();
}
?>
