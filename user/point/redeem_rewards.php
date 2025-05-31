<?php
session_start();
require_once '../../includes/conn.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'member') {
    header("Location: login.php");
    exit();
}

// Ambil poin user
$stmt = $conn->prepare("SELECT point FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();


$stmt = $conn->query("SELECT * FROM menus WHERE is_reward = 1 AND available = 1");
$rewards = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Reward Point</title>
    <link href="../../src/output.css" rel="stylesheet">
</head>
<body class="bg-gray-100 text-gray-800">

    <nav class="bg-white shadow-sm mb-8">
        <div class="max-w-6xl mx-auto px-4 py-4 flex justify-between items-center">
            <h1 class="text-xl font-bold text-gray-700">🎁 Tukarkan Poin</h1>
            <a href="../../index.php" class="text-sm text-blue-600 underline">← Kembali ke Menu</a>
        </div>
    </nav>

    <main class="max-w-6xl mx-auto px-4 pb-10">
        <p class="mb-4">Poin kamu saat ini: <strong><?= $user['point'] ?></strong></p>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
            <?php foreach ($rewards as $reward): ?>
                <form method="POST" action="../../process/process_user/point/redeem_process.php">
                    <div class="bg-white rounded-xl shadow-md overflow-hidden flex flex-col">
                        <img src="../../public/uploads/<?= htmlspecialchars($reward['image']) ?>" alt="<?= htmlspecialchars($reward['name']) ?>" class="w-full h-48 object-cover">

                        <div class="p-4 flex-grow flex flex-col">
                            <h2 class="text-lg font-semibold mb-1"><?= htmlspecialchars($reward['name']) ?></h2>
                            <p class="text-sm text-gray-600 mb-2 flex-grow"><?= htmlspecialchars($reward['description']) ?></p>
                            <p class="text-green-600 font-bold mb-3">🎯 <?= $reward['point_cost'] ?> Poin</p>

                            <input type="hidden" name="menu_id" value="<?= $reward['id'] ?>">
                            <button type="submit" class="w-full mt-auto bg-green-500 hover:bg-green-600 text-white text-sm font-semibold px-4 py-2 rounded"
                                <?= $user['point'] < $reward['point_cost'] ? 'disabled class="bg-gray-400 cursor-not-allowed text-white px-4 py-2 rounded"' : '' ?>>
                                Tukarkan
                            </button>
                        </div>
                    </div>
                </form>
            <?php endforeach; ?>
        </div>
    </main>

</body>
</html>
