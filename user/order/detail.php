<?php
session_start();
require_once '../../includes/conn.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'member') {
    header("Location: ../../login.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: history.php");
    exit();
}

$order_id = $_GET['id'];
$user_id = $_SESSION['user_id'];

// Cek kepemilikan order
$stmt = $conn->prepare("SELECT * FROM orders WHERE id = :order_id AND users_id = :user_id");
$stmt->execute(['order_id' => $order_id, 'user_id' => $user_id]);
$order = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$order) {
    echo "Pesanan tidak ditemukan atau Anda tidak memiliki akses.";
    exit();
}

$stmtItems = $conn->prepare("SELECT oi.quantity, oi.subtotal, m.name, m.price
                            FROM orders_items oi
                            JOIN menus m ON oi.menus_id = m.id
                            WHERE oi.orders_id = :order_id");
$stmtItems->execute(['order_id' => $order_id]);
$items = $stmtItems->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Detail Pesanan</title>
    <link rel="stylesheet" href="../../assets/style.css">
</head>
<body>
    <h2>Detail Pesanan #<?= htmlspecialchars($order_id) ?></h2>
    <p><strong>Status:</strong> <?= ucfirst(htmlspecialchars($order['status'])) ?></p>
    <p><strong>Metode Pembayaran:</strong> <?= htmlspecialchars($order['payment_method']) ?></p>
    <p><strong>Tipe Layanan:</strong> <?= htmlspecialchars($order['service_type']) ?></p>
    <p><strong>Total Harga:</strong> Rp<?= number_format($order['total_price'], 0, ',', '.') ?></p>

    <table border="1" cellpadding="8" cellspacing="0">
        <thead>
            <tr>
                <th>Menu</th>
                <th>Harga</th>
                <th>Jumlah</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($items as $item): ?>
                <tr>
                    <td><?= htmlspecialchars($item['name']) ?></td>
                    <td>Rp<?= number_format($item['price'], 0, ',', '.') ?></td>
                    <td><?= $item['quantity'] ?></td>
                    <td>Rp<?= number_format($item['subtotal'], 0, ',', '.') ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <br>
    <a href="history.php">Kembali ke Riwayat</a>
</body>
</html>
