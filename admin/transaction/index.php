<?php
session_start();
require_once '../../includes/conn.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../../login.php");
    exit();
}

$sql = "SELECT transactions.amount, transactions.payment_method, transactions.created_at
        FROM transactions 
        ORDER BY transactions.created_at DESC";
$stmt = $conn->query($sql);
$transactions = $stmt->fetchAll(PDO::FETCH_ASSOC);

$grouped = [];
foreach ($transactions as $trx) {
    $date = date('Y-m-d', strtotime($trx['created_at']));
    $grouped[$date][] = $trx;
}

$totals = [];
foreach ($grouped as $date => $entries) {
    $total = 0;
    foreach ($entries as $trx) {
        $total += $trx['amount'];
    }
    $totals[$date] = $total;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Riwayat Transaksi</title>
    <link href="../../src/output.css" rel="stylesheet">
</head>
<body class="bg-gray-100">

<nav class="bg-white border-b border-gray-200 shadow px-6 py-4">
    <div class="flex items-center justify-between">
        <div class="text-2xl font-bold text-gray-800">
        <a href="../dashboard.php">🍽️ Sambal Belut</a><span class="text-sm font-bold text-gray-500 pl-5">Admin Panel</span>
        </div>
        <ul class="flex items-center space-x-6 text-sm font-medium">
            <li><a href="../orders/index.php" class="navbar-teks-admin">Pesanan</a></li>
            <li><a href="../menu/index.php" class="navbar-teks-admin">Menu</a></li>
            <li><a href="../category/index.php" class="navbar-teks-admin">Kategori</a></li>
            <li><a href="../stock/index.php" class="navbar-teks-admin">Stok</a></li>
            <li><a href="../membership/index.php" class="navbar-teks-admin">Member</a></li>
            <li><a href="./index.php" class="navbar-teks-admin text-blue-600 font-bold">Riwayat Pembayaran</a></li>
            <li><a href="../../logout.php" class="text-red-600 hover:text-red-800">Logout</a></li>
        </ul>
    </div>
</nav>

<div class="p-6">
    <div class="bg-white rounded-lg shadow p-6">
        <div class="mb-4">
            <h1 class="text-xl font-semibold">Riwayat Transaksi</h1>
        </div>

        <?php if (empty($grouped)): ?>
            <p class="text-center py-4 text-gray-500">Belum ada transaksi.</p>
        <?php else: ?>
            <?php foreach ($grouped as $date => $entries): ?>
                <div class="mb-6">
                    <h2 class="text-md font-bold text-gray-700 mb-2">
                        <?= date('d M Y', strtotime($date)) ?> - Total: Rp <?= number_format($totals[$date], 0, ',', '.') ?>
                    </h2>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left text-gray-600 border border-gray-200">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-100">
                                <tr>
                                    <th class="px-4 py-2">Total</th>
                                    <th class="px-4 py-2">Metode Pembayaran</th>
                                    <th class="px-4 py-2">Waktu</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php foreach ($entries as $entrie): ?>
                                    <tr>
                                        <td class="px-4 py-2">Rp <?= number_format($entrie['amount'], 0, ',', '.') ?></td>
                                        <td class="px-4 py-2"><?= htmlspecialchars($entrie['payment_method']) ?></td>
                                        <td class="px-4 py-2"><?= date('H:i:s', strtotime($entrie['created_at'])) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

</body>
</html>
