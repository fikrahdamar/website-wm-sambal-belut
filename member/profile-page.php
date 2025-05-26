<?php 
session_start();
require_once '../includes/conn.php';

// Cek apakah user sudah login dan berperan sebagai 'member'
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'member') {
    header("location: ../login.php");
    exit();
}

// Ambil ID user dari session
$user_id = $_SESSION['user_id'];

// Ambil data user dari database berdasarkan ID
$sql = "SELECT * FROM users WHERE id = :id AND role = 'member'";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':id', $user_id, PDO::PARAM_INT);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Jika data tidak ditemukan
if (!$user) {
    echo "Data member tidak ditemukan.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Member</title>
</head>
<body>
    <h1>Profil Member</h1>
    <p><strong>Nama:</strong> <?php echo htmlspecialchars($user['users_name']); ?></p>
    <p><strong>points:</strong> <?php echo number_format($user['point']); ?></p>
    <!-- Tambahkan data lain jika perlu -->
</body>
</html>

</html>