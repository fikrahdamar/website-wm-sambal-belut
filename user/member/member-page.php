<?php 
session_start();
require_once '../../includes/conn.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'member') {
    header("location: ../../login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Ambil data user
$sql = "SELECT * FROM users WHERE id = :id AND role = 'member'";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':id', $user_id, PDO::PARAM_INT);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Ambil riwayat order
$orderSql = "SELECT * FROM orders WHERE users_id = :id ORDER BY created_at DESC";
$orderStmt = $conn->prepare($orderSql);
$orderStmt->bindParam(':id', $user_id, PDO::PARAM_INT);
$orderStmt->execute();
$orders = $orderStmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Profil - <?php echo htmlspecialchars($user['users_name']); ?></title>
    <link href="../../src/output.css" rel="stylesheet">
</head>
<body class="bg-gray-100 min-h-screen text-gray-800">

    <!-- Navbar -->
    <nav class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-4 py-3 flex justify-between items-center">
            <div class="text-xl font-semibold text-gray-700">👤 Profil Member</div>
            <a href="../../logout.php" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded">Logout</a>
        </div>
    </nav>

    <main class="max-w-4xl mx-auto mt-10 px-4">
        <!-- Profil Box -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-8">
            <h2 class="text-xl font-bold mb-4">Informasi Akun</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold mb-1">Nama:</label>
                    <div class="bg-gray-100 p-3 rounded"><?php echo htmlspecialchars($user['users_name']); ?></div>
                </div>
                <div>
                    <label class="block text-sm font-semibold mb-1">Points:</label>
                    <div class="bg-gray-100 p-3 rounded"><?php echo number_format($user['point']); ?></div>
                </div>
            </div>
        </div>

        <!-- Riwayat Pembelian -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-bold mb-4">Riwayat Pembelian</h2>
            <?php if (count($orders) > 0): ?>
                <div class="overflow-x-auto">
                    <table class="min-w-full border text-sm text-left">
                        <thead class="bg-gray-200 text-gray-700 font-semibold">
                            <tr>
                                <th class="p-2 border">Tanggal</th>
                                <th class="p-2 border">Layanan</th>
                                <th class="p-2 border">Metode Bayar</th>
                                <th class="p-2 border">Status</th>
                                <th class="p-2 border text-right">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($orders as $order): ?>
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="p-2 border"><?php echo date('d M Y', strtotime($order['created_at'])); ?></td>
                                    <td class="p-2 border"><?php echo htmlspecialchars($order['service_type']); ?></td>
                                    <td class="p-2 border"><?php echo htmlspecialchars($order['payment_method']); ?></td>
                                    <td class="p-2 border"><?php echo htmlspecialchars($order['status']); ?></td>
                                    <td class="p-2 border text-right">Rp<?php echo number_format($order['total_price'], 0, ',', '.'); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <p class="text-gray-500">Belum ada riwayat pembelian.</p>
            <?php endif; ?>
        </div>
    </main>

</body>
</html>
