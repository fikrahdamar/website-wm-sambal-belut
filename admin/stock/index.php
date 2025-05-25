<?php
session_start();
require_once '../../includes/conn.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../../login.php");
    exit();
}

$sql = "SELECT stocks.id, menus.name as menus_name, stocks.stocks_name, stocks.stock_in, stocks.stock_out, stocks.current_stock
        FROM stocks 
        JOIN menus ON stocks.menus_id = menus.id 
        ORDER BY menus.id ASC";
$stmt = $conn->query($sql);
$stocks = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stock Menu</title>
    <link href="../../src/output.css" rel="stylesheet">
</head>
<body class="bg-gray-100">

    <nav class="bg-white border-b border-gray-200 shadow px-6 py-4">
        <div class="flex items-center justify-between">
            <div class="text-2xl font-bold text-gray-800">
                <a href="../dashboard.php">🍽️ Sambal Belut</a>
                <span class="text-sm font-bold text-gray-500 pl-5">Admin Panel</span>
            </div>
            <ul class="flex items-center space-x-6 text-sm font-medium">
                <li><a href="../orders/index.php" class="navbar-teks-admin">Pesanan</a></li>
                <li><a href="../menu/index.php" class="navbar-teks-admin">Menu</a></li>
                <li><a href="../category/index.php" class="navbar-teks-admin ">Kategori</a></li>
                <li><a href=".index.php" class="navbar-teks-admin text-blue-600 font-bold">Stok</a></li>
                <li><a href="../membership/index.php" class="navbar-teks-admin">Member</a></li>
                <li><a href="../transaction/index.php" class="navbar-teks-admin">Riwayat Pembayaran</a></li>
                <li><a href="../../logout.php" class="text-red-600 hover:text-red-800">Logout</a></li>
            </ul>
        </div>
    </nav>

    <div class="p-6">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex justify-between items-center mb-4">
                <h1 class="text-xl font-semibold">Kategori Menu</h1>
                <a href="add.php" class="add-admin-button">Tambah Kategori</a>
            </div>

            <?php if (isset($_GET['success'])): ?>
                <?php if ($_GET['success'] === 'deleted'): ?>
                    <div class="mb-4 px-4 py-3 rounded bg-red-100 text-red-800 border">
                        🗑️ Stok berhasil dihapus!
                    </div>
                <?php elseif ($_GET['success'] === 'added'): ?>
                    <div class="mb-4 px-4 py-3 rounded bg-green-100 text-green-800 border">
                        ✅ Stok berhasil ditambahkan!
                    </div>
                <?php elseif ($_GET['success'] === 'edited'): ?>
                    <div class="mb-4 px-4 py-3 rounded bg-blue-100 text-blue-800 border">
                        ✏️ Stok berhasil diedit!
                    </div>
                <?php endif; ?>
            <?php endif; ?>

            <table class="w-full text-sm text-left text-gray-600">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                    <tr>
                        <th class="px-4 py-2">Menu</th>
                        <th class="px-4 py-2">Nama Stok</th>
                        <th class="px-4 py-2">Stok Masuk</th>
                        <th class="px-4 py-2">Stok Keluar</th>
                        <th class="px-4 py-2">Stok saat ini</th>
                        <th class="px-4 py-2 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php foreach ($stocks as $stock): ?>
                        <tr>
                            <td class="px-4 py-2"><?= htmlspecialchars($stock['menus_name']) ?></td>
                            <td class="px-4 py-2"><?= htmlspecialchars($stock['stocks_name']) ?></td>
                            <td class="px-4 py-2"><?= htmlspecialchars($stock['stock_in']) ?></td>
                            <td class="px-4 py-2"><?= htmlspecialchars($stock['stock_out']) ?></td>
                            <td class="px-4 py-2"><?= htmlspecialchars($stock['current_stock']) ?></td>
                            <td class="px-4 py-2 text-right space-x-2">
                                <a href="edit.php?id=<?= $stock['id'] ?>" class="edit-admin-button">Edit</a>
                                <a href="../../process/stock/delete-stock_process.php?id=<?= $stock['id'] ?>" onclick="return confirm('Yakin ingin menghapus kategori ini?')" class="delete-admin-button">Hapus</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (empty($stock)): ?>
                        <tr>
                            <td colspan="3" class="text-center py-4 text-gray-500">Belum ada Stock.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</body>
</html>
