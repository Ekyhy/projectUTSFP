<?php
session_start();
require_once '../src/auth.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$conn = connectDB();
$user_id = $_SESSION['user_id'];
try {
    $stmt = $conn->prepare("SELECT * FROM schedules WHERE user_id = :user_id ORDER BY date, time ASC");
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    $schedules = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <title>Jadwal Kuliah</title>
    <script>
        if ('Notification' in window) {
    Notification.requestPermission().then(function(permission) {
        if (permission === 'granted') {
            console.log('Izin notifikasi diberikan');
        } else {
            console.log('Izin notifikasi ditolak');
        }
    });
}

function sendNotification(user_id) {
    fetch('/projectpendaki/public/send_notification.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ user_id: user_id })
    })
    .then(response => response.json())
    .then(data => {
        if (!Array.isArray(data) || data.length === 0) {
            console.warn("ℹ️ Tidak ada notifikasi:", data);
            return;
        }

        if ('serviceWorker' in navigator && 'PushManager' in window) {
            navigator.serviceWorker.ready.then(function(registration) {
                data.forEach(function(notification) {
                    registration.showNotification(notification.title, {
                        body: notification.body,
                        icon: '/icon.png',
                        vibrate: [200, 100, 200]
                    });
                });
            });
        }
    })
    .catch(function(error) {
        console.error("❌ Gagal mengirim notifikasi", error);
    });
}

// Coba kirim notifikasi saat halaman dimuat
document.addEventListener("DOMContentLoaded", function () {
    let userId = 1; // Ganti dengan ID user yang sedang login
    sendNotification(userId);
});
if ('Notification' in window) {
    Notification.requestPermission().then(function(permission) {
        if (permission === 'granted') {
            console.log('Izin notifikasi diberikan');
        } else {
            console.log('Izin notifikasi ditolak');
        }
    });
}


    </script>
    <style>
  .my-button {
    text-decoration: none; /* Menghapus garis bawah dari link */
  }

  
  /* Mengubah warna ikon menjadi hijau */
  .fas.fa-home {
    color: green;
  }
</style>
</head>
<body class="bg-gray-100 flex justify-center items-center min-h-screen">
    <div class="bg-white p-6 rounded-lg shadow-lg w-4/5">
        <h2 class="text-2xl font-bold mb-4 text-center">Jadwal Kuliah</h2>
        <a href="index.php" class="my-button">
        <button type="button">
            <i class="fas fa-home" color="green"></i>
        </button>
        </a>
        <table class="w-full border-collapse border border-gray-300">
            <thead>
                <tr class="bg-gray-200">
                    <th class="border p-2">Judul</th>
                    <th class="border p-2">Deskripsi</th>
                    <th class="border p-2">Tanggal</th>
                    <th class="border p-2">Waktu</th>
                    <th class="border p-2">Aksi</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($schedules as $schedule) : ?>
            <tr>
                <td><?= htmlspecialchars($schedule['title']) ?></td>
                <td><?= htmlspecialchars($schedule['description']) ?></td>
                <td><?= htmlspecialchars($schedule['date']) ?></td>
                <td><?= htmlspecialchars($schedule['time']) ?></td>
                <td>
                    <a href="edit-schedule.php?id=<?= $schedule['schedule_id'] ?>">Edit</a> | 
                    <a href="delete-schedule.php?id=<?= $schedule['schedule_id'] ?>" onclick="return confirm('Yakin ingin menghapus?')">Hapus</a>
                </td>
            </tr>
        <?php endforeach; ?>
            </tbody>
        </table>

        <div class="text-center mt-4">
            <a href="upload.php" class="text-blue-500">Tambah Jadwal</a> |
            <a href="logout.php" class="text-red-500">Logout</a>
        </div>
    </div>
</body>
</html>
