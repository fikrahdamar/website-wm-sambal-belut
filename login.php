<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login page</title>
    <link href="./src/output.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="bg-white p-8 rounded-xl shadow-lg w-full max-w-md">
        <div class="text-center mb-8">
            <h1 class="text-2xl font-bold text-gray-800">Spesial Sambal Belut Bu Raden</h1>
            <p class="text-gray-500">Silahkan login ke akun Anda</p>
        </div>
        <form action="process/login_process.php" method="POST">
            <div class="mb-6">
                <label for="username" class="block text-sm font-semibold text-gray-700 mb-2">Username</label>
                <input type="text" id="username" name="username" placeholder="Masukkan username Anda"
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition duration-150 ease-in-out"
                       required>
            </div>
            <div class="mb-6">
                <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">Password</label>
                <input type="password" id="password" name="password" placeholder="Masukkan password Anda"
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition duration-150 ease-in-out"
                       required>
            </div>
            <!--
            buat nanti kalo mau punya fitur remember me
            <div class="mb-6 flex items-center justify-between">
                <div class="flex items-center">
                    <input type="checkbox" id="remember_me" name="remember_me" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                    <label for="remember_me" class="ml-2 block text-sm text-gray-900">Remember me</label>
                </div>
            </div>
            -->
            <div>
                <button type="submit"
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-lg focus:outline-none focus:shadow-outline transition duration-150 ease-in-out">
                    Login
                </button>
            </div>
            <div class="mt-6 text-center">
                <p class="text-sm text-gray-600">
                    Tidak punya akun? <a href="register.php" class="font-medium text-blue-600 hover:text-blue-500">Daftar</a>
                </p>
                <hr class="my-6 border-gray-300">
                <button type="button"
                        class="w-full bg-white border border-blue-600 text-blue-600 font-bold py-3 px-4 rounded-lg focus:outline-none focus:shadow-outline transition duration-150 ease-in-out hover:bg-gray-100">
                    Lihat Menu
                </button>
            </div>
        </form>
    </div>
</body>
</html>
