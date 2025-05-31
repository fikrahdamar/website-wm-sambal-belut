<?php
// process_order.php
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

        // Simpan order ke tabel orders
        $stmt = $conn->prepare("INSERT INTO orders (users_id, status, payment_method, total_price, service_type) VALUES (?, 'pending', ?, ?, ?)");
        $stmt->execute([$user_id, $payment_method, $total_price, $service_type]);
        $order_id = $conn->lastInsertId();

        // Simpan detail item pesanan
        $stmtItem = $conn->prepare("INSERT INTO orders_items (orders_id, menus_id, quantity, subtotal) VALUES (?, ?, ?, ?)");
        foreach ($cart as $item) {
            $subtotal = $item['price'] * $item['qty'];
            $stmtItem->execute([$order_id, $item['id'], $item['qty'], $subtotal]);
        }

        // Jika user login, tambahkan poin
        if ($user_id) {
            $earned_points = floor($total_price / 5000) * 5;
            if ($earned_points > 0) {
                $stmtPoint = $conn->prepare("UPDATE users SET point = point + ? WHERE id = ?");
                $stmtPoint->execute([$earned_points, $user_id]);
            }
        }

        // Bersihkan keranjang
        unset($_SESSION['cart']);
        $conn->commit();

        // Redirect sesuai role
        if ($user_id) {
            header("Location: ../../../user/order/history.php?success=1");
        } else {
            header("Location: ../../../guest/order/success.php"); // kamu bisa buat halaman sukses guest di sini
        }
        exit();
    } catch (Exception $e) {
        $conn->rollBack();
        echo "Terjadi kesalahan: " . $e->getMessage();
    }
}
?>
