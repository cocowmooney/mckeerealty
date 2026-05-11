<?php
require_once __DIR__ . '/../includes/auth.php';
require_admin();

$total_listings = $db->query("SELECT COUNT(*) FROM listings")->fetchColumn();
$active_listings = $db->query("SELECT COUNT(*) FROM listings WHERE status='active'")->fetchColumn();
$pending_listings = $db->query("SELECT COUNT(*) FROM listings WHERE status='pending'")->fetchColumn();
$closed_listings = $db->query("SELECT COUNT(*) FROM listings WHERE status='closed'")->fetchColumn();
$total_agents = $db->query("SELECT COUNT(*) FROM agents")->fetchColumn();
$total_contacts = $db->query("SELECT COUNT(*) FROM contacts")->fetchColumn();
$recent_contacts = $db->query("SELECT c.*, l.title as listing_title FROM contacts c LEFT JOIN listings l ON c.listing_id = l.id ORDER BY c.created_at DESC LIMIT 5")->fetchAll();
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
        <!-- Sidebar -->
        <div class="w-64 bg-navy text-white shrink-0 hidden md:block">
            <div class="p-6">
                <a href="/admin/dashboard.php" class="flex items-center gap-2">
                    <div class="w-8 h-8 bg-gold rounded-lg flex items-center justify-center"><span class="text-navy font-bold">M</span></div>
                    <span class="font-bold">McKee Admin</span>
                </a>
            </div>
            <nav class="px-4 space-y-1">
                <a href="dashboard.php" class="flex items-center gap-3 px-4 py-2.5 rounded-lg bg-gold/10 text-gold font-medium"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg> Dashboard</a>
                <a href="listings.php" class="flex items-center gap-3 px-4 py-2.5 rounded-lg hover:bg-navy-light transition"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg> Listings</a>
                <a href="agents.php" class="flex items-center gap-3 px-4 py-2.5 rounded-lg hover:bg-navy-light transition"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg> Agents</a>
                <a href="submissions.php" class="flex items-center gap-3 px-4 py-2.5 rounded-lg hover:bg-navy-light transition"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg> Submissions</a>
                <a href="logout.php" class="flex items-center gap-3 px-4 py-2.5 rounded-lg hover:bg-red-700/20 text-red-300 mt-4"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg> Logout</a>
            </nav>
        </div>
        <!-- Mobile header -->
        <div class="md:hidden fixed top-0 left-0 right-0 bg-navy text-white p-4 flex items-center justify-between z-50">
            <span class="font-bold">McKee Admin</span>
            <a href="logout.php" class="text-red-300 text-sm">Logout</a>
        </div>
        <!-- Main Content -->
        <div class="flex-1 overflow-y-auto pt-16 md:pt-0">
            <div class="p-6 md:p-8">
                <h1 class="text-2xl font-bold text-navy mb-6">Dashboard</h1>
                <!-- Stats -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
                    <div class="bg-white rounded-xl shadow-sm p-5 border border-gray-100"><div class="text-2xl font-bold text-navy"><?= $active_listings ?></div><div class="text-sm text-gray-500">Active Listings</div></div>
                    <div class="bg-white rounded-xl shadow-sm p-5 border border-gray-100"><div class="text-2xl font-bold text-yellow-600"><?= $pending_listings ?></div><div class="text-sm text-gray-500">Pending</div></div>
                    <div class="bg-white rounded-xl shadow-sm p-5 border border-gray-100"><div class="text-2xl font-bold text-green-600"><?= $total_agents ?></div><div class="text-sm text-gray-500">Agents</div></div>
                    <div class="bg-white rounded-xl shadow-sm p-5 border border-gray-100"><div class="text-2xl font-bold text-gold"><?= $total_contacts ?></div><div class="text-sm text-gray-500">Inquiries</div></div>
                </div>
                <!-- Quick links -->
                <div class="flex flex-wrap gap-3 mb-8">
                    <a href="listing-edit.php" class="btn-primary text-sm">+ Add New Listing</a>
                    <a href="agents.php" class="btn-gold text-sm">Manage Agents</a>
                </div>
                <!-- Recent submissions -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                    <div class="p-5 border-b border-gray-100"><h2 class="font-semibold text-navy">Recent Inquiries</h2></div>
                    <?php if (count($recent_contacts) > 0): ?>
                    <div class="divide-y divide-gray-100">
                        <?php foreach ($recent_contacts as $c): ?>
                        <div class="p-4 hover:bg-gray-50">
                            <div class="flex justify-between items-start"><div><span class="font-medium text-navy"><?= htmlspecialchars($c['name']) ?></span><span class="text-sm text-gray-500 ml-2"><?= htmlspecialchars($c['email']) ?></span></div><span class="text-xs text-gray-400"><?= date('M j, g:i A', strtotime($c['created_at'])) ?></span></div>
                            <?php if ($c['listing_title']): ?><p class="text-xs text-gold mt-1">Re: <?= htmlspecialchars($c['listing_title']) ?></p><?php endif; ?>
                            <p class="text-sm text-gray-600 mt-1 line-clamp-1"><?= htmlspecialchars($c['message']) ?></p>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <?php else: ?>
                    <div class="p-6 text-center text-gray-400 text-sm">No inquiries yet.</div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</body>
</html>