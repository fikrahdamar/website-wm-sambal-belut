<?php
session_start();

// Tambahkan item ke keranjang
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id = $_POST['menu_id'];
    $name = $_POST['menu_name'];
    $price = $_POST['menu_price'];
    $qty = $_POST['qty'];

    $item = [
        "id" => $id,
        "name" => $name,
        "price" => $price,
        "qty" => $qty,
        "subtotal" => $price * $qty
    ];

    // Kalau item sudah ada, update jumlah dan subtotal
    if (isset($_SESSION['cart'][$id])) {
        $_SESSION['cart'][$id]['qty'] += $qty;
        $_SESSION['cart'][$id]['subtotal'] = $_SESSION['cart'][$id]['qty'] * $price;
    } else {
        $_SESSION['cart'][$id] = $item;
    }
}

// Hapus item
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    unset($_SESSION['cart'][$id]);
}

// Total semua
$total = 0;
if (isset($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
        $total += $item['price'] * $item['qty'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Keranjang Belanja</title>
  <link href="../src/output.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
  <div class="max-w-3xl mx-auto py-10">
    <h1 class="text-3xl font-bold text-center text-gray-800 mb-6">Keranjang Belanja</h1>

    <?php if (!empty($_SESSION['cart'])): ?>
      <table class="w-full table-auto bg-white rounded-lg shadow-md overflow-hidden">
        <thead class="bg-blue-500 text-white">
          <tr>
            <th class="px-4 py-2">Menu</th>
            <th>Harga</th>
            <th>Jumlah</th>
            <th>Subtotal</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody class="text-center">
          <?php foreach ($_SESSION['cart'] as $item): ?>
            <tr class="border-b">
              <td class="px-4 py-2"><?= htmlspecialchars($item['name']) ?></td>
              <td>Rp <?= number_format($item['price'], 0, ',', '.') ?></td>
              <td><?= $item['qty'] ?></td>
              <?php $subtotal = $item['price'] * $item['qty']; ?>
              <td>Rp <?= number_format($subtotal, 0, ',', '.') ?></td>
              <td>
                <a href="cart.php?hapus=<?= $item['id'] ?>"
                   class="text-red-500 hover:underline">Hapus</a>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>

      <div class="text-right mt-4">
        <p class="text-xl font-semibold text-gray-700">Total: <span class="text-blue-600">Rp <?= number_format($total, 0, ',', '.') ?></span></p>
        <a href="./order/checkout.php" class="inline-block mt-4 bg-green-500 hover:bg-green-600 text-white font-semibold px-6 py-2 rounded">
          Checkout
        </a>
      </div>
    <?php else: ?>
      <p class="text-center text-gray-500">Keranjang kamu masih kosong 🛒</p>
    <?php endif; ?>

    <div class="mt-6 text-center">
      <a href="menu.php" class="text-blue-600 hover:underline">← Kembali ke Menu</a>
    </div>
  </div>
</body>
</html>
