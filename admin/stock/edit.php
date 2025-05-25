<?php
session_start();
require_once '../../includes/conn.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../../login.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$id = $_GET['id'];
$stmt = $conn->prepare("SELECT * FROM stocks WHERE id = ?");
$stmt->execute([$id]);
$stock = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$stock) {
    echo "Data stok tidak ditemukan.";
    exit();
}

$menus = $conn->query("SELECT id, name FROM menus ORDER BY name ASC")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Stok Menu</title>
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
            <h2 class="text-xl font-semibold mb-4">Edit Data Stok</h2>

            <form method="post" action="../../process/stock/edit-stock_process.php" class="space-y-4">
                <input type="hidden" name="id" value="<?= htmlspecialchars($stock['id']) ?>">

                <div>
                    <label class="block mb-1 font-medium">Pilih Menu</label>
                    <select name="menus_id" required class="w-full border border-gray-300 rounded px-3 py-2 bg-white">
                        <option value="" disabled>-- Pilih Menu --</option>
                        <?php foreach ($menus as $menu): ?>
                            <option value="<?= $menu['id'] ?>" <?= ($menu['id'] == $stock['menus_id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($menu['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div>
                    <label class="block mb-1 font-medium">Nama Stok</label>
                    <input type="text" name="stocks_name" value="<?= htmlspecialchars($stock['stocks_name']) ?>" required class="w-full border border-gray-300 rounded px-3 py-2">
                </div>

                <div>
                    <label class="block mb-1 font-medium">Stok Masuk</label>
                    <input type="number" name="stock_in" min="0" value="<?= $stock['stock_in'] ?>" required class="w-full border border-gray-300 rounded px-3 py-2">
                </div>

                <div>
                    <label class="block mb-1 font-medium">Stok Keluar</label>
                    <input type="number" name="stock_out" min="0" value="<?= $stock['stock_out'] ?>" required class="w-full border border-gray-300 rounded px-3 py-2">
                </div>

                <div class="pt-4">
                    <button type="submit" class="px-4 bg-green-500 hover:bg-green-600 text-white py-1 rounded mr-4">Simpan Perubahan</button>
                    <a href="index.php" class="ml-8 text-gray-600 hover:underline">Batal</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
