<?php
session_start();
require_once '../../includes/conn.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../../login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? null;
    $users_name = trim($_POST['users_name'] ?? '');
    $point = intval($_POST['point'] ?? 0);

    if ($id && $users_name !== '') {
        $stmt = $conn->prepare("UPDATE users SET users_name = ?, point = ? WHERE id = ?");
        $success = $stmt->execute([$users_name, $point, $id]);

        if ($success) {
            header("Location: ../../admin/membership/index.php?deleted=edited");
        } else {
            header("Location: ../../admin/membership/index.php?error=gagal_update");
        }
        exit();
    }
}

header("Location: ../../admin/membership/index.php?error=input_tidak_valid");
exit();
