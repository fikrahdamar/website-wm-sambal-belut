<?php
session_start();
require_once '../../includes/conn.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

try {
    $stmt = $conn->prepare("SELECT id, users_name, `point` FROM users WHERE role = 'member'");
    $stmt->execute();
    $members = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $members = [];
    $error = "Gagal mengambil data member: " . htmlspecialchars($e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar Member - Admin Panel</title>
    <link href="../../src/output.css" rel="stylesheet">
</head>
<body class="bg-gray-100 font-sans">
    <nav class="bg-white border-b border-gray-200 shadow px-6 py-4">
        <div class="flex items-center justify-between">
            <div class="text-2xl font-bold text-gray-800">
                <a href="./dashboard.php">🍽️ Sambal Belut</a><span class="text-sm font-bold text-gray-500 pl-5">Admin Panel</span>
            </div>
            <ul class="flex items-center space-x-6 text-sm font-medium">
                <li><a href="../orders/index.php" class="navbar-teks-admin">Pesanan</a></li>
                <li><a href="../menu/index.php" class="navbar-teks-admin">Menu</a></li>
                <li><a href="../category/index.php" class="navbar-teks-admin">Kategori</a></li>
                <li><a href="../stock/index.php" class="navbar-teks-admin">Stok</a></li>
                <li><a href="./members.php" class="navbar-teks-admin text-blue-600 font-bold">Member</a></li>
                <li><a href="./transactions.php" class="navbar-teks-admin">Riwayat Pembayaran</a></li>
                <li><a href="../../logout.php" class="text-red-600 hover:text-red-800">Logout</a></li>
            </ul>
        </div>
    </nav>

    <div class="p-6">
        <div class="bg-white rounded-lg shadow p-6">
            <h1 class="text-xl font-semibold text-gray-800 mb-4">Daftar Member</h1>

            <?php if (isset($error)): ?>
                <div class="mb-4 px-4 py-3 rounded bg-red-100 text-red-800 border border-red-400">
                    ❌ <?= $error ?>
                </div>
            <?php endif; ?>

            <?php if (isset($_GET['deleted'])): ?>
                <?php if ($_GET['deleted'] === 'success'): ?>
                    <div class="mb-4 px-4 py-3 rounded bg-red-100 text-red-800 border">
                        🗑️ Member berhasil dihapus!
                    </div>
                <?php elseif ($_GET['deleted'] === 'edited'): ?>
                    <div class="mb-4 px-4 py-3 rounded bg-blue-100 text-blue-800 border">
                        ✏️ Member berhasil diedit!
                    </div>
                <?php endif; ?>
            <?php endif; ?>

            <div class="overflow-x-auto">
                <table class="min-w-full text-sm text-left text-gray-600">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                        <tr>
                            <th class="px-4 py-2">Nama</th>
                            <th class="px-4 py-2">Poin</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php if ($members): ?>
                            <?php foreach ($members as $member): ?>
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-2"><?= htmlspecialchars($member['users_name']) ?></td>
                                    <td class="px-4 py-2"><?= htmlspecialchars($member['point']) ?></td>
                                    <td class="px-4 py-2 text-right space-x-2">
                                        <a href="edit.php?id=<?= $member['id'] ?>" class="edit-admin-button">Edit</a>
                                        <a href="../../process/membership/delete-membership_process.php?id=<?= $member['id'] ?>" onclick="return confirm('Yakin ingin menghapus menu ini?')" class="delete-admin-button">Hapus</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td class="px-4 py-2 text-center text-gray-500" colspan="2">Belum ada member yang terdaftar.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
