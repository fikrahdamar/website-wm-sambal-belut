<?php
session_start();
require_once '../../../includes/conn.php';


if (isset($_SESSION['user_id']) && $_SESSION['role'] !== 'member') {
    header("Location: ../../login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'] ?? null;
    $payment_method = $_POST['payment_method'] ?? 'tunai';
    $service_type = $_POST['service_type'] ?? 'dine-in';
    $cart = $_SESSION['cart'] ?? [];

    if (empty($cart)) {
        header("Location: ../../../user/menu.php");
        exit();
    }

    $conn->beginTransaction();
    try {
        $total_price = 0;
        foreach ($cart as $item) {
            $total_price += $item['price'] * $item['qty'];
        }

        $stmt = $conn->prepare("INSERT INTO orders (users_id, status, payment_method, total_price, service_type) VALUES (?, 'pending', ?, ?, ?)");
        $stmt->execute([$user_id, $payment_method, $total_price, $service_type]);
        $order_id = $conn->lastInsertId();

        $stmtItem = $conn->prepare("INSERT INTO orders_items (orders_id, menus_id, quantity, subtotal) VALUES (?, ?, ?, ?)");
        foreach ($cart as $item) {
            $menu_id = $item['id'];
            $qty = $item['qty'];
            $subtotal = $item['price'] * $qty;

            $stmtItem->execute([$order_id, $menu_id, $qty, $subtotal]);

            $stmtUpdateStock = $conn->prepare("
                UPDATE stocks 
                SET stock_out = stock_out + ?, 
                    current_stock = stock_in - (stock_out + ?),
                    updated_at = NOW()
                WHERE menus_id = ?
            ");
            $stmtUpdateStock->execute([$qty, $qty, $menu_id]);

            $stmtCheckStock = $conn->prepare("SELECT COUNT(*) FROM stocks WHERE menus_id = ? AND current_stock > 0");
            $stmtCheckStock->execute([$menu_id]);
            $stillAvailable = $stmtCheckStock->fetchColumn();

            if ($stillAvailable == 0) {
                $stmtSetUnavailable = $conn->prepare("UPDATE menus SET available = 0 WHERE id = ?");
                $stmtSetUnavailable->execute([$menu_id]);
            }
        }

        if ($user_id) {
            $earned_points = floor($total_price / 5000) * 5;
            if ($earned_points > 0) {
                $stmtPoint = $conn->prepare("UPDATE users SET point = point + ? WHERE id = ?");
                $stmtPoint->execute([$earned_points, $user_id]);
            }
        }
        $_SESSION['guest_order_id'] = $order_id;
        $_SESSION['cart_snapshot'] = $_SESSION['cart'];
        unset($_SESSION['cart']);
        $conn->commit();

        if ($user_id) {
            header("Location: ../../../user/order/history.php?success=1");
        } else {
            header("Location: ../../../user/guest/success.php");
        }
        exit();

    } catch (Exception $e) {
        $conn->rollBack();
        echo "Terjadi kesalahan: " . $e->getMessage();
    }
}
?>
