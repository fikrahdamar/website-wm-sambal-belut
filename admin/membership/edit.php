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
$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$id]);
$member = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$member) {
    echo "Member tidak ditemukan.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Member</title>
    <link href="../../src/output.css" rel="stylesheet">
</head>
<body class="bg-gray-100 text-gray-800">
    <nav class="bg-white border-b border-gray-200 shadow-sm px-6 py-4">
        <div class="flex items-center justify-between">
            <div class="text-2xl font-bold text-gray-800">
                👤 Member Panel <span class="text-sm font-normal text-gray-500">Admin</span>
            </div>
            <a href="index.php" class="text-md text-blue-400 hover:underline">← Kembali ke Daftar Member</a>
        </div>
    </nav>

    <div class="p-6">
        <div class="bg-white shadow rounded-lg p-6 max-w-xl mx-auto">
            <h2 class="text-xl font-semibold mb-4">Edit Member</h2>

            <form method="post" action="../../process/membership/edit-membership_process.php" class="space-y-4">
                <input type="hidden" name="id" value="<?= htmlspecialchars($member['id']) ?>">

                <div>
                    <label class="block mb-1 font-medium">Nama Member</label>
                    <input type="text" name="users_name" required value="<?= htmlspecialchars($member['users_name']) ?>" class="w-full border border-gray-300 rounded px-3 py-2">
                </div>

                <div>
                    <label class="block mb-1 font-medium">Poin</label>
                    <input type="number" name="point" min="0" required value="<?= htmlspecialchars($member['point']) ?>" class="w-full border border-gray-300 rounded px-3 py-2">
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
