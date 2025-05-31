<?php
session_start();
$cart = $_SESSION['cart_snapshot'] ?? [];
$order_id = $_SESSION['guest_order_id'] ?? null;
$total = 0;
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Pesanan Berhasil</title>
     <link href="../../src/output.css" rel="stylesheet">
</head>
<body class="bg-gray-100 text-gray-800">

<div class="max-w-xl mx-auto mt-10 bg-white shadow-lg rounded-lg p-6">
    <h1 class="text-2xl font-bold text-green-600 mb-4">Pesanan Berhasil!</h1>
    <p class="mb-4">Terima kasih telah memesan di Warung Makan Kami 🎉</p>
     <?php if ($order_id): ?>
        <p class="mb-4 text-sm text-gray-600">ID Pesanan Anda: <span class="font-semibold">#<?= htmlspecialchars($order_id) ?></span></p>
    <?php endif; ?>
    <h2 class="text-lg font-semibold mb-2">Detail Pesanan:</h2>
    <div class="mb-4">
        <?php if (!empty($cart)): ?>
            <ul class="divide-y divide-gray-200">
                <?php foreach ($cart as $item): ?>
                    <?php $subtotal = $item['price'] * $item['qty']; $total += $subtotal; ?>
                    <li class="py-2 flex justify-between">
                        <span><?= htmlspecialchars($item['name']) ?> x <?= $item['qty'] ?></span>
                        <span>Rp <?= number_format($subtotal, 0, ',', '.') ?></span>
                    </li>
                <?php endforeach; ?>
            </ul>
            <div class="mt-4 font-bold flex justify-between">
                <span>Total</span>
                <span>Rp <?= number_format($total, 0, ',', '.') ?></span>
            </div>
        <?php else: ?>
            <p class="text-red-500">Tidak ada data pesanan.</p>
        <?php endif; ?>
    </div>

    <a href="../../user/menu.php" class="mt-6 inline-block bg-stone-400 hover:bg-green-700 text-white px-4 py-2 rounded">
        Kembali ke Menu
    </a>
</div>

</body>
</html>
