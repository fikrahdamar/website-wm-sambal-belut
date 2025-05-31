<?php 
session_start();
require_once './includes/conn.php';

$id = null;
$username = null;

if (isset($_SESSION['user_id']) && isset($_SESSION['username']) && $_SESSION['role'] === 'member') {
    $id = $_SESSION['user_id'];
    $username = $_SESSION['username'];
} 


try {
    $stmt = $conn->query("SELECT * FROM menus");
    $menus = $stmt->fetchAll();
} catch (PDOException $e) {
    echo "Gagal mengambil data menu: " . $e->getMessage();
    $menus = [];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Spesial Sambal Belut Bu Raden</title>
    <style>
        .lobster-regular {
            font-family: "Lobster", sans-serif;
            font-weight: 400;
            font-style: normal;
            }
        .limelight-regular {
        font-family: "Limelight", sans-serif;
        font-weight: 400;
        font-style: normal;
        }
        html {
        scroll-behavior: smooth;
        }
    </style>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Limelight&family=Lobster&display=swap" rel="stylesheet">
    <link href="./src/output.css" rel="stylesheet">
</head>
<body class="bg-gray-100">

   <nav style="position: absolute; top: 0; left: 0; width: 100%; background: transparent; z-index: 50; color: white; padding: 12px 24px;">
    <div class="max-w-screen-xl mx-auto flex justify-between items-center">
        <div class="flex-shrink-0">
            <h1 class="text-xl font-bold lobster-regular">Sambal Belut Bu Raden</h1>
        </div>
        <ul class="flex space-x-6 text-xl font-medium">
            <li><a href="#menu" class="hover:text-green-600">Menu</a></li>
            <li><a href="#" class="hover:text-green-600">Order</a></li>
            <li><a href="#footer" class="hover:text-green-600">Contact Us</a></li>
        </ul>
        <ul class="flex space-x-6 text-xl font-medium">
            <?php if (isset($_SESSION['user_id'])): ?>
                <li><a href="#" class="hover:text-green-600">Point</a></li>
                <li><a href="#" class="hover:text-green-600">Profile</a></li>
            <?php else: ?>
                <li><a href="login.php" class="hover:text-green-600">Login</a></li>
            <?php endif; ?>
        </ul>
     </div>
    </nav>

    <section style="height: 500px; background-image: url('/warung_makan/public/assets/headingImg.jpg'); background-size: cover; background-position: center;">
        <div style="background-color: rgba(0,0,0,0.5); width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; color: white; text-align: center; padding: 1rem;">
            <div>
                <h2 class="limelight-regular" style="font-size: 2rem; font-weight: bold; margin-bottom: 1rem;">Rasa Tradisional, Harga Bersahabat!</h2>
                <p style="font-size: 1.125rem; max-width: 600px; margin: 0 auto;">Nikmati masakan khas rumahan ala Bu Raden yang dimasak dengan sepenuh hati dan bumbu pilihan, membawa cita rasa seperti di rumah sendiri.</p>
            </div>
        </div>
    </section>

   <section class="px-4 max-w-6xl mx-auto -mt-20 z-10 relative">
        <div class="bg-white p-6 rounded-xl shadow-lg">
            <h2 class="text-center text-lg font-semibold text-gray-700 mb-6">
                Kami menerima pembayaran digital maupun tunai
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-3 text-center">
                    <img src="./public/assets/tunai-icon.png" class="rounded-lg w-100 h-100 object-cover" />
                    <h3 class="text-xl font-bold text-gray-800">TUNAI</h3>
                </div>

                <div class="space-y-3 text-center items-center">
                    <img src="./public/assets/qris-icon.png" class="rounded-lg w-100 h-100 object-cover" />
                    <h3 class="text-xl font-bold text-gray-800">QRIS</h3>
                </div>
            </div>
        </div>
    </section>


    <section class="py-20 px-4 max-w-6xl mx-auto text-center mt-[100px]" id="menu">
        <h2 class="text-3xl font-bold mb-4">Menu Kami</h2>
        <p class="text-gray-600 mb-10 max-w-2xl mx-auto">Disini ada berbagai menu yang disediakan oleh kami dengan harga yang terjangkau.</p>

        <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 gap-6">
        <?php foreach ($menus as $menu): ?>
            <div>
                <img src="./public/uploads/<?= htmlspecialchars($menu['image']) ?>" alt="<?= htmlspecialchars($menu['name']) ?>" class="w-45 h-45 mx-auto rounded-full object-cover" />
                <p class="mt-3 font-medium"><?= htmlspecialchars($menu['name']) ?></p>
                <p class="text-sm text-gray-600">Rp<?= number_format($menu['price'], 0, ',', '.') ?></p>
            </div>
        <?php endforeach; ?>
        </div>
    </section>

    <footer class="bg-stone-900 text-white pt-20 pb-10 mt-32" id="footer">
        <div class="max-w-6xl mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8 text-sm md:text-base">
                <div class="space-y-4">
                    <h3 class="font-bold text-lg">Special Sambal<br>Belut Bu Raden®</h3>
                </div>

                <div class="space-y-2">
                    <h4 class="font-semibold">CONTACT ME</h4>
                    <p>+62 812 199 9009</p>
                    <p>Jl. wiyung gayungan semblaaa</p>
                </div>

                <div class="space-y-2">
                    
                </div>

                <div class="space-y-3">
                    <h4 class="font-semibold">WANT TO BE THE SMARTEST IN YOUR OFFICE?</h4>
                    <a href="#" class="underline hover:text-yellow-300">SIGN UP FOR OUR NEWSLETTER →</a>
                    <div class="flex space-x-4 pt-2 text-xl">
                        <a href="#"><i class="fa-brands fa-behance"></i></a>
                        <a href="#"><i class="fa-brands fa-instagram"></i></a>
                        <a href="#"><i class="fa-brands fa-linkedin-in"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

</body>
</html>
