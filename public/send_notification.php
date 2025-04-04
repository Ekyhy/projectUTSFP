<?php
require_once '../src/db.php';

header('Content-Type: application/json');

// 🔴 DEBUG: Cek apakah request method benar
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['error' => 'Hanya menerima request POST']);
    exit();
}

// 🔴 DEBUG: Cek isi dari `php://input`
$raw_input = file_get_contents('php://input');
error_log("Raw input: " . $raw_input);  // Cek di error log PHP

// Decode JSON
$input = json_decode($raw_input, true);

// 🔴 DEBUG: Cek hasil decode JSON
if (json_last_error() !== JSON_ERROR_NONE) {
    echo json_encode(['error' => 'JSON tidak valid: ' . json_last_error_msg()]);
    exit();
}

// 🔴 DEBUG: Cek apakah user_id ada
if (!isset($input['user_id'])) {
    echo json_encode(['error' => 'Data tidak valid - user_id tidak ditemukan']);
    exit();
}

$user_id = intval($input['user_id']);

// 🔴 DEBUG: Log user_id
error_log("User ID: " . $user_id);

// Query database
try {
    $conn = connectDB();
    $stmt = $conn->prepare("SELECT title, date, time FROM schedules WHERE user_id = :user_id AND date = CURDATE()");
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    $schedules = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($schedules)) {
        echo json_encode([]); // Kirim array kosong jika tidak ada jadwal
        exit();
    }

    $notifications = [];
    foreach ($schedules as $schedule) {
        $notifications[] = [
            'title' => "Pengingat Jadwal: " . htmlspecialchars($schedule['title']),
            'body'  => "Jadwal kamu pada " . htmlspecialchars($schedule['date']) . " pukul " . htmlspecialchars($schedule['time'])
        ];
    }

    echo json_encode($notifications);
} catch (PDOException $e) {
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
?>
