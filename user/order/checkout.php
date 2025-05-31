<?php
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
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Checkout - Sambal Belut</title>
    <link href="../../src/output.css" rel="stylesheet">
</head>
<body class="bg-gray-100 min-h-screen p-6">

    <div class="max-w-4xl mx-auto mt-8 bg-white p-6 rounded shadow">
        <h2 class="text-2xl font-bold mb-6 text-center text-red-600">Checkout Pesanan Anda</h2>

        <div class="overflow-x-auto mb-6">
            <table class="min-w-full table-auto border border-gray-200 rounded">
                <thead>
                    <tr class="bg-red-600 text-white">
                        <th class="px-4 py-2 text-left">Menu</th>
                        <th class="px-4 py-2 text-right">Harga</th>
                        <th class="px-4 py-2 text-center">Qty</th>
                        <th class="px-4 py-2 text-right">Subtotal</th>
                    </tr>
                </thead>
                <tbody class="bg-white">
                    <?php foreach ($cart_items as $item): ?>
                        <tr class="border-t">
                            <td class="px-4 py-2"><?= htmlspecialchars($item['name']) ?></td>
                            <td class="px-4 py-2 text-right">Rp <?= number_format($item['price']) ?></td>
                            <td class="px-4 py-2 text-center"><?= $item['quantity'] ?></td>
                            <td class="px-4 py-2 text-right">Rp <?= number_format($item['subtotal']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                    <tr class="bg-gray-100 font-semibold border-t">
                        <td colspan="3" class="px-4 py-3 text-right">Total</td>
                        <td class="px-4 py-3 text-right">Rp <?= number_format($total_price) ?></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <form action="process/process_order.php" method="post" class="space-y-4">
            <input type="hidden" name="total_price" value="<?= $total_price ?>">

            <div>
                <label for="payment_method" class="block text-sm font-medium text-gray-700">Metode Pembayaran</label>
                <select name="payment_method" id="payment_method" required class="mt-1 block w-full rounded border-gray-300 shadow-sm focus:ring-red-500 focus:border-red-500">
                    <option value="tunai">Tunai</option>
                    <option value="qris">QRIS</option>
                </select>
            </div>

            <div>
                <label for="service_type" class="block text-sm font-medium text-gray-700">Jenis Layanan</label>
                <select name="service_type" id="service_type" required class="mt-1 block w-full rounded border-gray-300 shadow-sm focus:ring-red-500 focus:border-red-500">
                    <option value="dine-in">Makan di Tempat</option>
                    <option value="take-away">Bungkus</option>
                </select>
            </div>

            <div class="flex justify-end space-x-3">
                <a href="../menu/index.php" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold py-2 px-4 rounded">Kembali</a>
                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-semibold py-2 px-6 rounded">Konfirmasi Pesanan</button>
            </div>
        </form>
    </div>

</body>
</html>

