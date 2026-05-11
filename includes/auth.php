<?php
// Admin authentication
function is_logged_in(): bool {
    return isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;
}

function require_admin(): void {
    session_start();
    if (!is_logged_in()) {
        header('Location: /admin/login.php?redirect=' . urlencode($_SERVER['REQUEST_URI']));
        exit;
    }
}

function login(string $username, string $password): bool {
    require_once __DIR__ . '/db.php';
    global $db;
    
    $stmt = $db->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();
    
    if ($user && password_verify($password, $user['password_hash'])) {
        session_start();
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_username'] = $user['username'];
        $_SESSION['admin_user_id'] = $user['id'];
        return true;
    }
    return false;
}

function logout(): void {
    session_start();
    session_destroy();
}