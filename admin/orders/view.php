<?php

session_start();
require_once '../../includes/conn.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../../login.php");
    exit();
}

if (!isset($_GET['id'])) {
    die("ID pesanan tidak ditemukan.");
}

$order_id = (int)$_GET['id'];

// Ambil data utama pesanan
$stmt = $pdo->prepare("SELECT o.*, IFNULL(u.users_name, 'GUEST') AS username  
                       FROM orders o 
                       JOIN users u ON o.user_id = u.id 
                       WHERE o.id = :order_id");
$stmt->execute(['order_id' => $order_id]);
$order = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$order) {
    die("Pesanan tidak ditemukan.");
}

$itemStmt = $pdo->prepare("SELECT oi.id, oi.quantity, o.total_price, o.service_type, o.created_at, 
                            o.status, m.name, m.price, o.payment_method  FROM orders_item oi
                            JOIN  orders o ON oi.orders_id = o.id
                            JOIN menus m ON oi.menus_id = m.id
                            WHERE orders_id = :order_id");
$itemStmt->execute(['order_id' => $order_id]);
$items = $itemStmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Pesanan</title>
    <link href="../../src/output.css" rel="stylesheet">
</head>
<body class="bg-gray-100 p-6">
    <div class="max-w-2xl mx-auto bg-white shadow p-6 rounded">
        <h1 class="text-2xl font-bold mb-4">Detail Pesanan</h1>

        <div class="mb-4">
            <p><strong>ID Pesanan:</strong> <?= $items['id'] ?></p>
            <p><strong>Username:</strong> <?= htmlspecialchars($order['username']) ?></p>
            <p><strong>Status:</strong> <?= $items['status'] ?></p>
            <p><strong>Jenis Layanan:</strong> <?= $items['service_type'] ?></p>
            <p><strong>Total:</strong> Rp<?= number_format($items['total_price'], 0, ',', '.') ?></p>
            <p><strong>Dibuat pada:</strong> <?= $items['created_at'] ?></p>
        </div>

        <h2 class="text-xl font-semibold mb-2">Item dalam Pesanan:</h2>
        <table class="min-w-full table-auto border">
            <thead class="bg-gray-200">
                <tr>
                    <th class="border px-4 py-2">Nama Menu</th>
                    <th class="border px-4 py-2">Harga</th>
                    <th class="border px-4 py-2">Metode Pembayaran</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($items as $item): ?>
                <tr>
                    <td class="border px-4 py-2"><?= htmlspecialchars($item['name']) ?></td>
                    <td class="border px-4 py-2"><?= $item['quantity'] ?></td>
                    <td class="border px-4 py-2">Rp<?= number_format($item['price'], 0, ',', '.') ?></td>
                    <td class="border px-4 py-2"><?= htmlspecialchars($item['payment_method']) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="mt-6">
            <a href="index.php" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">Kembali</a>
        </div>
    </div>
</body>
</html>
