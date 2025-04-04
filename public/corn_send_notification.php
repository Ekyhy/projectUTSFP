<?php
require_once '../src/db.php';

try {
    $conn = connectDB();

    // Ambil jadwal yang waktunya hampir tiba (misalnya 10 menit sebelum)
    $stmt = $conn->prepare("
        SELECT schedule_id, user_id, title, date, time 
        FROM schedules 
        WHERE reminder_sent = 0 
        AND TIMESTAMP(date, time) <= NOW() + INTERVAL 10 MINUTE
    ");
    $stmt->execute();
    $schedules = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($schedules)) {
        echo "Tidak ada jadwal yang perlu dikirim notifikasi.";
        exit();
    }

    foreach ($schedules as $schedule) {
        $user_id = $schedule['user_id'];
        $title = "Pengingat: " . $schedule['title'];
        $body = "Jadwal Anda akan dimulai pada " . $schedule['date'] . " pukul " . $schedule['time'];

        // Kirim notifikasi
        $payload = json_encode(["user_id" => $user_id, "title" => $title, "body" => $body]);
        error_log("Notifikasi dikirim untuk User ID: " . $user_id);

        $ch = curl_init("http://localhost/projectpendaki/public/send_notification.php");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        $response = curl_exec($ch);
        curl_close($ch);

        echo "Notifikasi dikirim untuk User ID: $user_id\n";

        // Tandai sebagai sudah dikirim
        $updateStmt = $conn->prepare("UPDATE schedules SET reminder_sent = 1 WHERE user_id = :user_id");
        $updateStmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $updateStmt->execute();

    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
