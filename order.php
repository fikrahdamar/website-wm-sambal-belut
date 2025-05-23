<?php
session_start();
// include '../includes/conn.php';

$user_id = $_SESSION['user_id'] ?? null;
$cart = $_SESSION['cart'] ?? [];

if (empty($cart)) {
    echo "Keranjang kosong.";
    exit;
}

$ids = implode(',', array_keys($cart));
$sql = "SELECT * FROM menus WHERE id IN ($ids)";
$result = mysqli_query($conn, $sql);
$total = 0;
?>

<h2>Konfirmasi Pesanan</h2>
<form action="../process/order_process.php" method="POST">
    <table>
        <tr><th>Menu</th><th>Jumlah</th><th>Harga</th><th>Subtotal</th></tr>
        <?php while ($row = mysqli_fetch_assoc($result)): 
            $qty = $cart[$row['id']];
            $subtotal = $qty * $row['price'];
            $total += $subtotal;
        ?>
        <tr>
            <td><?= htmlspecialchars($row['name']) ?></td>
            <td><?= $qty ?></td>
            <td>Rp<?= $row['price'] ?></td>
            <td>Rp<?= $subtotal ?></td>
        </tr>
        <?php endwhile; ?>
    </table>

    <p><strong>Total: Rp<?= $total ?></strong></p>

    <label>Metode Pembayaran:</label>
    <select name="payment_method" required>
        <option value="tunai">Tunai</option>
        <option value="qris">QRIS</option>
    </select><br>

    <label>Layanan:</label>
    <select name="service_type" required>
        <option value="dine-in">Dine-In</option>
        <option value="take-away">Take-Away</option>
    </select><br>

    <input type="hidden" name="user_id" value="<?= $user_id ?>">
    <button type="submit">Pesan Sekarang</button>
</form>
