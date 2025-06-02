<?php
session_start();
require_once '../../includes/conn.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../../login.php");
    exit();
}

if (!isset($_GET['order_id']) || !isset($_GET['status'])) {
    header("Location: ../../admin/orders/index.php?status_update=error");
    exit();
}

$order_id = intval($_GET['order_id']);
$new_status = $_GET['status'];

$allowed_statuses = ['process', 'completed', 'canceled'];
if (!in_array($new_status, $allowed_statuses)) {
    header("Location: ../../admin/orders/index.php?status_update=error");
    exit();
}

$sql = "UPDATE orders SET status = :new_status WHERE id = :order_id";

try {
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':new_status', $new_status, PDO::PARAM_STR);
    $stmt->bindParam(':order_id', $order_id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        if ($new_status === 'completed') {
            $orderQuery = "SELECT payment_method, total_price, service_type FROM orders WHERE id = :order_id";
            $orderStmt = $conn->prepare($orderQuery);
            $orderStmt->bindParam(':order_id', $order_id, PDO::PARAM_INT);
            $orderStmt->execute();
            $orderData = $orderStmt->fetch(PDO::FETCH_ASSOC);

            if ($orderData) {
                $insertTransaction = "INSERT INTO transactions (order_id, payment_method, amount, created_at)
                                      VALUES (:order_id, :payment_method, :total_price, NOW())";
                $transStmt = $conn->prepare($insertTransaction);
                $transStmt->bindParam(':order_id', $order_id, PDO::PARAM_INT);
                $transStmt->bindParam(':payment_method', $orderData['payment_method'], PDO::PARAM_STR);
                $transStmt->bindParam(':total_price', $orderData['total_price'], PDO::PARAM_STR);
                $transStmt->execute();
            }
        }

        header("Location: ../../admin/orders/index.php?status_update=success");
        exit();
    } else {
        header("Location: ../../admin/orders/index.php?status_update=error");
        exit();
    }
} catch (PDOException $e) {
    echo "PDO Exception: " . $e->getMessage();
    header("Location: ../../admin/orders/index.php?status_update=error");
    exit();
}
?>
