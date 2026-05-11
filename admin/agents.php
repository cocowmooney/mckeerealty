<?php
require_once __DIR__ . '/../includes/auth.php';
require_admin();

// Handle delete agent
if (isset($_GET['delete_agent'])) {
    $del = $db->prepare("DELETE FROM agents WHERE id = ?");
    $del->execute([$_GET['delete_agent']]);
    header('Location: agents.php');
    exit;
}

// Handle add/edit agent
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $role = $_POST['role'] ?? '';
    $email = $_POST['email'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $phone2 = $_POST['phone2'] ?? '';
    
    if ($name && $email) {
        if ($_POST['agent_id']) {
            $upd = $db->prepare("UPDATE agents SET name=?, role=?, email=?, phone=?, phone2=? WHERE id=?");
            $upd->execute([$name, $role, $email, $phone, $phone2, $_POST['agent_id']]);
        } else {
            $ins = $db->prepare("INSERT INTO agents (name, role, email, phone, phone2) VALUES (?, ?, ?, ?, ?)");
            $ins->execute([$name, $role, $email, $phone, $phone2]);
        }
        header('Location: agents.php?saved=1');
        exit;
    }
}

$agents = $db->query("SELECT * FROM agents ORDER BY name ASC")->fetchAll();
$edit_agent = null;
if (isset($_GET['edit'])) {
    $stmt = $db->prepare("SELECT * FROM agents WHERE id = ?");
    $stmt->execute([$_GET['edit']]);
    $edit_agent = $stmt->fetch();
}
?><!DOCTYPE html>
<html lang="en">
<head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>Agents | McKee Admin</title>
<script src="https://cdn.tailwindcss.com"></script>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<script>tailwind.config={theme:{extend:{fontFamily:{sans:['Inter','sans-serif']},colors:{navy:'#1e3a5f',gold:'#c9a84c'}}}}}</script>
</head>
<body class="font-sans bg-gray-50">
    <div class="flex h-screen">
        <div class="w-64 bg-navy text-white shrink-0 hidden md:block p-6">
            <a href="/admin/dashboard.php" class="flex items-center gap-2 mb-6"><div class="w-8 h-8 bg-gold rounded-lg flex items-center justify-center"><span class="text-navy font-bold">M</span></div><span class="font-bold">McKee Admin</span></a>
            <nav class="space-y-1">
                <a href="dashboard.php" class="flex items-center gap-3 px-4 py-2.5 rounded-lg hover:bg-navy-light transition">Dashboard</a>
                <a href="listings.php" class="flex items-center gap-3 px-4 py-2.5 rounded-lg hover:bg-navy-light transition">Listings</a>
                <a href="agents.php" class="flex items-center gap-3 px-4 py-2.5 rounded-lg bg-gold/10 text-gold font-medium">Agents</a>
                <a href="submissions.php" class="flex items-center gap-3 px-4 py-2.5 rounded-lg hover:bg-navy-light transition">Submissions</a>
                <a href="logout.php" class="flex items-center gap-3 px-4 py-2.5 rounded-lg hover:bg-red-700/20 text-red-300 mt-4">Logout</a>
            </nav>
        </div>
        <div class="flex-1 overflow-y-auto pt-4 md:pt-0">
            <div class="p-6 md:p-8">
                <?php if (isset($_GET['saved'])): ?><div class="bg-green-50 text-green-700 p-3 rounded-lg mb-4 text-sm">Agent saved successfully.</div><?php endif; ?>
                
                <div class="grid md:grid-cols-5 gap-8">
                    <div class="md:col-span-2">
                        <h1 class="text-2xl font-bold text-navy mb-6"><?= $edit_agent ? 'Edit Agent' : 'Add Agent' ?></h1>
                        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                            <form method="POST">
                                <?php if ($edit_agent): ?><input type="hidden" name="agent_id" value="<?= $edit_agent['id'] ?>"><?php endif; ?>
                                <div class="space-y-4">
                                    <div><label class="block text-sm font-medium text-gray-700 mb-1">Name *</label><input type="text" name="name" value="<?= htmlspecialchars($edit_agent['name'] ?? '') ?>" required class="form-input"></div>
                                    <div><label class="block text-sm font-medium text-gray-700 mb-1">Role *</label><input type="text" name="role" value="<?= htmlspecialchars($edit_agent['role'] ?? 'Realtor') ?>" required class="form-input"></div>
                                    <div><label class="block text-sm font-medium text-gray-700 mb-1">Email *</label><input type="email" name="email" value="<?= htmlspecialchars($edit_agent['email'] ?? '') ?>" required class="form-input"></div>
                                    <div><label class="block text-sm font-medium text-gray-700 mb-1">Phone</label><input type="text" name="phone" value="<?= htmlspecialchars($edit_agent['phone'] ?? '') ?>" class="form-input"></div>
                                    <div><label class="block text-sm font-medium text-gray-700 mb-1">Phone 2</label><input type="text" name="phone2" value="<?= htmlspecialchars($edit_agent['phone2'] ?? '') ?>" class="form-input"></div>
                                    <div class="flex gap-3">
                                        <button type="submit" class="btn-primary"><?= $edit_agent ? 'Update' : 'Add Agent' ?></button>
                                        <?php if ($edit_agent): ?><a href="agents.php" class="px-4 py-2.5 border border-gray-300 rounded-lg text-gray-600 hover:bg-gray-50 transition text-sm">Cancel</a><?php endif; ?>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="md:col-span-3">
                        <h1 class="text-2xl font-bold text-navy mb-6">All Agents</h1>
                        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead class="bg-gray-50 text-gray-600">
                                    <tr><th class="text-left p-4 font-medium">Name</th><th class="text-left p-4 font-medium hidden md:table-cell">Role</th><th class="text-left p-4 font-medium hidden md:table-cell">Email</th><th class="text-left p-4 font-medium">Actions</th></tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    <?php foreach ($agents as $a): ?>
                                    <tr class="hover:bg-gray-50">
                                        <td class="p-4 font-medium text-navy"><?= htmlspecialchars($a['name']) ?></td>
                                        <td class="p-4 text-gray-500 hidden md:table-cell"><?= htmlspecialchars($a['role']) ?></td>
                                        <td class="p-4 text-gray-500 hidden md:table-cell"><?= htmlspecialchars($a['email']) ?></td>
                                        <td class="p-4"><div class="flex gap-2"><a href="?edit=<?= $a['id'] ?>" class="text-navy hover:text-gold transition text-xs font-medium">Edit</a><span class="text-gray-300">|</span><a href="?delete_agent=<?= $a['id'] ?>" onclick="return confirm('Delete this agent?')" class="text-red-500 hover:text-red-700 transition text-xs">Delete</a></div></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>