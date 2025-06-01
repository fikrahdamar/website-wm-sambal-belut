<?php
session_start();
require_once './includes/conn.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    if (!empty($username) && !empty($password)) {
        // Cek apakah username sudah ada
        $stmt = $conn->prepare("SELECT id FROM users WHERE users_name = ?");
        $stmt->execute([$username]);

        if ($stmt->rowCount() > 0) {
            $error = "Username sudah digunakan!";
        } else {
            $hashed = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $conn->prepare("INSERT INTO users (users_name, password, role, point) VALUES (?, ?, 'member', 0)");
            $stmt->execute([$username, $hashed]);

            header("Location: login.php?success=registered");
            exit();
        }
    } else {
        $error = "Username dan password harus diisi.";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Daftar Akun</title>
  <link href="./src/output.css" rel="stylesheet">
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
  <div class="bg-white p-8 rounded-xl shadow-lg w-full max-w-md">
    <h1 class="text-2xl font-bold text-center mb-4">Buat Akun Baru</h1>

    <?php if (isset($error)): ?>
      <div class="mb-4 text-red-600 font-medium"><?= $error ?></div>
    <?php endif; ?>

    <form method="POST">
      <div class="mb-4">
        <label for="username" class="block text-sm font-semibold mb-1">Username</label>
        <input type="text" id="username" name="username" required
               class="w-full border border-gray-300 rounded px-3 py-2">
      </div>

      <div class="mb-6">
        <label for="password" class="block text-sm font-semibold mb-1">Password</label>
        <input type="password" id="password" name="password" required
               class="w-full border border-gray-300 rounded px-3 py-2">
      </div>

      <button type="submit"
              class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 rounded">
        Daftar
      </button>

      <p class="text-sm text-center text-gray-600 mt-4">
        Sudah punya akun? <a href="login.php" class="text-blue-600 hover:underline">Login di sini</a>
      </p>
    </form>
  </div>
</body>
</html>
