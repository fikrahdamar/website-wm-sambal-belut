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
    <link rel="stylesheet" href="../../assets/style.css">
</head>
<body>
    <h2>Riwayat Pesanan Anda</h2>
    <a href="../../menu">Kembali ke Menu</a>
    <table border="1" cellpadding="8" cellspacing="0">
        <thead>
            <tr>
                <th>ID Pesanan</th>
                <th>Tanggal</th>
                <th>Status</th>
                <th>Total Harga</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($orders) > 0): ?>
                <?php foreach ($orders as $order): ?>
                    <tr>
                        <td><?= htmlspecialchars($order['id']) ?></td>
                        <td><?= htmlspecialchars($order['created_at']) ?></td>
                        <td><?= htmlspecialchars(ucfirst($order['status'])) ?></td>
                        <td>Rp<?= number_format($order['total_price'], 0, ',', '.') ?></td>
                        <td><a href="detail.php?id=<?= $order['id'] ?>">Lihat Detail</a></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="5">Belum ada pesanan.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>
