<?php
require_once '../src/schedule.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    session_start();

    if (isset($_SESSION['user'])) {
        $schedule = [
            'user_id' => $_SESSION['user']['user_id'],
            'title' => $_POST['title'],
            'description' => $_POST['description'],
            'date' => $_POST['date'],
            'time' => $_POST['time'],
        ];

        if (uploadSchedule($schedule)) {
            echo "Jadwal berhasil di-upload!";
        } else {
            echo "Gagal menyimpan jadwal.";
        }
    } else {
        echo "Silakan login terlebih dahulu.";
    }
}
?>

