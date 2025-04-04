<?php
session_start();
require_once '../src/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$conn = connectDB();
$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $date = $_POST['date'];
    $time = $_POST['time'];

    try {
        $stmt = $conn->prepare("INSERT INTO schedules (user_id, title, description, date, time) VALUES (:user_id, :title, :description, :date, :time)");
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':date', $date);
        $stmt->bindParam(':time', $time);

        if ($stmt->execute()) {
            header("Location: view-schedule.php");
            exit();
        } else {
            echo "Gagal menyimpan jadwal!";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>
