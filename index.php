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

   <nav class="fixed top-0 left-0 w-full z-50 transition-all duration-300 py-3" id="navbar">
    <div class="max-w-screen-xl mx-auto flex justify-between items-center px-4">
        <div class="flex-shrink-0">
            <h1 class="text-2xl font-bold lobster-regular text-white">Sambal Belut Bu Raden</h1>
        </div>
        <div class="hidden md:flex space-x-8 text-lg font-medium">
            <ul class="flex space-x-8">
                <li><a href="#menu" class="text-white hover:text-green-400 transition-colors duration-300">Menu</a></li>
                <li><a href="./user/menu.php" class="text-white hover:text-green-400 transition-colors duration-300">Order</a></li>
                <li><a href="#footer" class="text-white hover:text-green-400 transition-colors duration-300">Contact Us</a></li>
            </ul>
            <ul class="flex space-x-8">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <li><a href="./user/point/redeem_rewards.php" class="text-white hover:text-green-400 transition-colors duration-300">Point</a></li>
                    <li><a href="./user/member/profile-page.php" class="text-white hover:text-green-400 transition-colors duration-300">Profile</a></li>
                <?php else: ?>
                    <li><a href="login.php" class="text-white hover:text-green-400 transition-colors duration-300">Login</a></li>
                <?php endif; ?>
            </ul>
        </div>
        <div class="md:hidden">
            <button id="mobile-menu-button" class="text-white focus:outline-none">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path></svg>
            </button>
        </div>
    </div>
    <div id="mobile-menu" class="md:hidden hidden bg-stone-900 bg-opacity-90 py-2">
        <ul class="flex flex-col items-center space-y-3 text-lg font-medium">
            <li><a href="#menu" class="text-white hover:text-green-400 transition-colors duration-300 py-2 block">Menu</a></li>
            <li><a href="./user/menu.php" class="text-white hover:text-green-400 transition-colors duration-300 py-2 block">Order</a></li>
            <li><a href="#footer" class="text-white hover:text-green-400 transition-colors duration-300 py-2 block">Contact Us</a></li>
            <?php if (isset($_SESSION['user_id'])): ?>
                <li><a href="./user/point/redeem_rewards.php" class="text-white hover:text-green-400 transition-colors duration-300 py-2 block">Point</a></li>
                <li><a href="./user/member/profile-page.php" class="text-white hover:text-green-400 transition-colors duration-300 py-2 block">Profile</a></li>
            <?php else: ?>
                <li><a href="login.php" class="text-white hover:text-green-400 transition-colors duration-300 py-2 block">Login</a></li>
            <?php endif; ?>
        </ul>
    </div>
</nav>

    <section class="relative h-screen bg-cover bg-center" style="background-image: url('/warung_makan/public/assets/headingImg.jpg');">
        <div class="absolute inset-0 bg-black bg-opacity-60 flex items-center justify-center text-white text-center p-4">
            <div>
                <h2 class="limelight-regular text-5xl md:text-6xl font-bold mb-4 animate-fade-in-up">Rasa Tradisional, Harga Bersahabat!</h2>
                <p class="text-lg md:text-xl max-w-3xl mx-auto opacity-0 animate-fade-in-up animation-delay-500">Nikmati masakan khas rumahan ala Bu Raden yang dimasak dengan sepenuh hati dan bumbu pilihan, membawa cita rasa seperti di rumah sendiri.</p>
                <a href="#menu" class="mt-8 inline-block bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-8 rounded-full transition duration-300 ease-in-out transform hover:scale-105 opacity-0 animate-fade-in-up animation-delay-1000">Lihat Menu</a>
            </div>
        </div>
    </section>

   <section class="px-4 max-w-6xl mx-auto -mt-20 z-10 relative">
        <div class="bg-white p-8 rounded-xl shadow-lg border border-gray-200">
            <h2 class="text-center text-2xl font-semibold text-gray-800 mb-8">
                Kami menerima pembayaran digital maupun tunai
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-center">
                <div class="flex flex-col items-center p-4 bg-gray-50 rounded-lg shadow-sm transform hover:scale-105 transition-transform duration-300">
                    <img src="./public/assets/tunai-icon.png" alt="Tunai Icon" class="w-48 h-48 object-contain mb-4" />
                    <h3 class="text-2xl font-bold text-gray-800">TUNAI</h3>
                </div>

                <div class="flex flex-col items-center p-4 bg-gray-50 rounded-lg shadow-sm transform hover:scale-105 transition-transform duration-300">
                    <img src="./public/assets/qris-icon.png" alt="QRIS Icon" class="w-48 h-48 object-contain mb-4" />
                    <h3 class="text-2xl font-bold text-gray-800">QRIS</h3>
                </div>
            </div>
        </div>
    </section>


    <section class="py-20 px-4 max-w-6xl mx-auto text-center mt-20" id="menu">
        <h2 class="text-4xl font-bold mb-5 text-gray-800">Menu Kami</h2>
        <p class="text-gray-600 mb-12 text-lg max-w-2xl mx-auto">Disini ada berbagai menu yang disediakan oleh kami dengan harga yang terjangkau.</p>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
        <?php foreach ($menus as $menu): ?>
            <div class="bg-white rounded-xl shadow-lg overflow-hidden transform hover:scale-105 transition-transform duration-300 border border-gray-200">
                <img src="./public/uploads/<?= htmlspecialchars($menu['image']) ?>" alt="<?= htmlspecialchars($menu['name']) ?>" class="w-full h-48 object-cover object-center" />
                <div class="p-5">
                    <p class="mt-3 text-xl font-semibold text-gray-900 truncate"><?= htmlspecialchars($menu['name']) ?></p>
                    <p class="text-md text-gray-700 mt-1">Rp<?= number_format($menu['price'], 0, ',', '.') ?></p>
                </div>
            </div>
        <?php endforeach; ?>
        </div>
    </section>

    <footer class="bg-stone-900 text-white pt-20 pb-10 mt-32" id="footer">
        <div class="max-w-6xl mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8 text-sm md:text-base">
                <div class="space-y-4">
                    <h3 class="font-bold text-xl">Special Sambal<br>Belut Bu Raden®</h3>
                    <p class="text-gray-400">Nikmati masakan khas rumahan dengan cita rasa tak terlupakan.</p>
                </div>

                <div class="space-y-2">
                    <h4 class="font-semibold text-lg">CONTACT ME</h4>
                    <p class="text-gray-400">+62 812 199 9009</p>
                    <p class="text-gray-400">Jl. wiyung gayungan semblaaa</p>
                </div>

                <div class="space-y-2">
                    <h4 class="font-semibold text-lg">Quick Links</h4>
                    <ul>
                        <li><a href="#menu" class="text-gray-400 hover:text-green-400 transition-colors duration-300">Menu</a></li>
                        <li><a href="./user/menu.php" class="text-gray-400 hover:text-green-400 transition-colors duration-300">Order</a></li>
                        <li><a href="#footer" class="text-gray-400 hover:text-green-400 transition-colors duration-300">Contact Us</a></li>
                    </ul>
                </div>

                <div class="space-y-3">
                    <h4 class="font-semibold text-lg">Our Social Media</h4>
                    <a href="#" class="text-gray-400 hover:text-yellow-300 transition-colors duration-300">Instagram</a>
                    <div class="flex space-x-4 pt-2 text-2xl">
                        <a href="#" class="text-gray-400 hover:text-green-400 transition-colors duration-300"><i class="fa-brands fa-facebook"></i></a>
                        <a href="#" class="text-gray-400 hover:text-green-400 transition-colors duration-300"><i class="fa-brands fa-instagram"></i></a>
                        <a href="#" class="text-gray-400 hover:text-green-400 transition-colors duration-300"><i class="fa-brands fa-whatsapp"></i></a>
                    </div>
                </div>
            </div>
            <div class="border-t border-gray-700 mt-10 pt-8 text-center text-gray-500">
                <p>&copy; 2023 Spesial Sambal Belut Bu Raden. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const navbar = document.getElementById('navbar');
            const mobileMenuButton = document.getElementById('mobile-menu-button');
            const mobileMenu = document.getElementById('mobile-menu');

            window.addEventListener('scroll', function() {
                if (window.scrollY > 50) {
                    navbar.classList.add('bg-stone-900', 'shadow-lg');
                } else {
                    navbar.classList.remove('bg-stone-900', 'shadow-lg');
                }
            });

            mobileMenuButton.addEventListener('click', function() {
                mobileMenu.classList.toggle('hidden');
            });

            // Smooth scrolling for navigation links
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function (e) {
                    e.preventDefault();

                    document.querySelector(this.getAttribute('href')).scrollIntoView({
                        behavior: 'smooth'
                    });
                });
            });
        });
    </script>
    <script src="https://kit.fontawesome.com/your-font-awesome-kit.js" crossorigin="anonymous"></script>

</body>
</html>
