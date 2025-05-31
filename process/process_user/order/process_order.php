<?php
// process_order.php
session_start();
require_once '../../includes/conn.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'member') {
    header("Location: ../../login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $payment_method = $_POST['payment_method'] ?? 'tunai';
    $service_type = $_POST['service_type'] ?? 'dine-in';
    $cart = $_SESSION['cart'] ?? [];

    if (empty($cart)) {
        header("Location: ../menu/index.php");
        exit();
    }

    $conn->beginTransaction();
    try {
        $total_price = 0;
        foreach ($cart as $item) {
            $total_price += $item['price'] * $item['quantity'];
        }

        $stmt = $conn->prepare("INSERT INTO orders (users_id, status, payment_method, total_price, service_type) VALUES (?, 'pending', ?, ?, ?)");
        $stmt->execute([$user_id, $payment_method, $total_price, $service_type]);
        $order_id = $conn->lastInsertId();

        $stmtItem = $conn->prepare("INSERT INTO orders_items (orders_id, menus_id, quantity, subtotal) VALUES (?, ?, ?, ?)");
        foreach ($cart as $item) {
            $subtotal = $item['price'] * $item['quantity'];
            $stmtItem->execute([$order_id, $item['id'], $item['quantity'], $subtotal]);
        }

        unset($_SESSION['cart']);
        $conn->commit();

        header("Location: ../order/history.php?success=1");
        exit();
    } catch (Exception $e) {
        $conn->rollBack();
        echo "Terjadi kesalahan: " . $e->getMessage();
    }
}
?>
