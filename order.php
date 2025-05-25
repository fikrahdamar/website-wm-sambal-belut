<?php
session_start();

$host = 'localhost';
$db = 'warung_makan_sambalbelut';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Koneksi database gagal: " . $e->getMessage());
}

$user_id = $_SESSION['user_id'] ?? null;
$cart = $_SESSION['cart'] ?? [];

if (empty($cart)) {
    echo "Keranjang kosong.";
    exit;
}

// Ambil data menu berdasarkan ID di keranjang
$ids = implode(',', array_keys($cart));
$query = "SELECT * FROM menus WHERE id IN ($ids)";
$stmt = $pdo->query($query);
$menus = $stmt->fetchAll(PDO::FETCH_ASSOC);

$total = 0;
?>

<!DOCTYPE html>
<html>
<head>
    <title>Konfirmasi Pesanan</title>
</head>
<body>
    <h2>Konfirmasi Pesanan</h2>
    <form action="../process/order_process.php" method="POST">
        <table border="1" cellpadding="8" cellspacing="0">
            <tr><th>Menu</th><th>Jumlah</th><th>Harga</th><th>Subtotal</th></tr>
            <?php foreach ($menus as $row): 
                $qty = $cart[$row['id']];
                $subtotal = $qty * $row['price'];
                $total += $subtotal;
            ?>
            <tr>
                <td><?= htmlspecialchars($row['name']) ?></td>
                <td><?= $qty ?></td>
                <td>Rp<?= number_format($row['price'], 0, ',', '.') ?></td>
                <td>Rp<?= number_format($subtotal, 0, ',', '.') ?></td>
            </tr>
            <?php endforeach; ?>
        </table>

        <p><strong>Total: Rp<?= number_format($total, 0, ',', '.') ?></strong></p>

        <label>Metode Pembayaran:</label>
        <select name="payment_method" required>
            <option value="tunai">Tunai</option>
            <option value="qris">QRIS</option>
        </select><br><br>

        <label>Layanan:</label>
        <select name="service_type" required>
            <option value="dine-in">Dine-In</option>
            <option value="take-away">Take-Away</option>
        </select><br><br>

        <input type="hidden" name="user_id" value="<?= $user_id ?>">
        <button type="submit">Pesan Sekarang</button>
    </form>
</body>
</html>
