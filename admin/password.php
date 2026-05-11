<?php
require_once __DIR__ . '/../includes/auth.php';
require_admin();

$msg = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $current = $_POST['current_password'] ?? '';
    $new = $_POST['new_password'] ?? '';
    $confirm = $_POST['confirm_password'] ?? '';
    
    // Verify current password
    $stmt = $db->prepare("SELECT password_hash FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['admin_user_id']]);
    $user = $stmt->fetch();
    
    if (!password_verify($current, $user['password_hash'])) {
        $error = 'Current password is incorrect.';
    } elseif (strlen($new) < 6) {
        $error = 'New password must be at least 6 characters.';
    } elseif ($new !== $confirm) {
        $error = 'New passwords do not match.';
    } else {
        $hash = password_hash($new, PASSWORD_DEFAULT);
        $upd = $db->prepare("UPDATE users SET password_hash = ? WHERE id = ?");
        $upd->execute([$hash, $_SESSION['admin_user_id']]);
        $msg = 'Password changed successfully!';
    }
}
?><!DOCTYPE html>
<html lang="en">
<head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>Change Password | McKee Admin</title>
<script src="https://cdn.tailwindcss.com"></script>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<script>tailwind.config={theme:{extend:{fontFamily:{sans:['Inter','sans-serif']},colors:{navy:'#1e3a5f',gold:'#c9a84c'}}}}}</script>
</head>
<body class="font-sans bg-gray-50">
    <div class="flex h-screen">
        <div class="w-64 bg-navy text-white shrink-0 hidden md:block p-6">
            <a href="/admin/dashboard.php" class="flex items-center gap-2 mb-6"><img src="/assets/images/logo.png" alt="" class="h-10"></a>
            <nav class="space-y-1">
                <a href="dashboard.php" class="flex items-center gap-3 px-4 py-2.5 hover:bg-navy-light transition text-sm text-gray-200">Dashboard</a>
                <a href="listings.php" class="flex items-center gap-3 px-4 py-2.5 hover:bg-navy-light transition text-sm text-gray-200">Listings</a>
                <a href="agents.php" class="flex items-center gap-3 px-4 py-2.5 hover:bg-navy-light transition text-sm text-gray-200">Agents</a>
                <a href="submissions.php" class="flex items-center gap-3 px-4 py-2.5 hover:bg-navy-light transition text-sm text-gray-200">Submissions</a>
                <a href="approve.php" class="flex items-center gap-3 px-4 py-2.5 hover:bg-navy-light transition text-sm text-gray-200">Approvals</a>
                <a href="password.php" class="flex items-center gap-3 px-4 py-2.5 bg-gold/10 text-gold font-medium text-sm">Password</a>
                <a href="logout.php" class="flex items-center gap-3 px-4 py-2.5 hover:bg-red-700/20 text-red-300 mt-4 text-sm">Logout</a>
            </nav>
        </div>
        <div class="flex-1 overflow-y-auto pt-4 md:pt-0">
            <div class="p-6 md:p-8 max-w-md">
                <h1 class="text-2xl font-bold text-navy mb-6">Change Password</h1>
                
                <?php if ($msg): ?><div class="bg-green-50 border border-green-200 text-green-700 p-4 text-sm mb-4"><?= $msg ?></div><?php endif; ?>
                <?php if ($error): ?><div class="bg-red-50 border border-red-200 text-red-600 p-4 text-sm mb-4"><?= $error ?></div><?php endif; ?>
                
                <form method="POST" class="bg-white border border-gray-200 p-6 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Current Password</label>
                        <input type="password" name="current_password" required class="border border-gray-300 w-full px-3 py-2.5 text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">New Password</label>
                        <input type="password" name="new_password" required minlength="6" class="border border-gray-300 w-full px-3 py-2.5 text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Confirm New Password</label>
                        <input type="password" name="confirm_password" required minlength="6" class="border border-gray-300 w-full px-3 py-2.5 text-sm">
                    </div>
                    <button type="submit" class="btn-primary text-sm w-full">Change Password</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>