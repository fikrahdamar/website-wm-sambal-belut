<?php

session_start();
require_once '../includes/conn.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>dashboard admin</title>
    <link href="./src/output.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    

    <!-- navbar dashboard admin  -->
    <nav class="bg-white shadow-md px-6 py-4 flex justify-between items-center">
        <div class="text-xl font-semibold text-gray-800">
            Warung Sambal Belut - Admin
        </div>
        <ul class="flex space-x-6">
            <li><a href="./orders.php" class="text-gray-700 hover:text-blue-500">Pesanan</a></li>
            <li><a href="./menu/index.php" class="text-gray-700 hover:text-blue-500">Menu</a></li>
            <li><a href="./category/index.php" class="text-gray-700 hover:text-blue-500">Kategori</a></li>
            <li><a href="./stock.php" class="text-gray-700 hover:text-blue-500">Stok</a></li>
            <li><a href="./members.php" class="text-gray-700 hover:text-blue-500">Member</a></li>
            <li><a href="./transactions.php" class="text-gray-700 hover:text-blue-500">Riwayat Pembayaran</a></li>
        </ul>
    </nav>


    <div class="min-h-screen flex items-center justify-center">
        <h1 class="text-2xl">
            placeholder dashboard admin
        </h1>
    </div>
</body>
</html>
