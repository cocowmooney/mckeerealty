<?php
require_once __DIR__ . '/../includes/auth.php';
require_admin();

$agents = $db->query("SELECT * FROM agents ORDER BY name ASC")->fetchAll();
$edit_id = $_GET['id'] ?? 0;
$editing = null;

if ($edit_id) {
    $stmt = $db->prepare("SELECT * FROM listings WHERE id = ?");
    $stmt->execute([$edit_id]);
    $editing = $stmt->fetch();
}

$saved = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save'])) {
    $title = $_POST['title'] ?? '';
    $address = $_POST['address'] ?? '';
    $city = $_POST['city'] ?? '';
    $state = $_POST['state'] ?? 'MS';
    $zip = $_POST['zip'] ?? '';
    $price = str_replace(',', '', $_POST['price'] ?? 0);
    $beds = $_POST['beds'] ?? 0;
    $baths = $_POST['baths'] ?? 0;
    $sqft = $_POST['sqft'] ?? 0;
    $description = $_POST['description'] ?? '';
    $property_type = $_POST['property_type'] ?? 'House';
    $status = $_POST['status'] ?? 'active';
    $featured = isset($_POST['featured']) ? 1 : 0;
    $agent_id = $_POST['agent_id'] ?: null;
    
    // Handle image uploads
    $images = [];
    for ($i = 1; $i <= 5; $i++) {
        $field = "image_$i";
        if (isset($_FILES[$field]) && $_FILES[$field]['error'] === UPLOAD_ERR_OK) {
            $ext = pathinfo($_FILES[$field]['name'], PATHINFO_EXTENSION);
            $filename = uniqid() . '.' . $ext;
            $dest = __DIR__ . '/../uploads/listings/' . $filename;
            move_uploaded_file($_FILES[$field]['tmp_name'], $dest);
            $images[$i] = '/uploads/listings/' . $filename;
        } elseif ($editing && $editing[$field]) {
            $images[$i] = $editing[$field];
        } else {
            $images[$i] = null;
        }
    }
    
    if ($edit_id) {
        $sql = "UPDATE listings SET title=?, address=?, city=?, state=?, zip=?, price=?, beds=?, baths=?, sqft=?, description=?, property_type=?, status=?, featured=?, agent_id=?, image_1=?, image_2=?, image_3=?, image_4=?, image_5=?, updated_at=CURRENT_TIMESTAMP WHERE id=?";
        $params = [$title, $address, $city, $state, $zip, $price, $beds, $baths, $sqft, $description, $property_type, $status, $featured, $agent_id, $images[1], $images[2], $images[3], $images[4], $images[5], $edit_id];
    } else {
        $sql = "INSERT INTO listings (title, address, city, state, zip, price, beds, baths, sqft, description, property_type, status, featured, agent_id, image_1, image_2, image_3, image_4, image_5) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $params = [$title, $address, $city, $state, $zip, $price, $beds, $baths, $sqft, $description, $property_type, $status, $featured, $agent_id, $images[1], $images[2], $images[3], $images[4], $images[5]];
    }
    
    $stmt = $db->prepare($sql);
    $stmt->execute($params);
    
    header('Location: listings.php?saved=1');
    exit;
}
?><!DOCTYPE html>
<html lang="en">
<head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title><?= $edit_id ? 'Edit' : 'Add' ?> Listing | McKee Admin</title>
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
                <a href="approve.php" class="flex items-center gap-3 px-4 py-2.5 rounded-lg hover:bg-navy-light transition"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg> Approvals</a>
                <a href="password.php" class="flex items-center gap-3 px-4 py-2.5 hover:bg-navy-light transition text-sm text-gray-200">Password</a>
                <a href="logout.php" class="flex items-center gap-3 px-4 py-2.5 hover:bg-red-700/20 text-red-300 mt-4 text-sm">Logout</a>
            </nav>
        </div>
        <div class="flex-1 overflow-y-auto pt-4 md:pt-0">
            <div class="p-6 md:p-8">
                <div class="flex items-center gap-3 mb-6">
                    <a href="listings.php" class="text-gray-400 hover:text-navy transition">&larr; Back</a>
                    <h1 class="text-2xl font-bold text-navy"><?= $edit_id ? 'Edit' : 'Add' ?> Listing</h1>
                </div>
                
                <form method="POST" enctype="multipart/form-data" class="max-w-3xl">
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 space-y-6">
                        <!-- Basic Info -->
                        <div class="grid md:grid-cols-2 gap-4">
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Listing Title *</label>
                                <input type="text" name="title" value="<?= htmlspecialchars($editing['title'] ?? '') ?>" required class="form-input">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                                <input type="text" name="address" value="<?= htmlspecialchars($editing['address'] ?? '') ?>" class="form-input">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">City *</label>
                                <input type="text" name="city" value="<?= htmlspecialchars($editing['city'] ?? '') ?>" required class="form-input">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">State</label>
                                <input type="text" name="state" value="<?= htmlspecialchars($editing['state'] ?? 'MS') ?>" class="form-input">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">ZIP</label>
                                <input type="text" name="zip" value="<?= htmlspecialchars($editing['zip'] ?? '') ?>" class="form-input">
                            </div>
                        </div>
                        
                        <!-- Pricing & Details -->
                        <div class="grid md:grid-cols-4 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Price *</label>
                                <input type="text" name="price" value="<?= $editing ? number_format($editing['price']) : '' ?>" required class="form-input" placeholder="$">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Beds</label>
                                <input type="number" name="beds" value="<?= $editing['beds'] ?? 0 ?>" min="0" class="form-input">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Baths</label>
                                <input type="number" name="baths" value="<?= $editing['baths'] ?? 0 ?>" min="0" step="0.5" class="form-input">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Sq Ft</label>
                                <input type="number" name="sqft" value="<?= $editing['sqft'] ?? 0 ?>" min="0" class="form-input">
                            </div>
                        </div>
                        
                        <!-- Property Type & Status -->
                        <div class="grid md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Property Type</label>
                                <select name="property_type" class="form-input">
                                    <?php foreach (['House','Condo','Townhouse','Land','Commercial','Multi-Unit','Mobile Home','Rental'] as $t): ?>
                                    <option value="<?= $t ?>" <?= ($editing['property_type'] ?? 'House') == $t ? 'selected' : '' ?>><?= $t ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                                <select name="status" class="form-input">
                                    <?php foreach (['active','pending','closed'] as $s): ?>
                                    <option value="<?= $s ?>" <?= ($editing['status'] ?? 'active') == $s ? 'selected' : '' ?>><?= ucfirst($s) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Listing Agent</label>
                                <select name="agent_id" class="form-input">
                                    <option value="">— Select Agent —</option>
                                    <?php foreach ($agents as $a): ?>
                                    <option value="<?= $a['id'] ?>" <?= ($editing['agent_id'] ?? '') == $a['id'] ? 'selected' : '' ?>><?= htmlspecialchars($a['name']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        
                        <!-- Featured -->
                        <div class="flex items-center gap-2">
                            <input type="checkbox" name="featured" id="featured" value="1" <?= ($editing['featured'] ?? 0) ? 'checked' : '' ?> class="w-4 h-4 text-gold border-gray-300 rounded focus:ring-gold">
                            <label for="featured" class="text-sm font-medium text-gray-700">Featured listing (shows on homepage)</label>
                        </div>
                        
                        <!-- Description -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                            <textarea name="description" rows="6" class="form-input"><?= htmlspecialchars($editing['description'] ?? '') ?></textarea>
                        </div>
                        
                        <!-- Image Uploads -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-3">Images (up to 5)</label>
                            <div class="grid md:grid-cols-5 gap-3">
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                <div>
                                    <label class="block text-xs text-gray-500 mb-1">Image <?= $i ?></label>
                                    <?php if ($editing && $editing["image_$i"]): ?>
                                    <div class="mb-1"><img src="<?= htmlspecialchars($editing["image_$i"]) ?>" class="h-16 w-full object-cover rounded" alt=""></div>
                                    <?php endif; ?>
                                    <input type="file" name="image_<?= $i ?>" accept="image/*" class="text-sm text-gray-500 file:mr-2 file:py-1 file:px-3 file:border file:border-gray-300 file:rounded file:text-xs file:bg-gray-50 file:cursor-pointer">
                                </div>
                                <?php endfor; ?>
                            </div>
                        </div>
                        
                        <div class="flex gap-3 pt-2">
                            <button type="submit" name="save" class="btn-primary"><?= $edit_id ? 'Update Listing' : 'Create Listing' ?></button>
                            <a href="listings.php" class="px-6 py-2.5 border border-gray-300 rounded-lg text-gray-600 hover:bg-gray-50 transition font-medium">Cancel</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
