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
$stmt = $conn->prepare("SELECT o.*, IFNULL(u.users_name, 'GUEST') AS username  
                       FROM orders o 
                       LEFT JOIN users u ON o.users_id = u.id 
                       WHERE o.id = :order_id");
$stmt->execute(['order_id' => $order_id]);
$order = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$order) {
    die("Pesanan tidak ditemukan.");
}

$itemStmt = $conn->prepare("SELECT oi.quantity, m.name, m.price, o.payment_method  FROM orders_items oi
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
<body class="bg-gray-100 min-h-screen flex items-center justify-center p-4">
  <div class="bg-white shadow-xl rounded-xl w-full max-w-xl p-6 relative">

    <!-- Judul dan ikon -->
    <div class="flex justify-between items-start mb-4">
      <h1 class="text-xl font-bold">Detail Pesanan</h1>
      <a href="index.php" class="text-gray-400 hover:text-gray-700">&times;</a>
    </div>

    <!-- Info utama -->
    <div class="grid grid-cols-2 gap-x-4 gap-y-3 text-sm mb-6">
      <p><span class="font-semibold">ID Pesanan:</span> <?= $order['id'] ?></p>
      <p><span class="font-semibold">Username:</span> <?= htmlspecialchars($order['username']) ?></p>
      <p>
        <span class="font-semibold">Status:</span>
        <span class=" rounded-2xl inline-block px-2 py-1 text-xs text-white bg-blue-500 "><?= $order['status'] ?></span>
      </p>
      <p><span class="font-semibold">Jenis Layanan:</span> <?= $order['service_type'] ?></p>
      <p><span class="font-semibold">Total:</span> Rp<?= number_format($order['total_price'], 0, ',', '.') ?></p>
      <p><span class="font-semibold">Dibuat pada:</span> <?= $order['created_at'] ?></p>
    </div>

    
    <div class="mb-4">
      <h2 class="text-md font-semibold mb-2">Item dalam Pesanan:</h2>
      <ul class="space-y-3">
        <?php foreach ($items as $item): ?>
          <li class="border rounded-lg px-4 py-3 mb-2 bg-gray-50 flex flex-col md:flex-row md:items-center md:justify-between">
            <div class="flex-1 mb-2 md:mb-0">
              <p class="font-medium"><?= htmlspecialchars($item['name']) ?></p>
              <p class="text-sm text-gray-500">x<?= $item['quantity'] ?> · Rp<?= number_format($item['price'], 0, ',', '.') ?></p>
            </div>
            <div>
              <span class="inline-block bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full"><?= htmlspecialchars($item['payment_method']) ?></span>
            </div>
          </li>
        <?php endforeach; ?>
      </ul>
    </div>

    <!-- Tombol -->
    <div class="text-right">
      <a href="index.php" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded shadow">Kembali</a>
    </div>
  </div>
</body>
</html>
