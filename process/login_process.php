<?php
session_start();

require_once '../includes/conn.php';

// $host = 'localhost';
// $db = 'warung_makan_sambalbelut';
// $user = 'root';
// $pass = '';

// try {
//     $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
//     $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
// } catch (PDOException $e) {
//     die("Database connection failed: " . $e->getMessage());
// }

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // buat ngecek kalo username dan password kosong, blm ada error handling
    // if (empty($username) || empty($password)) {
    //     header("Location: ../login.php?error=empty_fields");
    //     exit();
    // }

    $sql = "SELECT id, users_name, `password`, role FROM users WHERE users_name = :username LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':username', $username, PDO::PARAM_STR);
    $stmt->execute();

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // buat verify kalo username dan password sama
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['users_name'];
        $_SESSION['role'] = $user['role'];

        // ngecek role user, kalo admin ke dashboard, kalo bukan admin ke index
        if ($user['role'] == 'admin') {
            header("Location: ../admin/dashboard.php");
            exit();
        } else if($user['role'] == 'member'){
            header("Location: ../user/member/member-page.php");
            exit();
        }
    } else {
        // kalo username dan password beda, blm ada error handling
        // header("Location: ../login.php?error=invalid_credentials");
        header("Location: ../login.php");
        exit();
    }
} else {
    header("Location: ../login.php");
    exit();
}
?> 