<?php
session_start();
require_once '../../includes/conn.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../../login.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$id = $_GET['id'];
$stmt = $conn->prepare("SELECT * FROM menus WHERE id = ?");
$stmt->execute([$id]);
$menu = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$menu) {
    echo "Menu tidak ditemukan.";
    exit();
}

$categories = $conn->query("SELECT id, menu_categoris_name FROM menu_categories ORDER BY menu_categoris_name ASC")->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Menu</title>
    <link href="../../src/output.css" rel="stylesheet">
</head>
<body class="bg-gray-100 text-gray-800">
    <nav class="bg-white border-b border-gray-200 shadow-sm px-6 py-4">
        <div class="flex items-center justify-between">
            <div class="text-2xl font-bold text-gray-800">
                🍽️ Sambal Belut <span class="text-sm font-normal text-gray-500">Admin Panel</span>
            </div>
            <a href="index.php" class="text-md text-blue-400 hover:underline">← Kembali ke Daftar Menu</a>
        </div>
    </nav>

    <div class="p-6">
        <div class="bg-white shadow rounded-lg p-6 max-w-xl mx-auto">
            <h2 class="text-xl font-semibold mb-4">Edit Menu</h2>

            <form method="post" action="../../process/menu/edit-menu_process.php" class="space-y-4">
                <input type="hidden" name="id" value="<?= htmlspecialchars($menu['id']) ?>">

                <div>
                    <label class="block mb-1 font-medium">Nama Menu</label>
                    <input type="text" name="name" required value="<?= htmlspecialchars($menu['name']) ?>" class="w-full border border-gray-300 rounded px-3 py-2">
                </div>

                <div>
                    <label class="block mb-1 font-medium">Deskripsi</label>
                    <textarea name="description" rows="3" class="w-full border border-gray-300 rounded px-3 py-2"><?= htmlspecialchars($menu['description']) ?></textarea>
                </div>

                <div>
                    <label class="block mb-1 font-medium">Harga (Rp)</label>
                    <input type="number" name="price" min="0" required value="<?= $menu['price'] ?>" class="w-full border border-gray-300 rounded px-3 py-2">
                </div>

                <div>
                    <label class="block mb-1 font-medium">Kategori</label>
                    <select name="category_id" required class="w-full border border-gray-300 rounded px-3 py-2 bg-white">
                        <option value="" disabled>Pilih Kategori</option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?= $cat['id'] ?>" <?= ($cat['id'] == $menu['category_id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($cat['menu_categoris_name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="flex items-center mb-6">
                    <input type="checkbox" name="available" id="available" value="1" <?= $menu['available'] ? 'checked' : '' ?> class="mr-2">
                    <label for="available" class="font-medium">Tersedia</label>
                </div>

                <div class="pt-4">
                    <button type="submit" class="px-4 bg-green-500 hover:bg-green-600 text-white py-1 rounded mr-4">Simpan Perubahan</button>
                    <a href="index.php" class="ml-8 text-gray-600 hover:underline">Batal</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
