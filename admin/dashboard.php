<?php

session_start();
require_once '../includes/conn.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

// buat fetch datanya dari database

// penjualan harian
$sql_daily_sales = "SELECT DATE(created_at) as sale_date, SUM(total_price) as daily_total
                    FROM orders
                    WHERE status = 'completed'
                    GROUP BY DATE(created_at)
                    ORDER BY sale_date DESC LIMIT 7"; // Get last 7 days
$stmt_daily_sales = $conn->prepare($sql_daily_sales);
$stmt_daily_sales->execute();
$daily_sales_data = $stmt_daily_sales->fetchAll(PDO::FETCH_ASSOC);

// penjualan mingguan
$sql_weekly_sales = "SELECT YEARWEEK(created_at, 1) as sale_week, SUM(total_price) as weekly_total
                     FROM orders
                     WHERE status = 'completed'
                     GROUP BY YEARWEEK(created_at, 1)
                     ORDER BY sale_week DESC LIMIT 4"; // Get last 4 weeks
$stmt_weekly_sales = $conn->prepare($sql_weekly_sales);
$stmt_weekly_sales->execute();
$weekly_sales_data = $stmt_weekly_sales->fetchAll(PDO::FETCH_ASSOC);

// penjualan bulanan
$sql_monthly_sales = "SELECT YEAR(created_at) as sale_year, MONTH(created_at) as sale_month, SUM(total_price) as monthly_total
                      FROM orders
                      WHERE status = 'completed'
                      GROUP BY YEAR(created_at), MONTH(created_at)
                      ORDER BY sale_year DESC, sale_month DESC LIMIT 12"; // Get last 12 months
$stmt_monthly_sales = $conn->prepare($sql_monthly_sales);
$stmt_monthly_sales->execute();
$monthly_sales_data = $stmt_monthly_sales->fetchAll(PDO::FETCH_ASSOC);

// menu terlaris
$sql_best_sellers = "SELECT m.name, SUM(oi.quantity) as total_sold
                     FROM orders_items oi
                     JOIN menus m ON oi.menus_id = m.id
                     JOIN orders o ON oi.orders_id = o.id
                     WHERE o.status = 'completed'
                     GROUP BY m.name
                     ORDER BY total_sold DESC LIMIT 10"; // Top 10 best sellers
$stmt_best_sellers = $conn->prepare($sql_best_sellers);
$stmt_best_sellers->execute();
$best_sellers_data = $stmt_best_sellers->fetchAll(PDO::FETCH_ASSOC);

// jam penjualan terbanyak
$sql_peak_hours = "SELECT HOUR(created_at) as sales_hour, COUNT(*) as total_orders
                   FROM orders
                   WHERE status = 'completed'
                   GROUP BY HOUR(created_at)
                   ORDER BY total_orders DESC LIMIT 5"; // Top 5 peak hours
$stmt_peak_hours = $conn->prepare($sql_peak_hours);
$stmt_peak_hours->execute();
$peak_hours_data = $stmt_peak_hours->fetchAll(PDO::FETCH_ASSOC);

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Warung Sambal Belut</title>
    <link href="../src/output.css" rel="stylesheet">
</head>
<body class="bg-gray-100 font-sans">
    
    <!-- navbar dashboard admin -->
    <nav class="bg-white border-b border-gray-200 shadow px-6 py-4">
        <div class="flex items-center justify-between">
            <!-- Judul -->
            <div class="text-2xl font-bold text-gray-800">
                <a href="./dashboard.php">🍽️ Sambal Belut</a><span class="text-sm font-bold text-gray-500 pl-5">Admin Panel</span>
            </div>

            <!-- Menu Navigasi -->
            <ul class="flex items-center space-x-6 text-sm font-medium">
                <li><a href="./orders/index.php" class="text-gray-700 hover:text-blue-600">Pesanan</a></li>
                <li><a href="./menu/index.php" class="text-gray-700 hover:text-blue-600">Menu</a></li>
                <li><a href="./category/index.php" class="text-gray-700 hover:text-blue-600">Kategori</a></li>
                <li><a href="./stock/index.php" class="text-gray-700 hover:text-blue-600">Stok</a></li>
                <li><a href="./membership/index.php" class="text-gray-700 hover:text-blue-600">Member</a></li>
                <li><a href="./transaction/index.php" class="text-gray-700 hover:text-blue-600">Riwayat Pembayaran</a></li>
                <li><a href="../logout.php" class="text-red-600 hover:text-red-800">Logout</a></li>
            </ul>
        </div>
    </nav>

    <div class="p-6">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">Dashboard Summary</h1>

        <!-- ringkasan penjualan -->
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <h2 class="text-xl font-semibold text-gray-700 mb-4">Ringkasan Penjualan</h2>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <h3 class="text-lg font-medium text-gray-600 mb-2">Penjualan Harian (7 Hari Terakhir)</h3>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left text-gray-600">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                                <tr>
                                    <th class="px-4 py-2">Tanggal</th>
                                    <th class="px-4 py-2">Total Penjualan (IDR)</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php if ($daily_sales_data): ?>
                                    <?php foreach ($daily_sales_data as $sale): ?>
                                        <tr>
                                            <td class="px-4 py-2"><?php echo htmlspecialchars($sale['sale_date']); ?></td>
                                            <td class="px-4 py-2"><?php echo number_format($sale['daily_total'], 0, ',', '.'); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr><td class="px-4 py-2 text-center text-gray-500" colspan="2">Tidak ada data penjualan harian.</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div>
                    <h3 class="text-lg font-medium text-gray-600 mb-2">Penjualan Mingguan (4 Minggu Terakhir)</h3>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left text-gray-600">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                                <tr>
                                    <th class="px-4 py-2">Minggu</th>
                                    <th class="px-4 py-2">Total Penjualan (IDR)</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php if ($weekly_sales_data): ?>
                                    <?php foreach ($weekly_sales_data as $sale): ?>
                                        <tr>
                                            <td class="px-4 py-2"><?php echo htmlspecialchars($sale['sale_week']); ?></td>
                                            <td class="px-4 py-2"><?php echo number_format($sale['weekly_total'], 0, ',', '.'); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr><td class="px-4 py-2 text-center text-gray-500" colspan="2">Tidak ada data penjualan mingguan.</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div>
                    <h3 class="text-lg font-medium text-gray-600 mb-2">Penjualan Bulanan (12 Bulan Terakhir)</h3>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left text-gray-600">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                                <tr>
                                    <th class="px-4 py-2">Bulan/Tahun</th>
                                    <th class="px-4 py-2">Total Penjualan (IDR)</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php if ($monthly_sales_data): ?>
                                    <?php foreach ($monthly_sales_data as $sale): ?>
                                        <tr>
                                            <td class="px-4 py-2"><?php echo htmlspecialchars($sale['sale_month'] . '/' . $sale['sale_year']); ?></td>
                                            <td class="px-4 py-2"><?php echo number_format($sale['monthly_total'], 0, ',', '.'); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr><td class="px-4 py-2 text-center text-gray-500" colspan="2">Tidak ada data penjualan bulanan.</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- menu terlaris -->
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <h2 class="text-xl font-semibold text-gray-700 mb-4">Menu Terlaris</h2>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-600">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                        <tr>
                            <th class="px-4 py-2">Menu Item</th>
                            <th class="px-4 py-2">Total Terjual</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php if ($best_sellers_data): ?>
                            <?php foreach ($best_sellers_data as $item): ?>
                                <tr>
                                    <td class="px-4 py-2"><?php echo htmlspecialchars($item['name']); ?></td>
                                    <td class="px-4 py-2"><?php echo htmlspecialchars($item['total_sold']); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td class="px-4 py-2 text-center text-gray-500" colspan="2">Tidak ada data menu terlaris.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- jam penjualan terbanyak -->
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <h2 class="text-xl font-semibold text-gray-700 mb-4">Jam Penjualan Terbanyak</h2>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-600">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                        <tr>
                            <th class="px-4 py-2">Jam (24h)</th>
                            <th class="px-4 py-2">Total Pesanan</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php if ($peak_hours_data): ?>
                            <?php foreach ($peak_hours_data as $hour_data): ?>
                                <tr>
                                    <td class="px-4 py-2"><?php echo htmlspecialchars(sprintf('%02d:00 - %02d:59', $hour_data['sales_hour'], $hour_data['sales_hour'])); ?></td>
                                    <td class="px-4 py-2"><?php echo htmlspecialchars($hour_data['total_orders']); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td class="px-4 py-2 text-center text-gray-500" colspan="2">Tidak ada data jam penjualan terbanyak.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>

</body>
</html>
