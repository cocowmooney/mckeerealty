<?php
session_start();
require_once __DIR__ . '/../includes/auth.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    
    if (login($username, $password)) {
        header('Location: dashboard.php');
        exit;
    } else {
        $error = 'Invalid username or password.';
    }
}
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login | McKee Realty</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script>tailwind.config={theme:{extend:{fontFamily:{sans:['Inter','sans-serif']},colors:{navy:'#1e3a5f',gold:'#c9a84c'}}}}}</script>
</head>
<body class="font-sans bg-gray-100 min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-md">
        <div class="text-center mb-8">
            <div class="w-16 h-16 bg-navy rounded-2xl flex items-center justify-center mx-auto mb-4">
                <span class="text-gold font-bold text-2xl">M</span>
            </div>
            <h1 class="text-2xl font-bold text-navy">Admin Login</h1>
            <p class="text-gray-500 mt-1">McKee Realty Management</p>
        </div>
        <div class="bg-white rounded-xl shadow-lg p-8">
            <?php if ($error): ?>
            <div class="bg-red-50 text-red-600 p-3 rounded-lg text-sm mb-4"><?= $error ?></div>
            <?php endif; ?>
            <form method="POST" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Username</label>
                    <input type="text" name="username" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:border-gold focus:ring-2 focus:ring-gold/20 outline-none transition">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                    <input type="password" name="password" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:border-gold focus:ring-2 focus:ring-gold/20 outline-none transition">
                </div>
                <button type="submit" class="btn-primary w-full">Sign In</button>
            </form>
        </div>
        <p class="text-center text-sm text-gray-400 mt-6">
            <a href="/" class="hover:text-navy transition">&larr; Back to Website</a>
        </p>
    </div>
</body>
</html>