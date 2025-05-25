<?php
session_start();
require_once '../../includes/conn.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../../login.php");
    exit();
}

// Ambil data menu dan kategori
$sql = "SELECT menus.id, menus.name, menus.description, menus.price, menus.available, menu_categories.menu_categoris_name AS category_name 
        FROM menus 
        JOIN menu_categories ON menus.category_id = menu_categories.id 
        ORDER BY menus.id ASC";
$stmt = $conn->query($sql);
$menus = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu Admin</title>
    <link href="../../src/output.css" rel="stylesheet">
</head>
<body class="bg-gray-100">

    
    <nav class="bg-white border-b border-gray-200 shadow px-6 py-4">
        <div class="flex items-center justify-between">
            <!-- Judul -->
            <div class="text-2xl font-bold text-gray-800">
            <a href="../dashboard.php">🍽️ Sambal Belut</a><span class="text-sm font-bold text-gray-500 pl-5">Admin Panel</span>
            </div>

            <!-- Menu Navigasi -->
            <ul class="flex items-center space-x-6 text-sm font-medium">
                <li><a href="../orders.php" class="navbar-teks-admin">Pesanan</a></li>
                <li><a href="" class="navbar-teks-admin text-blue-600 font-bold">Menu</a></li>
                <li><a href="../category/index.php" class="navbar-teks-admin">Kategori</a></li>
                <li><a href="../stock/index.php" class="navbar-teks-admin">Stok</a></li>
                <li><a href="../members.php" class="navbar-teks-admin">Member</a></li>
                <li><a href="../transactions.php" class="navbar-teks-admin">Riwayat Pembayaran</a></li>
                <li><a href="../../logout.php" class="text-red-600 hover:text-red-800">Logout</a></li>
            </ul>
        </div>
    </nav>

    <div class="p-6">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex justify-between items-center mb-4">
                <h1 class="text-xl font-semibold">Daftar Menu</h1>
                <a href="add.php" class="add-admin-button">Tambah Menu</a>
            </div>

            <?php if (isset($_GET['success'])): ?>
                <?php if ($_GET['success'] === 'deleted'): ?>
                    <div class="mb-4 px-4 py-3 rounded bg-red-100 text-red-800 border">
                        🗑️ Menu berhasil dihapus!
                    </div>
                <?php elseif ($_GET['success'] === 'added'): ?>
                    <div class="mb-4 px-4 py-3 rounded bg-green-100 text-green-800 border">
                        ✅ Menu berhasil ditambahkan!
                    </div>
                <?php elseif ($_GET['success'] === 'edited'): ?>
                    <div class="mb-4 px-4 py-3 rounded bg-blue-100 text-blue-800 border">
                        ✏️ Menu berhasil diedit!
                    </div>
                <?php endif; ?>
            <?php endif; ?>
            

            <table class="w-full text-sm text-left text-gray-600">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                    <tr>
                        <th class="px-4 py-2">Kategori</th>
                        <th class="px-4 py-2">Nama</th>
                        <th class="px-4 py-2">Deskripsi</th>
                        <th class="px-4 py-2">Harga</th>
                        <th class="px-4 py-2">Status</th>
                        <th class="px-4 py-2 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php foreach ($menus as $menu): ?>
                        <tr>
                            <td class="px-4 py-2"><?= htmlspecialchars($menu['category_name']) ?></td>
                            <td class="px-4 py-2"><?= htmlspecialchars($menu['name']) ?></td>
                            <td class="px-4 py-2"><?= htmlspecialchars($menu['description']) ?></td>
                            <td class="px-4 py-2">Rp<?= number_format($menu['price'], 0, ',', '.') ?></td>
                            <td class="px-4 py-2">
                                <?php if ($menu['available']): ?>
                                    <span class="text-green-600 font-semibold">Tersedia</span>
                                <?php else: ?>
                                    <span class="text-red-600 font-semibold">Kosong</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-4 py-2 text-right space-x-2">
                                <a href="edit.php?id=<?= $menu['id'] ?>" class="edit-admin-button">Edit</a>
                                <a href="../../process/menu/delete-menu_process.php?id=<?= $menu['id'] ?>" onclick="return confirm('Yakin ingin menghapus menu ini?')" class="delete-admin-button">Hapus</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (empty($menus)): ?>
                        <tr>
                            <td colspan="6" class="text-center py-4 text-gray-500">Tidak ada menu tersedia.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>


</body>
</html>
