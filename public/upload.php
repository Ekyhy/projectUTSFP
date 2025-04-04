<?php
session_start();
require_once '../src/auth.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$user = $_SESSION['user_id'];
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
        <h2 class="text-2xl font-bold mb-4 text-center">Tambah Jadwal Kuliah</h2>
        <form method="POST" action="save-schedule.php" class="space-y-4">
            <div>
                <label class="block text-gray-700">Judul Jadwal:</label>
                <input type="text" name="title" required class="w-full p-2 border rounded">
            </div>
            <div>
                <label class="block text-gray-700">Deskripsi:</label>
                <textarea name="description" required class="w-full p-2 border rounded"></textarea>
            </div>
            <div>
                <label class="block text-gray-700">Tanggal:</label>
                <input type="date" name="date" required class="w-full p-2 border rounded">
            </div>
            <div>
                <label class="block text-gray-700">Waktu:</label>
                <input type="time" name="time" required class="w-full p-2 border rounded">
            </div>
            <button type="submit" class="w-full bg-blue-500 text-white py-2 rounded hover:bg-blue-600">Upload Jadwal</button>
        </form>
        <div class="text-center mt-4">
            <a href="view-schedule.php" class="text-blue-500">Lihat Jadwal</a> |
            <a href="logout.php" class="text-red-500">Logout</a>
        </div>
    </div>
    
</body>
</html>
