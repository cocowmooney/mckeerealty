<?php
require_once __DIR__ . '/../includes/auth.php';
require_super_admin();

// Approve user
if (isset($_GET['approve'])) {
    $stmt = $db->prepare("SELECT * FROM pending_users WHERE id = ?");
    $stmt->execute([$_GET['approve']]);
    $pu = $stmt->fetch();
    
    if ($pu) {
        // Move to users table
        $ins = $db->prepare("INSERT INTO users (username, email, password_hash, approved, role) VALUES (?, ?, ?, 1, 'admin')");
        $ins->execute([$pu['username'], $pu['email'], $pu['password_hash']]);
        
        // Remove from pending
        $del = $db->prepare("DELETE FROM pending_users WHERE id = ?");
        $del->execute([$pu['id']]);
        
        $msg = "Approved {$pu['username']}.";
    }
    header('Location: approve.php');
    exit;
}

// Reject user
if (isset($_GET['reject'])) {
    $del = $db->prepare("DELETE FROM pending_users WHERE id = ?");
    $del->execute([$_GET['reject']]);
    header('Location: approve.php');
    exit;
}

$pending = $db->query("SELECT * FROM pending_users ORDER BY created_at DESC")->fetchAll();
$approved_users = $db->query("SELECT * FROM users ORDER BY role ASC, username ASC")->fetchAll();
?><!DOCTYPE html>
<html lang="en">
<head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>Approve Users | McKee Admin</title>
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
                <a href="approve.php" class="flex items-center gap-3 px-4 py-2.5 bg-gold/10 text-gold font-medium text-sm">Approvals</a>
                <a href="password.php" class="flex items-center gap-3 px-4 py-2.5 hover:bg-navy-light transition text-sm text-gray-200">Password</a>
                <a href="logout.php" class="flex items-center gap-3 px-4 py-2.5 hover:bg-red-700/20 text-red-300 mt-4 text-sm">Logout</a>
            </nav>
        </div>
        <div class="flex-1 overflow-y-auto pt-4 md:pt-0">
            <div class="p-6 md:p-8">
                <h1 class="text-2xl font-bold text-navy mb-6">Account Approvals</h1>
                
                <h2 class="text-sm font-semibold text-gray-700 mb-3 uppercase tracking-wider">Pending Requests</h2>
                <div class="bg-white border border-gray-200 overflow-x-auto mb-8">
                    <?php if (count($pending) > 0): ?>
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50 text-gray-600"><tr><th class="text-left p-4 font-medium">Username</th><th class="text-left p-4 font-medium hidden md:table-cell">Email</th><th class="text-left p-4 font-medium hidden md:table-cell">Requested</th><th class="text-left p-4 font-medium">Actions</th></tr></thead>
                        <tbody class="divide-y divide-gray-100">
                            <?php foreach ($pending as $p): ?>
                            <tr class="hover:bg-gray-50">
                                <td class="p-4 font-medium text-navy"><?= htmlspecialchars($p['username']) ?></td>
                                <td class="p-4 text-gray-500 hidden md:table-cell"><?= htmlspecialchars($p['email']) ?></td>
                                <td class="p-4 text-gray-500 hidden md:table-cell"><?= date('M j, Y g:i A', strtotime($p['created_at'])) ?></td>
                                <td class="p-4">
                                    <a href="?approve=<?= $p['id'] ?>" class="text-green-600 hover:text-green-800 text-xs font-medium mr-3">Approve</a>
                                    <a href="?reject=<?= $p['id'] ?>" onclick="return confirm('Reject this request?')" class="text-red-500 hover:text-red-700 text-xs font-medium">Reject</a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <?php else: ?>
                    <div class="p-6 text-center text-gray-400 text-xs">No pending requests.</div>
                    <?php endif; ?>
                </div>
                
                <h2 class="text-sm font-semibold text-gray-700 mb-3 uppercase tracking-wider">Active Admins</h2>
                <div class="bg-white border border-gray-200 overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50 text-gray-600"><tr><th class="text-left p-4 font-medium">Username</th><th class="text-left p-4 font-medium hidden md:table-cell">Role</th><th class="text-left p-4 font-medium hidden md:table-cell">Created</th></tr></thead>
                        <tbody class="divide-y divide-gray-100">
                            <?php foreach ($approved_users as $u): ?>
                            <tr class="hover:bg-gray-50">
                                <td class="p-4 font-medium text-navy"><?= htmlspecialchars($u['username']) ?> <?= $u['role'] == 'super' ? '<span class="text-gold text-[10px] uppercase tracking-wider">(Owner)</span>' : '' ?></td>
                                <td class="p-4 text-gray-500 hidden md:table-cell"><?= ucfirst($u['role']) ?></td>
                                <td class="p-4 text-gray-500 hidden md:table-cell"><?= date('M j, Y', strtotime($u['created_at'])) ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
