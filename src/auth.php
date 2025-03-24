<?php

function loginUser(string $username, string $password): ?array {
    $db = connectDB();
    $stmt = $db->prepare('SELECT * FROM users WHERE username = :username AND password = :password');
    $stmt->execute([':username' => $username, ':password' => $password]);
    return $stmt->fetch() ?: null;
}
