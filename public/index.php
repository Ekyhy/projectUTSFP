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
<script>
    // ✅ Pastikan Service Worker terdaftar lebih dulu
if ('serviceWorker' in navigator) {
    navigator.serviceWorker.register('/projectpendaki/public/sw.js')
        .then(function(registration) {
            console.log('✅ Service Worker registered', registration);
        })
        .catch(function(error) {
            console.error('❌ Service Worker registration failed:', error);
        });
}

// ✅ Fungsi untuk mengirim notifikasi
function sendNotification() {
    var userId = <?php echo json_encode($_SESSION['user_id'] ?? 1); ?>;
    
    fetch('/projectpendaki/public/send_notification.php', {
        method: 'POST', 
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ user_id: userId })
    })
    .then(response => response.text()) // Ambil response dalam format teks dulu
    .then(text => {
        console.log("📩 Respon mentah:", text); // Debug response sebelum di-parse

        let data;
        try {
            data = JSON.parse(text);  // Coba parse JSON
        } catch (error) {
            console.error("❌ Gagal parse JSON:", error);
            return;
        }

        console.log("📩 Notifikasi diterima:", data);

        if (!Array.isArray(data)) {
            console.error("❌ Data notifikasi tidak valid:", data);
            return;
        }

        if ('serviceWorker' in navigator && 'PushManager' in window) {
            navigator.serviceWorker.ready.then(registration => {
                data.forEach(notification => {
                    registration.showNotification(notification.title, {
                        body: notification.body,
                        icon: '/icon.png',
                        vibrate: [200, 100, 200]
                    });
                });
            });
        }
    })
    .catch(err => console.error("❌ Gagal mengirim notifikasi:", err));
}

// ✅ Jalankan `sendNotification()` setelah DOM dimuat
document.addEventListener("DOMContentLoaded", function () {
    sendNotification();  
});

</script>

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
                <a href="view-schedule.php" class="bg-blue-600 text-white py-2 px-4 rounded hover:bg-blue-700">Lihat Jadwal</a>
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
