<?php
session_start();
require_once '../includes/conn.php';

$user_id = $_POST['user_id'] ?? null;
$payment_method = $_POST['payment_method'];
$service_type = $_POST['service_type'];
$cart = $_SESSION['cart'] ?? [];

if (empty($cart)) {
    die("Keranjang kosong.");
}

$total_price = 0;

try {
    $pdo->beginTransaction();

    $insertOrder = "INSERT INTO orders (users_id, status, payment_method, total_price, service_type) 
                    VALUES (?, 'pending', ?, 0, ?)";
    $stmt = $pdo->prepare($insertOrder);
    $stmt->execute([$user_id, $payment_method, $service_type]);
    $order_id = $pdo->lastInsertId();

    foreach ($cart as $menu_id => $qty) {
        $menuStmt = $pdo->prepare("SELECT price FROM menus WHERE id = ?");
        $menuStmt->execute([$menu_id]);
        $menu = $menuStmt->fetch(PDO::FETCH_ASSOC);

        $subtotal = $menu['price'] * $qty;
        $total_price += $subtotal;

        $itemStmt = $pdo->prepare("INSERT INTO orders_items (orders_id, menus_id, quantity, subtotal) 
                                   VALUES (?, ?, ?, ?)");
        $itemStmt->execute([$order_id, $menu_id, $qty, $subtotal]);
    }

    $updateOrder = $pdo->prepare("UPDATE orders SET total_price = ? WHERE id = ?");
    $updateOrder->execute([$total_price, $order_id]);

    if ($user_id) {
        $point = floor($total_price / 10000);
        $pdo->prepare("UPDATE users SET point = point + ? WHERE id = ?")->execute([$point, $user_id]);
    }

    $pdo->commit();
    unset($_SESSION['cart']);
    header("Location: ../src/order_history.php");
    exit;

} catch (Exception $e) {
    $pdo->rollBack();
    die("Terjadi kesalahan saat memproses pesanan: " . $e->getMessage());
}
