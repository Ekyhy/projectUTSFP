<?php
require_once '../src/db.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    $user_id = $input['user_id'] ?? null;

    if (!$user_id) {
        echo json_encode(["error" => "User ID tidak valid"]);
        exit();
    }

    try {
        $conn = connectDB();
        $stmt = $conn->prepare("SELECT title, date, time FROM schedules WHERE user_id = :user_id AND date = CURDATE()");
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        $schedules = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (empty($schedules)) {
            echo json_encode(["message" => "Tidak ada jadwal untuk hari ini"]);
            exit();
        }

        $notifications = [];
        foreach ($schedules as $schedule) {
            $notifications[] = [
                "title" => "Pengingat Jadwal: " . $schedule['title'],
                "body"  => "Jadwal kamu hari ini pukul " . $schedule['time']
            ];
        }

        echo json_encode($notifications);
    } catch (PDOException $e) {
        echo json_encode(["error" => $e->getMessage()]);
    }
}
?>
