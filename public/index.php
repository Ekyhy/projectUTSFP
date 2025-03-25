<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard User</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css">
</head>
<body class="bg-gray-100">
    <div class="container mx-auto p-6">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-blue-600">Selamat Datang, <?php echo $_SESSION['username']; ?>!
            </h1>
            <a href="logout.php" class="bg-blue-600 text-white py-2 px-4 rounded hover:bg-blue-700">Logout</a>
        </div>

        <!-- Card Container -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Card 1 -->
            <div class="bg-white p-6 rounded-2xl shadow-md hover:shadow-lg transition">
                <h2 class="text-xl font-bold mb-2">Upload Jadwal Kuliah</h2>
                <p class="text-gray-600 mb-4">Tambahkan jadwal kuliah kamu untuk memudahkan tracking perjalananmu.</p>
                <a href="upload.php" class="bg-blue-600 text-white py-2 px-4 rounded hover:bg-blue-700">Upload Sekarang</a>
            </div>

            <!-- Card 2 -->
            <div class="bg-white p-6 rounded-2xl shadow-md hover:shadow-lg transition">
                <h2 class="text-xl font-bold mb-2">Jadwal Saya</h2>
                <p class="text-gray-600 mb-4">Lihat dan kelola jadwal kuliah yang telah kamu upload.</p>
                <a href="#" class="bg-blue-600 text-white py-2 px-4 rounded hover:bg-blue-700">Lihat Jadwal</a>
            </div>

            <!-- Card 3 -->
            <div class="bg-white p-6 rounded-2xl shadow-md hover:shadow-lg transition">
                <h2 class="text-xl font-bold mb-2">Profil Saya</h2>
                <p class="text-gray-600 mb-4">Perbarui informasi akun kamu agar tetap aman dan terkini.</p>
                <a href="#" class="bg-blue-600 text-white py-2 px-4 rounded hover:bg-blue-700">Perbarui Profil</a>
            </div>
        </div>
    </div>
</body>
</html>
