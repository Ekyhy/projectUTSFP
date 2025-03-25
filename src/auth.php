<?php
require_once 'db.php';

function loginUser(string $username, string $password): ?array {
    try {
        $db = connectDB();
        $stmt = $db->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Pastikan password diverifikasi sebelum login berhasil
        if ($user && password_verify($password, $user['password'])) {
            return $user; // Login sukses
        }
        return null; // Login gagal
    } catch (PDOException $e) {
        error_log("Login Error: " . $e->getMessage());
        return null;
    }
}
?>
