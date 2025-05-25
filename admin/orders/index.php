<?php
session_start();
require_once '../../includes/conn.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../../login.php");
    exit();
}

$sql = "SELECT o.id, u.users_name, o.status, o.total_price, o.created_at, o.service_type
        FROM orders o
        LEFT JOIN users u ON o.users_id = u.id
        WHERE o.status = 'pending' OR o.status = 'process'
        ORDER BY o.created_at ASC";

try {
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $orders = [];
    echo "<p class='text-red-500'>Error fetching orders: " . htmlspecialchars($e->getMessage()) . "</p>";
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesanan - Admin Panel</title>
    <link href="../../src/output.css" rel="stylesheet">
</head>
<body class="bg-gray-100 font-sans">
    <nav class="bg-white border-b border-gray-200 shadow px-6 py-4">
        <div class="flex items-center justify-between">
            <!-- Judul -->
            <div class="text-2xl font-bold text-gray-800">
                <a href="../dashboard.php">🍽️ Sambal Belut</a><span class="text-sm font-bold text-gray-500 pl-5">Admin Panel</span>
            </div>

            <!-- Menu Navigasi -->
            <ul class="flex items-center space-x-6 text-sm font-medium">
                <li><a href="./index.php" class="text-blue-600 font-bold">Pesanan</a></li>
                <li><a href="../menu/index.php" class="text-gray-700 hover:text-blue-600">Menu</a></li>
                <li><a href="../category/index.php" class="text-gray-700 hover:text-blue-600">Kategori</a></li>
                <li><a href="../stock/index.php" class="text-gray-700 hover:text-blue-600">Stok</a></li>
                <li><a href="../membership/index.php" class="text-gray-700 hover:text-blue-600">Member</a></li>
                <li><a href="../transactions.php" class="text-gray-700 hover:text-blue-600">Riwayat Pembayaran</a></li>
                <li><a href="../../logout.php" class="text-red-600 hover:text-red-800">Logout</a></li>
            </ul>
        </div>
    </nav>

    <div class="p-6">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex justify-between items-center mb-4">
                <h1 class="text-xl font-semibold text-gray-800">Daftar Pesanan (Pending & Proses)</h1>
            </div>

            <?php if (isset($_GET['status_update'])): ?>
                <?php if ($_GET['status_update'] === 'success'): ?>
                    <div class="mb-4 px-4 py-3 rounded bg-green-100 text-green-800 border border-green-400">
                        ✅ Status pesanan berhasil diperbarui!
                    </div>
                <?php elseif ($_GET['status_update'] === 'error'): ?>
                     <div class="mb-4 px-4 py-3 rounded bg-red-100 text-red-800 border border-red-400">
                        ❌ Gagal memperbarui status pesanan.
                    </div>
                <?php endif; ?>
            <?php endif; ?>

            <div class="overflow-x-auto">
                <table class="min-w-full text-sm text-left text-gray-600">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                        <tr>
                            <th class="px-4 py-2">ID Pesanan</th>
                            <th class="px-4 py-2">Pelanggan</th>
                            <th class="px-4 py-2">Status</th>
                            <th class="px-4 py-2">Total Harga</th>
                            <th class="px-4 py-2">Waktu Pesan</th>
                            <th class="px-4 py-2">Jenis Layanan</th>
                            <th class="px-4 py-2 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php if ($orders): ?>
                            <?php foreach ($orders as $order): ?>
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-2"><?php echo htmlspecialchars($order['id']); ?></td>
                                    <td class="px-4 py-2"><?php echo htmlspecialchars($order['users_name'] ?? 'Guest'); // nampilkan 'Guest' jika users_id NULL ?></td>
                                    <td class="px-4 py-2">
                                        <?php
                                            $status_class = '';
                                            switch ($order['status']) {
                                                case 'pending':
                                                    $status_class = 'text-yellow-600 font-semibold';
                                                    break;
                                                case 'process':
                                                    $status_class = 'text-blue-600 font-semibold';
                                                    break;
                                                case 'completed':
                                                    $status_class = 'text-green-600 font-semibold';
                                                    break;
                                                case 'canceled':
                                                    $status_class = 'text-red-600 font-semibold';
                                                    break;
                                                default:
                                                    $status_class = 'text-gray-600';
                                            }
                                        ?>
                                        <span class="<?= $status_class ?>"><?php echo htmlspecialchars(ucfirst($order['status'])); ?></span>
                                    </td>
                                    <td class="px-4 py-2">Rp<?php echo number_format($order['total_price'], 0, ',', '.'); ?></td>
                                    <td class="px-4 py-2"><?php echo htmlspecialchars($order['created_at']); ?></td>
                                    <td class="px-4 py-2"><?php echo htmlspecialchars(ucfirst($order['service_type'])); ?></td>
                                    <td class="px-4 py-2 text-right space-x-2">
                                        <a href="view.php?id=<?= $order['id'] ?>" class="text-blue-600 hover:underline">Detail</a>

                                        <?php if ($order['status'] === 'pending'): ?>
                                            <a href="../../process/orders/update_status.php?order_id=<?= $order['id'] ?>&status=process"
                                               class="text-green-600 hover:underline">Proses</a>
                                            <a href="../../process/orders/update_status.php?order_id=<?= $order['id'] ?>&status=canceled"
                                               onclick="return confirm('Yakin ingin membatalkan pesanan ini?')"
                                               class="text-red-600 hover:underline">Batal</a>
                                        <?php elseif ($order['status'] === 'process'): ?>
                                             <a href="../../process/orders/update_status.php?order_id=<?= $order['id'] ?>&status=completed"
                                               onclick="return confirm('Yakin ingin menyelesaikan pesanan ini?')"
                                               class="text-green-600 hover:underline">Selesai</a>
                                             <a href="../../process/orders/update_status.php?order_id=<?= $order['id'] ?>&status=canceled"
                                               onclick="return confirm('Yakin ingin membatalkan pesanan ini?')"
                                               class="text-red-600 hover:underline">Batal</a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td class="px-4 py-2 text-center text-gray-500" colspan="7">Tidak ada pesanan yang perlu diproses saat ini.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</body>
</html>
