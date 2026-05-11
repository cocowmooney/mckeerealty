<?php
// Load database globally first
require_once __DIR__ . '/db.php';

function is_logged_in(): bool {
    return isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;
}

function is_super_admin(): bool {
    return isset($_SESSION['admin_role']) && $_SESSION['admin_role'] === 'super';
}

function require_admin(): void {
    session_start();
    if (!is_logged_in()) {
        header('Location: /admin/login.php?redirect=' . urlencode($_SERVER['REQUEST_URI']));
        exit;
    }
}

function require_super_admin(): void {
    require_admin();
    if (!is_super_admin()) {
        die('Access denied. Only the primary admin can access this page.');
    }
}

function login(string $username, string $password): string {
    global $db;
    
    if (!$db) {
        return 'invalid';
    }
    
    $stmt = $db->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();
    
    if (!$user) {
        return 'invalid';
    }
    
    if (!$user['approved']) {
        return 'unapproved';
    }
    
    if (password_verify($password, $user['password_hash'])) {
        session_start();
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_username'] = $user['username'];
        $_SESSION['admin_user_id'] = $user['id'];
        $_SESSION['admin_role'] = $user['role'];
        return 'ok';
    }
    
    return 'invalid';
}

function logout(): void {
    session_start();
    session_destroy();
}

function request_account(string $username, string $email, string $password): bool {
    global $db;
    
    if (!$db) {
        return false;
    }
    
    // Check if username already taken
    $check = $db->prepare("SELECT COUNT(*) as c FROM users WHERE username = ?");
    $check->execute([$username]);
    if ($check->fetch()['c'] > 0) return false;
    
    // Check if already pending
    $check2 = $db->prepare("SELECT COUNT(*) as c FROM pending_users WHERE username = ?");
    $check2->execute([$username]);
    if ($check2->fetch()['c'] > 0) return false;
    
    $hash = password_hash($password, PASSWORD_DEFAULT);
    $ins = $db->prepare("INSERT INTO pending_users (username, email, password_hash) VALUES (?, ?, ?)");
    $ins->execute([$username, $email, $hash]);
    
    return true;
}