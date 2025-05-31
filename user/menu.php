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
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Menu Makanan</title>
    <link href="../src/output.css" rel="stylesheet">
</head>
<body class="bg-gray-100 text-gray-800">

    <!-- Header -->
    <nav class="bg-white shadow-sm mb-8">
        <div class="max-w-6xl mx-auto px-4 py-4 flex justify-between items-center">
            <h1 class="text-2xl font-bold text-gray-700">🍽️ Daftar Menu</h1>
            <div class="flex space-x-2">
                <a href="../index.php" class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded text-sm">
                    ⬅️ Kembali ke Beranda
                </a>
                <a href="cart.php" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded text-sm">
                    🛒 Lihat Keranjang
                </a>
            </div>
        </div>
    </nav>


    <!-- Menu Grid -->
    <main class="max-w-6xl mx-auto px-4 pb-10">
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
            <?php foreach ($menus as $menu): ?>
                <form method="POST" action="cart.php">
                    <div class="bg-white rounded-xl shadow-md overflow-hidden flex flex-col">
                        <?php if (!empty($menu['image'])): ?>
                            <img src="../public/uploads/<?= htmlspecialchars($menu['image']) ?>"
                                 alt="<?= htmlspecialchars($menu['name']) ?>"
                                 class="w-full h-48 object-cover">
                        <?php else: ?>
                            <div class="w-full h-48 bg-gray-200 flex items-center justify-center text-gray-500">
                                Tidak ada gambar
                            </div>
                        <?php endif; ?>

                        <div class="p-4 flex-grow flex flex-col">
                            <h2 class="text-lg font-semibold mb-1"><?= htmlspecialchars($menu['name']) ?></h2>
                            <p class="text-sm text-gray-600 mb-2 flex-grow"><?= htmlspecialchars($menu['description']) ?></p>
                            <p class="text-blue-600 font-bold mb-3">Rp <?= number_format($menu['price'], 0, ',', '.') ?></p>

                            <div class="mb-3">
                                <label class="text-sm text-gray-600 mr-2">Jumlah:</label>
                                <input type="number" name="qty" value="1" min="1"
                                       class="w-16 border border-gray-300 rounded-md px-2 py-1 text-sm">
                            </div>

                            <!-- Hidden inputs -->
                            <input type="hidden" name="menu_id" value="<?= $menu['id'] ?>">
                            <input type="hidden" name="menu_name" value="<?= htmlspecialchars($menu['name']) ?>">
                            <input type="hidden" name="menu_price" value="<?= $menu['price'] ?>">

                            <button type="submit"
                                    class="w-full mt-auto bg-blue-500 hover:bg-blue-600 text-white text-sm font-semibold px-4 py-2 rounded">
                                + Tambah ke Keranjang
                            </button>
                        </div>
                    </div>
                </form>
            <?php endforeach; ?>
        </div>
    </main>

</body>
</html>
