<?php
require_once __DIR__ . '/../includes/auth.php';
require_admin();

$submissions = $db->query("SELECT c.*, l.title as listing_title FROM contacts c LEFT JOIN listings l ON c.listing_id = l.id ORDER BY c.created_at DESC")->fetchAll();
?><!DOCTYPE html>
<html lang="en">
<head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>Submissions | McKee Admin</title>
<script src="https://cdn.tailwindcss.com"></script>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<script>tailwind.config={theme:{extend:{fontFamily:{sans:['Inter','sans-serif']},colors:{navy:'#1e3a5f',gold:'#c9a84c'}}}}}</script>
</head>
<body class="font-sans bg-gray-50">
    <div class="flex h-screen">
        <div class="w-64 bg-slate-800 text-white shrink-0 hidden md:block p-6">
            <a href="/admin/dashboard.php" class="flex items-center gap-2 mb-6"><div class="w-8 h-8 bg-amber-400 rounded-lg flex items-center justify-center"><span class="text-navy font-bold">M</span></div><span class="font-bold">McKee Admin</span></a>
            <nav class="space-y-1">
                <a href="dashboard.php" class="flex items-center gap-3 px-4 py-2.5 rounded-lg hover:bg-slate-700 transition">Dashboard</a>
                <a href="listings.php" class="flex items-center gap-3 px-4 py-2.5 rounded-lg hover:bg-slate-700 transition">Listings</a>
                <a href="agents.php" class="flex items-center gap-3 px-4 py-2.5 rounded-lg hover:bg-slate-700 transition">Agents</a>
                <a href="submissions.php" class="flex items-center gap-3 px-4 py-2.5 rounded-lg bg-amber-400/10 text-amber-400 font-medium">Submissions</a>
                <a href="approve.php" class="flex items-center gap-3 px-4 py-2.5 rounded-lg hover:bg-slate-700 transition">Approvals</a>
                <a href="password.php" class="flex items-center gap-3 px-4 py-2.5 hover:bg-slate-700 transition text-sm text-slate-200">Password</a>
                <a href="logout.php" class="flex items-center gap-3 px-4 py-2.5 hover:bg-red-700/20 text-red-300 mt-4 text-sm">Logout</a>
            </nav>
        </div>
        <div class="flex-1 overflow-y-auto pt-4 md:pt-0">
            <div class="p-6 md:p-8">
                <h1 class="text-2xl font-bold text-navy mb-6">Contact Submissions</h1>
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50 text-gray-600">
                            <tr><th class="text-left p-4 font-medium">Date</th><th class="text-left p-4 font-medium">Name</th><th class="text-left p-4 font-medium hidden md:table-cell">Email</th><th class="text-left p-4 font-medium hidden md:table-cell">Phone</th><th class="text-left p-4 font-medium hidden md:table-cell">Property</th><th class="text-left p-4 font-medium hidden md:table-cell">Notified</th></tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <?php if (count($submissions) > 0): ?>
                            <?php foreach ($submissions as $s): ?>
                            <tr class="hover:bg-gray-50">
                                <td class="p-4 text-gray-500 whitespace-nowrap"><?= date('M j, Y', strtotime($s['created_at'])) ?></td>
                                <td class="p-4 font-medium text-navy"><?= htmlspecialchars($s['name']) ?></td>
                                <td class="p-4 text-gray-500 hidden md:table-cell"><a href="mailto:<?= $s['email'] ?>" class="hover:text-navy"><?= htmlspecialchars($s['email']) ?></a></td>
                                <td class="p-4 text-gray-500 hidden md:table-cell"><?= htmlspecialchars($s['phone'] ?? '—') ?></td>
                                <td class="p-4 text-gray-500 hidden md:table-cell"><?= htmlspecialchars($s['listing_title'] ?? 'General Inquiry') ?></td>
                                <td class="p-4 hidden md:table-cell"><?= $s['agent_notified'] ? '<span class="text-green-600 font-medium">Yes</span>' : '<span class="text-yellow-600">No</span>' ?></td>
                            </tr>
                            <tr class="md:hidden"><td colspan="2" class="px-4 pb-3 text-sm text-gray-500"><?= htmlspecialchars($s['email']) ?> <?= $s['phone'] ? '• '.htmlspecialchars($s['phone']) : '' ?> <?= $s['listing_title'] ? '• '.htmlspecialchars($s['listing_title']) : '' ?></td></tr>
                            <tr class="border-t-0"><td colspan="6" class="px-4 pb-3 text-sm text-gray-600"><?= nl2br(htmlspecialchars($s['message'])) ?></td></tr>
                            <?php endforeach; ?>
                            <?php else: ?>
                            <tr><td colspan="6" class="p-8 text-center text-gray-400">No submissions yet.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
