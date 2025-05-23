<?php
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

$query = "SELECT * FROM menus WHERE available = 1";
$stmt = $pdo->query($query);
$menus = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Menu Makanan</title>
  <link href="./src/output.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
  <div class="max-w-5xl mx-auto py-8">
    <h1 class="text-3xl font-bold mb-6 text-center text-gray-800">Daftar Menu</h1>
    
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      <?php foreach ($menus as $menu): ?>
        <form method="POST" action="cart.php">
          <div class="bg-white rounded-lg shadow-md p-4 mb-4">
            <h2 class="text-xl font-semibold text-gray-800"><?= htmlspecialchars($menu['name']) ?></h2>
            <p class="text-gray-600 mb-2"><?= htmlspecialchars($menu['description']) ?></p>
            <p class="text-blue-600 font-bold mb-2">Rp <?= number_format($menu['price'], 0, ',', '.') ?></p>

            <input type="hidden" name="menu_id" value="<?= $menu['id'] ?>">
            <input type="hidden" name="menu_name" value="<?= htmlspecialchars($menu['name']) ?>">
            <input type="hidden" name="menu_price" value="<?= $menu['price'] ?>">

            <label for="qty" class="text-sm text-gray-600">Jumlah:</label>
            <input type="number" name="qty" value="1" min="1"
                   class="w-16 border border-gray-300 rounded-md px-2 py-1 text-sm mb-2">

            <button type="submit"
                    class="block bg-blue-500 hover:bg-blue-600 text-white text-sm font-semibold px-4 py-2 rounded">
              + Tambah ke Keranjang
            </button>
          </div>
        </form>
      <?php endforeach; ?>
    </div>
  </div>
</body>
</html>
