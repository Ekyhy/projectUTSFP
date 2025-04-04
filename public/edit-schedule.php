<?php
session_start();
require_once '../src/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$conn = connectDB();
$user_id = $_SESSION['user_id']; // Pastikan hanya user yang bersangkutan bisa edit

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['schedule_id'])) {
    $schedule_id = $_POST['schedule_id'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $date = $_POST['date'];
    $time = $_POST['time'];

    try {
        $stmt = $conn->prepare("UPDATE schedules SET title = :title, description = :description, date = :date, time = :time WHERE schedule_id = :schedule_id AND user_id = :user_id");
        $stmt->bindParam(':schedule_id', $schedule_id, PDO::PARAM_INT);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindParam(':title', $title, PDO::PARAM_STR);
        $stmt->bindParam(':description', $description, PDO::PARAM_STR);
        $stmt->bindParam(':date', $date, PDO::PARAM_STR);
        $stmt->bindParam(':time', $time, PDO::PARAM_STR);

        if ($stmt->execute()) {
            header("Location: view-schedule.php?message=updated");
            exit();
        } else {
            echo "Gagal memperbarui jadwal!";
        }
    } catch (PDOException $e) {
        die("Error: " . $e->getMessage());
    }
} elseif (isset($_GET['id'])) {
    $schedule_id = $_GET['id'];

    try {
        $stmt = $conn->prepare("SELECT * FROM schedules WHERE schedule_id = :schedule_id AND user_id = :user_id");
        $stmt->bindParam(':schedule_id', $schedule_id, PDO::PARAM_INT);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        $schedule = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$schedule) {
            die("Jadwal tidak ditemukan atau bukan milik Anda!");
        }
    } catch (PDOException $e) {
        die("Error: " . $e->getMessage());
    }
} else {
    die("ID tidak valid!");
}
?>


<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Tambah Jadwal Kuliah</title>
</head>
<body class="bg-gray-100 flex justify-center items-center h-screen">
    <div class="bg-white p-8 rounded-lg shadow-lg w-96">
        <h2 class="text-2xl font-bold mb-4 text-center">Edit Jadwal Kuliah</h2>
        <form method="POST" action="edit-schedule.php" class="space-y-4">
             <input type="hidden" name="schedule_id" value="<?= htmlspecialchars($schedule['schedule_id']) ?>">
            <div>
                <label class="block text-gray-700">Judul Jadwal:</label>
                <input type="text" name="title" required class="w-full p-2 border rounded" value="<?= htmlspecialchars($schedule['title']) ?>">
            </div>
            <div>
                <label class="block text-gray-700">Deskripsi:</label>
                <textarea name="description" required class="w-full p-2 border rounded" ><?= htmlspecialchars($schedule['description']) ?></textarea>
            </div>
            <div>
                <label class="block text-gray-700">Tanggal:</label>
                <input type="date" name="date" required class="w-full p-2 border rounded" value="<?= htmlspecialchars($schedule['date']) ?>" required>
            </div>
            <div>
                <label class="block text-gray-700">Waktu:</label>
                <input type="time" name="time" required class="w-full p-2 border rounded" value="<?= htmlspecialchars($schedule['time']) ?>" required>
            </div>
            <button type="submit" class="w-full bg-blue-500 text-white py-2 rounded hover:bg-blue-600" value="Update">Upload Jadwal</button>
        </form>
        <div class="text-center mt-4">
            <a href="view-schedule.php" class="text-blue-500">Lihat Jadwal</a> |
            <a href="logout.php" class="text-red-500">Logout</a>
        </div>
    </div>
</body>
</html>

