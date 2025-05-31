<?php 
session_start();
require_once '../includes/conn.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'member') {
    header("location: ../login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$sql = "SELECT * FROM users WHERE id = :id AND role = 'member'";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':id', $user_id, PDO::PARAM_INT);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?php echo htmlspecialchars($user['users_name']); ?></title>
    <link href="../src/output.css" rel="stylesheet">
</head>
<body class="bg-blue-500 min-h-screen">

    <nav class="bg-white shadow-md">
        <div class="max-w-7xl mx-auto px-4 py-3 flex justify-between items-center">
            <div class="text-xl font-bold text-blue-600">Member</div>
            <div class="flex gap-4">
                <a href="keranjang.php" class="bg-blue-500 text-white  px-4 py-2 rounded hover:bg-blue-600">🛒 Keranjang</a>
                <a href="penukaran_poin.php" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">🎁 Tukar Poin</a>
                <a href="logout.php" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">🚪 Logout</a>
            </div>
        </div>
    </nav>

    <div class="flex items-center justify-center min-h-[calc(100vh-80px)] p-4">
        <div class="bg-white p-8 rounded-xl shadow-lg w-full max-w-md">
            <h1 class="text-2xl font-bold text-center text-gray-800 mb-6">Profil Member</h1>

            <div class="mb-4">
                <label class="block text-gray-700 font-semibold mb-1">Nama:</label>
                <div class="bg-gray-100 p-4 rounded-lg text-gray-800">
                    <?php echo htmlspecialchars($user['users_name']); ?>
                </div>
            </div>

            <div>
                <label class="block text-gray-700 font-semibold mb-1">Points:</label>
                <div class="bg-gray-100 p-4 rounded-lg text-gray-800">
                    <?php echo number_format($user['point']); ?>
                </div>
            </div>
        </div>
    </div>

</body>
</html>
