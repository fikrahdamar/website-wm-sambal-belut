<?php
session_start();
require_once '../../includes/conn.php';

// cek apakah user sudah login dan role nya admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../../login.php");
    exit();
}

// cek apakah order_id dan status ada di url
if (!isset($_GET['order_id']) || !isset($_GET['status'])) {
    // redirect ke halaman orders jika parameter tidak ada
    header("Location: ../../admin/orders/index.php?status_update=error");
    exit();
}

$order_id = intval($_GET['order_id']);
$new_status = $_GET['status'];

// cek apakah status yang dikirim valid
$allowed_statuses = ['process', 'completed', 'canceled'];
if (!in_array($new_status, $allowed_statuses)) {
    // redirect ke halaman orders jika status tidak valid
    header("Location: ../../admin/orders/index.php?status_update=error");
    exit();
}

$sql = "UPDATE orders SET status = :new_status WHERE id = :order_id";

try {
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':new_status', $new_status, PDO::PARAM_STR);
    $stmt->bindParam(':order_id', $order_id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        // redirect ke halaman orders jika berhasil
        header("Location: ../../admin/orders/index.php?status_update=success");
        exit();
    } else {
        // redirect ke halaman orders jika gagal
        header("Location: ../../admin/orders/index.php?status_update=error");
        exit();
    }
} catch (PDOException $e) {
    // log error atau handle itu sendiri
    header("Location: ../../admin/orders/index.php?status_update=error");
    exit();
}

?>
