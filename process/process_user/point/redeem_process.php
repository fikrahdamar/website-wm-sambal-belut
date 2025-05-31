<?php
session_start();
require_once '../../../includes/conn.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'member') {
    header("Location: ../../../login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$menu_id = $_POST['menu_id'] ?? null;

if (!$menu_id) {
    header("Location: ../../../user/point/redeem_rewards.php?error=no_menu_selected");
    exit();
}


$stmtUser = $conn->prepare("SELECT point FROM users WHERE id = ?");
$stmtUser->execute([$user_id]);
$user = $stmtUser->fetch();

$stmtMenu = $conn->prepare("SELECT * FROM menus WHERE id = ? AND is_reward = 1 AND available = 1");
$stmtMenu->execute([$menu_id]);
$menu = $stmtMenu->fetch();

if (!$user || !$menu || $user['point'] < $menu['point_cost']) {
    header("Location: ../../../user/point/redeem_rewards.php?error=invalid");
    exit();
}


$stmtUpdate = $conn->prepare("UPDATE users SET point = point - ? WHERE id = ?");
$stmtUpdate->execute([$menu['point_cost'], $user_id]);


$_SESSION['cart'][] = [
    'id' => $menu['id'],
    'name' => $menu['name'],
    'price' => 0,
    'qty' => 1
];

header("Location: ../../../user/cart.php?success=reward_added");
exit();
