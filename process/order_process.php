<?php
session_start();


$user_id = $_POST['user_id'] ?? null;
$payment_method = $_POST['payment_method'];
$service_type = $_POST['service_type'];
$cart = $_SESSION['cart'] ?? [];

if (empty($cart)) {
    die("Keranjang kosong.");
}

$total_price = 0;


$query = "INSERT INTO orders (users_id, status, payment_method, total_price, service_type) 
          VALUES (?, 'pending', ?, 0, ?)";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "iss", $user_id, $payment_method, $service_type);
mysqli_stmt_execute($stmt);
$order_id = mysqli_insert_id($conn);


foreach ($cart as $menu_id => $qty) {
    $menu_result = mysqli_query($conn, "SELECT price FROM menus WHERE id = $menu_id");
    $menu = mysqli_fetch_assoc($menu_result);
    $subtotal = $menu['price'] * $qty;
    $total_price += $subtotal;

    mysqli_query($conn, "INSERT INTO orders_items (orders_id, menus_id, quantity, subtotal) 
                         VALUES ($order_id, $menu_id, $qty, $subtotal)");
}


mysqli_query($conn, "UPDATE orders SET total_price = $total_price WHERE id = $order_id");

if ($user_id) {
    $point = floor($total_price / 10000);
    mysqli_query($conn, "UPDATE users SET point = point + $point WHERE id = $user_id");
}


unset($_SESSION['cart']);
header("Location: ../src/order_history.php");
exit;
