<?php
session_start();
require_once '../../includes/conn.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'member') {
    header("Location: ../../login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$sql = "SELECT o.id, o.created_at, o.status, o.total_price
        FROM orders o
        WHERE o.users_id = :user_id
        ORDER BY o.created_at DESC";
$stmt = $conn->prepare($sql);
$stmt->execute(['user_id' => $user_id]);
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Riwayat Pesanan</title>
    <link href="../../src/output.css" rel="stylesheet">
</head>
<body class="bg-gray-100 min-h-screen p-6">

    <div class="max-w-5xl mx-auto bg-white p-6 rounded shadow">
        <h2 class="text-2xl font-bold text-red-600 mb-4 text-center">Riwayat Pesanan Anda</h2>

        <div class="overflow-x-auto">
            <table class="min-w-full border border-gray-200 rounded overflow-hidden text-sm">
                <thead class="bg-red-600 text-white">
                    <tr>
                        <th class="text-left px-4 py-2">ID Pesanan</th>
                        <th class="text-left px-4 py-2">Tanggal</th>
                        <th class="text-left px-4 py-2">Status</th>
                        <th class="text-right px-4 py-2">Total Harga</th>
                        <th class="text-center px-4 py-2">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white">
                    <?php if (count($orders) > 0): ?>
                        <?php foreach ($orders as $order): ?>
                            <tr class="border-t">
                                <td class="px-4 py-2"><?= htmlspecialchars($order['id']) ?></td>
                                <td class="px-4 py-2"><?= htmlspecialchars($order['created_at']) ?></td>
                                <td class="px-4 py-2 capitalize"><?= htmlspecialchars($order['status']) ?></td>
                                <td class="px-4 py-2 text-right">Rp<?= number_format($order['total_price'], 0, ',', '.') ?></td>
                                <td class="px-4 py-2 text-center">
                                    <a href="detail.php?id=<?= $order['id'] ?>" class="text-blue-600 hover:underline">Lihat Detail</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="px-4 py-3 text-center text-gray-500">Belum ada pesanan.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="mt-6 text-right">
            <a href="../menu/index.php" class="inline-block bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold py-2 px-4 rounded">
                ⬅ Kembali ke Menu
            </a>
        </div>
    </div>

</body>
</html>
