<?php
session_start();
require_once '../../includes/conn.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../../login.php");
    exit();
}

$menus = $conn->query("SELECT id, name FROM menus ORDER BY name ASC")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Stok Menu</title>
    <link href="../../src/output.css" rel="stylesheet">
</head>
<body class="bg-gray-100 text-gray-800">
    <nav class="bg-white border-b border-gray-200 shadow-sm px-6 py-4">
        <div class="flex items-center justify-between">
            <div class="text-2xl font-bold text-gray-800">
                📦 Manajemen Stok <span class="text-sm font-normal text-gray-500">Admin Panel</span>
            </div>
            <a href="index.php" class="text-md text-blue-400 hover:underline">← Kembali ke Daftar Stok</a>
        </div>
    </nav>

    <div class="p-6">
        <div class="bg-white shadow rounded-lg p-6 max-w-xl mx-auto">
            <h2 class="text-xl font-semibold mb-4">Tambah Data Stok</h2>

            <form method="post" action="../../process/stock/add-stock_process.php" class="space-y-4">
                <div>
                    <label class="block mb-1 font-medium">Pilih Menu</label>
                    <select name="menus_id" required class="w-full border border-gray-300 rounded px-3 py-2 bg-white">
                        <option value="" disabled selected>-- Pilih Menu --</option>
                        <?php foreach ($menus as $menu): ?>
                            <option value="<?= $menu['id'] ?>"><?= htmlspecialchars($menu['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div>
                    <label class="block mb-1 font-medium">Nama Stok</label>
                    <input type="text" name="stocks_name" required class="w-full border border-gray-300 rounded px-3 py-2">
                </div>

                <div>
                    <label class="block mb-1 font-medium">Stok Masuk</label>
                    <input type="number" name="stock_in" value="0" min="0" required class="w-full border border-gray-300 rounded px-3 py-2">
                </div>

                <div>
                    <label class="block mb-1 font-medium">Stok Keluar</label>
                    <input type="number" name="stock_out" value="0" min="0" required class="w-full border border-gray-300 rounded px-3 py-2">
                </div>

                <div class="pt-4">
                    <button type="submit" class="px-4 bg-blue-500 hover:bg-blue-600 text-white py-1 rounded mr-4">Simpan</button>
                    <a href="index.php" class="ml-8 text-gray-600 hover:underline">Batal</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
