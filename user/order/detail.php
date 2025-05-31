<?php
session_start();
require_once '../../includes/conn.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'member') {
    header("Location: ../../login.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: history.php");
    exit();
}

$order_id = $_GET['id'];
$user_id = $_SESSION['user_id'];

// Cek kepemilikan order
$stmt = $conn->prepare("SELECT * FROM orders WHERE id = :order_id AND users_id = :user_id");
$stmt->execute(['order_id' => $order_id, 'user_id' => $user_id]);
$order = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$order) {
    echo "Pesanan tidak ditemukan atau Anda tidak memiliki akses.";
    exit();
}

$stmtItems = $conn->prepare("SELECT oi.quantity, oi.subtotal, m.name, m.price
                            FROM orders_items oi
                            JOIN menus m ON oi.menus_id = m.id
                            WHERE oi.orders_id = :order_id");
$stmtItems->execute(['order_id' => $order_id]);
$items = $stmtItems->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Detail Pesanan</title>
    <link href="../../src/output.css"> <!-- Tailwind CSS build -->
</head>
<body class="bg-gray-100 min-h-screen p-6">

    <div class="max-w-3xl mx-auto bg-white rounded shadow p-6 mt-6">
        <h2 class="text-2xl font-bold text-red-600 mb-4">Detail Pesanan #<?= htmlspecialchars($order_id) ?></h2>

        <div class="space-y-2 text-sm text-gray-700 mb-6">
            <p><strong>Status:</strong> <span class="capitalize"><?= htmlspecialchars($order['status']) ?></span></p>
            <p><strong>Metode Pembayaran:</strong> <?= htmlspecialchars($order['payment_method']) ?></p>
            <p><strong>Tipe Layanan:</strong> <?= htmlspecialchars($order['service_type']) ?></p>
            <p><strong>Total Harga:</strong> <span class="text-red-600 font-semibold">Rp<?= number_format($order['total_price'], 0, ',', '.') ?></span></p>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full border border-gray-200 rounded overflow-hidden text-sm">
                <thead class="bg-red-600 text-white">
                    <tr>
                        <th class="text-left px-4 py-2">Menu</th>
                        <th class="text-right px-4 py-2">Harga</th>
                        <th class="text-center px-4 py-2">Jumlah</th>
                        <th class="text-right px-4 py-2">Subtotal</th>
                    </tr>
                </thead>
                <tbody class="bg-white">
                    <?php foreach ($items as $item): ?>
                        <tr class="border-t">
                            <td class="px-4 py-2"><?= htmlspecialchars($item['name']) ?></td>
                            <td class="px-4 py-2 text-right">Rp<?= number_format($item['price'], 0, ',', '.') ?></td>
                            <td class="px-4 py-2 text-center"><?= $item['quantity'] ?></td>
                            <td class="px-4 py-2 text-right">Rp<?= number_format($item['subtotal'], 0, ',', '.') ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="mt-6 text-right">
            <a href="history.php" class="inline-block bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold py-2 px-4 rounded">
                ⬅ Kembali ke Riwayat
            </a>
        </div>
    </div>

</body>
</html>

