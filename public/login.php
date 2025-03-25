<?php
require_once '../src/auth.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    $user = loginUser($username, $password);
    var_dump($user);

    if ($user) {
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['username'] = $user['username'];

        header("Location: index.php");
        exit();
    } else {
        $error = "Login gagal! Username atau password salah.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css">
</head>
<body class="bg-gradient-to-r from-blue-500 to-blue-700 min-h-screen flex items-center justify-center">
    <div class="bg-white p-8 rounded-2xl shadow-lg w-96">
        <h2 class="text-2xl font-bold text-center text-blue-600 mb-6">Login Dashboard Mahasiswa</h2>

        <form action="login.php" method="POST" class="space-y-4">
            <div>
                <label for="username" class="block text-sm font-medium text-gray-700">Username</label>
                <input type="text" name="username" id="username" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" required>
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                <input type="password" name="password" id="password" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" required>
            </div>

            <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded-lg mt-4 hover:bg-blue-700 transition">Masuk</button>
        </form>

        <p class="text-center text-gray-500 text-sm mt-4">Belum punya akun? <a href="register.php" class="text-blue-600">Daftar</a></p>
    </div>
</body>
</html>
