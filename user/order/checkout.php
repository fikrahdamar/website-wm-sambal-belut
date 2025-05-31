<?php
// checkout.php
session_start();
require_once '../../includes/conn.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'member') {
    header("Location: ../../login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$cart = $_SESSION['cart'] ?? [];

if (empty($cart)) {
    echo "<p>Keranjang Anda kosong. <a href='../menu/index.php'>Kembali ke menu</a></p>";
    exit();
}

// Hitung total
$total_price = 0;
$cart_items = [];
foreach ($cart as $menu_id => $item) {
    $quantity = $item['qty'];
    $stmt = $conn->prepare("SELECT id, name, price FROM menus WHERE id = ? AND available = 1");
    $stmt->execute([$menu_id]);
    $menu = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($menu) {
        $subtotal = $menu['price'] * $quantity;
        $total_price += $subtotal;
        $cart_items[] = [
            'id' => $menu['id'],
            'name' => $menu['name'],
            'price' => $menu['price'],
            'quantity' => $quantity,
            'subtotal' => $subtotal
        ];
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Checkout - Sambal Belut</title>
    <link rel="stylesheet" href="../../assets/style.css">
</head>
<body>
    <h2>Checkout</h2>
    <table border="1" cellpadding="10">
        <tr>
            <th>Menu</th>
            <th>Harga</th>
            <th>Qty</th>
            <th>Subtotal</th>
        </tr>
        <?php foreach ($cart_items as $item): ?>
            <tr>
                <td><?= htmlspecialchars($item['name']) ?></td>
                <td>Rp <?= number_format($item['price']) ?></td>
                <td><?= $item['quantity'] ?></td>
                <td>Rp <?= number_format($item['subtotal']) ?></td>
            </tr>
        <?php endforeach; ?>
        <tr>
            <td colspan="3"><strong>Total</strong></td>
            <td><strong>Rp <?= number_format($total_price) ?></strong></td>
        </tr>
    </table>

    <form action="process/process_order.php" method="post">
        <input type="hidden" name="total_price" value="<?= $total_price ?>">
        <p>
            <label>Metode Pembayaran:</label><br>
            <select name="payment_method" required>
                <option value="tunai">Tunai</option>
                <option value="qris">QRIS</option>
            </select>
        </p>
        <p>
            <label>Jenis Layanan:</label><br>
            <select name="service_type" required>
                <option value="dine-in">Makan di Tempat</option>
                <option value="take-away">Bungkus</option>
            </select>
        </p>
        <button type="submit">Konfirmasi Pesanan</button>
    </form>
</body>
</html>
