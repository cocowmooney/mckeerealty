<?php
require_once __DIR__ . '/../includes/auth.php';
require_admin();

// Handle delete
if (isset($_GET['delete'])) {
    $del = $db->prepare("DELETE FROM listings WHERE id = ?");
    $del->execute([$_GET['delete']]);
    header('Location: listings.php?deleted=1');
    exit;
}

// Handle status change
if (isset($_GET['set_status']) && isset($_GET['id'])) {
    $upd = $db->prepare("UPDATE listings SET status = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?");
    $upd->execute([$_GET['set_status'], $_GET['id']]);
    header('Location: listings.php?updated=1');
    exit;
}

$listings = $db->query("SELECT l.*, a.name as agent_name FROM listings l LEFT JOIN agents a ON l.agent_id = a.id ORDER BY l.created_at DESC")->fetchAll();
?><!DOCTYPE html>
<html lang="en">
<head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>Manage Listings | McKee Admin</title>
<script src="https://cdn.tailwindcss.com"></script>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<script>tailwind.config={theme:{extend:{fontFamily:{sans:['Inter','sans-serif']},colors:{navy:'#1e3a5f',gold:'#c9a84c'}}}}}</script>
</head>
<body class="font-sans bg-gray-50">
    <div class="flex h-screen">
        <div class="w-64 bg-navy text-white shrink-0 hidden md:block p-6">
            <a href="/admin/dashboard.php" class="flex items-center gap-2 mb-6"><div class="w-8 h-8 bg-gold rounded-lg flex items-center justify-center"><span class="text-navy font-bold">M</span></div><span class="font-bold">McKee Admin</span></a>
            <nav class="space-y-1">
                <a href="dashboard.php" class="flex items-center gap-3 px-4 py-2.5 rounded-lg hover:bg-navy-light transition"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg> Dashboard</a>
                <a href="listings.php" class="flex items-center gap-3 px-4 py-2.5 rounded-lg bg-gold/10 text-gold font-medium"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg> Listings</a>
                <a href="agents.php" class="flex items-center gap-3 px-4 py-2.5 rounded-lg hover:bg-navy-light transition"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg> Agents</a>
                <a href="submissions.php" class="flex items-center gap-3 px-4 py-2.5 rounded-lg hover:bg-navy-light transition"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg> Submissions</a>
                <a href="logout.php" class="flex items-center gap-3 px-4 py-2.5 rounded-lg hover:bg-red-700/20 text-red-300 mt-4"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg> Logout</a>
            </nav>
        </div>
        <div class="flex-1 overflow-y-auto pt-4 md:pt-0">
            <div class="p-6 md:p-8">
                <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
                    <h1 class="text-2xl font-bold text-navy">Manage Listings</h1>
                    <a href="listing-edit.php" class="btn-primary text-sm">+ Add New Listing</a>
                </div>
                
                <?php if (isset($_GET['deleted'])): ?><div class="bg-green-50 text-green-700 p-3 rounded-lg mb-4 text-sm">Listing deleted successfully.</div><?php endif; ?>
                <?php if (isset($_GET['updated'])): ?><div class="bg-green-50 text-green-700 p-3 rounded-lg mb-4 text-sm">Listing updated successfully.</div><?php endif; ?>
                
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50 text-gray-600">
                            <tr>
                                <th class="text-left p-4 font-medium">Title</th>
                                <th class="text-left p-4 font-medium hidden md:table-cell">City</th>
                                <th class="text-left p-4 font-medium">Price</th>
                                <th class="text-left p-4 font-medium hidden md:table-cell">Agent</th>
                                <th class="text-left p-4 font-medium">Status</th>
                                <th class="text-left p-4 font-medium">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <?php if (count($listings) > 0): ?>
                            <?php foreach ($listings as $l): ?>
                            <tr class="hover:bg-gray-50">
                                <td class="p-4 font-medium text-navy"><?= htmlspecialchars($l['title']) ?></td>
                                <td class="p-4 text-gray-500 hidden md:table-cell"><?= htmlspecialchars($l['city']) ?></td>
                                <td class="p-4">$<?= number_format($l['price']) ?></td>
                                <td class="p-4 text-gray-500 hidden md:table-cell"><?= htmlspecialchars($l['agent_name'] ?? '—') ?></td>
                                <td class="p-4">
                                    <span class="badge-<?= $l['status'] ?> text-white text-xs font-semibold px-2 py-0.5 rounded-full uppercase"><?= $l['status'] ?></span>
                                </td>
                                <td class="p-4">
                                    <div class="flex items-center gap-2">
                                        <a href="listing-edit.php?id=<?= $l['id'] ?>" class="text-navy hover:text-gold transition text-xs font-medium">Edit</a>
                                        <span class="text-gray-300">|</span>
                                        <a href="?set_status=active&id=<?= $l['id'] ?>" class="text-green-600 hover:text-green-800 transition text-xs">Active</a>
                                        <span class="text-gray-300">|</span>
                                        <a href="?set_status=pending&id=<?= $l['id'] ?>" class="text-yellow-600 hover:text-yellow-800 transition text-xs">Pending</a>
                                        <span class="text-gray-300">|</span>
                                        <a href="?set_status=closed&id=<?= $l['id'] ?>" class="text-gray-600 hover:text-gray-800 transition text-xs">Closed</a>
                                        <span class="text-gray-300">|</span>
                                        <a href="?delete=<?= $l['id'] ?>" onclick="return confirm('Delete this listing?')" class="text-red-500 hover:text-red-700 transition text-xs">Delete</a>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                            <?php else: ?>
                            <tr><td colspan="6" class="p-8 text-center text-gray-400">No listings yet. <a href="listing-edit.php" class="text-gold hover:text-navy transition">Add your first listing</a></td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>
</html>