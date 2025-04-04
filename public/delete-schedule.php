<?php
session_start();
require_once '../src/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// memastikan connectDB() mengembalikan instance PDO
$conn = connectDB(); 
if (isset($_GET['id'])) {
    $schedule_id = $_GET['id'];
    // memastikan hanya bisa hapus milik user sendiri
    $user_id = $_SESSION['user_id']; 

    try {
        $stmt = $conn->prepare("DELETE FROM schedules WHERE schedule_id = :schedule_id AND user_id = :user_id");
        $stmt->bindParam(':schedule_id', $schedule_id, PDO::PARAM_INT);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            header("Location: view-schedule.php?message=deleted");
            exit();
        } else {
            echo "Gagal menghapus jadwal!";
        }
    } catch (PDOException $e) {
        die("Error: " . $e->getMessage());
    }
} else {
    echo "ID tidak ditemukan!";
}
?>
