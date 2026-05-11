<?php
require_once __DIR__ . '/../includes/auth.php';
require_admin();

$total_listings = $db->query("SELECT COUNT(*) FROM listings")->fetchColumn();
$active_listings = $db->query("SELECT COUNT(*) FROM listings WHERE status='active'")->fetchColumn();
$pending_listings = $db->query("SELECT COUNT(*) FROM listings WHERE status='pending'")->fetchColumn();
$closed_listings = $db->query("SELECT COUNT(*) FROM listings WHERE status='closed'")->fetchColumn();
$total_agents = $db->query("SELECT COUNT(*) FROM agents")->fetchColumn();
$total_contacts = $db->query("SELECT COUNT(*) FROM contacts")->fetchColumn();
$pending_users = $db->query("SELECT COUNT(*) FROM pending_users")->fetchColumn();

$recent_contacts = $db->query("SELECT c.*, l.title as listing_title FROM contacts c LEFT JOIN listings l ON c.listing_id = l.id ORDER BY c.created_at DESC LIMIT 5")->fetchAll();

$pend_reqs = $db->query("SELECT * FROM pending_users ORDER BY created_at DESC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>Dashboard | McKee Admin</title>
<script src="https://cdn.tailwindcss.com"></script>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<script>tailwind.config={theme:{extend:{fontFamily:{sans:['Inter','sans-serif']},colors:{navy:'#1e3a5f',gold:'#c9a84c'}}}}}</script>
<link rel="stylesheet" href="/assets/css/style.css">
</head>
<body class="font-sans bg-gray-50">
    <div class="flex h-screen">
        <div class="w-64 bg-navy text-white shrink-0 hidden md:block">
            <div class="p-6">
                <a href="/admin/dashboard.php" class="flex items-center gap-2"><img src="/assets/images/logo.png" alt="" class="h-10"></a>
            </div>
            <nav class="px-4 space-y-1">
                <a href="dashboard.php" class="flex items-center gap-3 px-4 py-2.5 bg-gold/10 text-gold font-medium text-sm">Dashboard</a>
                <a href="listings.php" class="flex items-center gap-3 px-4 py-2.5 hover:bg-navy-light transition text-sm text-gray-200">Listings</a>
                <a href="agents.php" class="flex items-center gap-3 px-4 py-2.5 hover:bg-navy-light transition text-sm text-gray-200">Agents</a>
                <a href="submissions.php" class="flex items-center gap-3 px-4 py-2.5 hover:bg-navy-light transition text-sm text-gray-200">Submissions</a>
                <?php if (is_super_admin()): ?>
                <a href="approve.php" class="flex items-center gap-3 px-4 py-2.5 hover:bg-navy-light transition text-sm text-gray-200">
                    Approvals <?= $pending_users > 0 ? '<span class="bg-gold text-navy text-xs font-bold px-2 py-0.5">'.$pending_users.'</span>' : '' ?>
                </a>
                <?php endif; ?>
                <a href="password.php" class="flex items-center gap-3 px-4 py-2.5 hover:bg-navy-light transition text-sm text-gray-200">Password</a>
                <a href="logout.php" class="flex items-center gap-3 px-4 py-2.5 hover:bg-red-700/20 text-red-300 mt-4 text-sm">Logout</a>
            </nav>
        </div>
        <div class="flex-1 overflow-y-auto pt-16 md:pt-0">
            <div class="p-6 md:p-8">
                <div class="flex items-center justify-between mb-6">
                    <h1 class="text-2xl font-bold text-navy">Dashboard</h1>
                    <span class="text-xs text-gray-400">Logged in as <strong><?= htmlspecialchars($_SESSION['admin_username']) ?></strong></span>
                </div>
                
                <?php if ($pending_users > 0 && is_super_admin()): ?>
                <div class="bg-gold/10 border border-gold/30 text-navy p-4 mb-6 text-sm flex items-center justify-between">
                    <span>There <?= $pending_users == 1 ? 'is' : 'are' ?> <strong><?= $pending_users ?></strong> pending account request<?= $pending_users != 1 ? 's' : '' ?>.</span>
                    <a href="approve.php" class="btn-gold text-xs px-4 py-1.5">Review</a>
                </div>
                <?php endif; ?>
                
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
                    <div class="bg-white border border-gray-200 p-5"><div class="text-2xl font-bold text-navy"><?= $active_listings ?></div><div class="text-xs text-gray-500">Active Listings</div></div>
                    <div class="bg-white border border-gray-200 p-5"><div class="text-2xl font-bold text-yellow-600"><?= $pending_listings ?></div><div class="text-xs text-gray-500">Pending</div></div>
                    <div class="bg-white border border-gray-200 p-5"><div class="text-2xl font-bold text-green-600"><?= $total_agents ?></div><div class="text-xs text-gray-500">Agents</div></div>
                    <div class="bg-white border border-gray-200 p-5"><div class="text-2xl font-bold text-gold"><?= $total_contacts ?></div><div class="text-xs text-gray-500">Inquiries</div></div>
                </div>
                
                <div class="flex flex-wrap gap-2 mb-8">
                    <a href="listing-edit.php" class="btn-primary text-xs">+ Add Listing</a>
                    <a href="agents.php" class="btn-gold text-xs">Manage Agents</a>
                </div>
                
                <div class="bg-white border border-gray-200">
                    <div class="p-5 border-b border-gray-100"><h2 class="font-semibold text-navy text-sm">Recent Inquiries</h2></div>
                    <?php if (count($recent_contacts) > 0): ?>
                    <div class="divide-y divide-gray-100">
                        <?php foreach ($recent_contacts as $c): ?>
                        <div class="p-4 hover:bg-gray-50">
                            <div class="flex justify-between items-start"><div><span class="font-medium text-navy text-sm"><?= htmlspecialchars($c['name']) ?></span><span class="text-xs text-gray-500 ml-2"><?= htmlspecialchars($c['email']) ?></span></div><span class="text-[10px] text-gray-400"><?= date('M j', strtotime($c['created_at'])) ?></span></div>
                            <?php if ($c['listing_title']): ?><p class="text-[11px] text-gold mt-1">Re: <?= htmlspecialchars($c['listing_title']) ?></p><?php endif; ?>
                            <p class="text-xs text-gray-600 mt-1 truncate"><?= htmlspecialchars($c['message']) ?></p>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <?php else: ?>
                    <div class="p-6 text-center text-gray-400 text-xs">No inquiries yet.</div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
