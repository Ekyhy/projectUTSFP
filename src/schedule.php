<?php
function uploadSchedule(array $schedule): bool {
    $db = connectDB();
    $stmt = $db->prepare('INSERT INTO schedules (user_id, title, description, date, time) VALUES (:user_id, :title, :description, :date, :time)');

    return $stmt->execute([
        ':user_id' => $schedule['user_id'],
        ':title' => $schedule['title'],
        ':description' => $schedule['description'],
        ':date' => $schedule['date'],
        ':time' => $schedule['time'],
    ]);
}