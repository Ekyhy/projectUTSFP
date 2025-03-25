<?php
require_once 'db.php';

function addUser(string $username, string $password, ?string $email): bool {
    try {
        $db = connectDB(); // Dapatkan koneksi database
        $db->beginTransaction();

        // Hash password sebelum disimpan
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        // Persiapkan query
        $stmt = $db->prepare("INSERT INTO users (username, password, email) VALUES (:username, :password, :email)");
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->bindParam(':email', $email);

        // Eksekusi query
        $stmt->execute();
        $db->commit();
        return true;
    } catch (PDOException $e) {
        $db->rollBack(); // Rollback jika terjadi error
        error_log("Error: " . $e->getMessage());
        return false;
    }
}
