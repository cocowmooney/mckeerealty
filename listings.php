<?php
$page_title = 'Listings';
$page_desc = 'Browse available properties in Flowood, Brandon, Madison, and Central Mississippi.';
require_once 'includes/header.php';

// Get filter values
$city = $_GET['city'] ?? '';
$min_price = $_GET['min_price'] ?? '';
$max_price = $_GET['max_price'] ?? '';
$beds = $_GET['beds'] ?? '';
$baths = $_GET['baths'] ?? '';
$type = $_GET['type'] ?? '';

// Build query
$sql = "SELECT l.*, a.name as agent_name FROM listings l LEFT JOIN agents a ON l.agent_id = a.id WHERE l.status = 'active'";
$params = [];

if ($city) { $sql .= " AND l.city LIKE ?"; $params[] = "%$city%"; }
if ($min_price) { $sql .= " AND l.price >= ?"; $params[] = (float)$min_price; }
if ($max_price) { $sql .= " AND l.price <= ?"; $params[] = (float)$max_price; }
if ($beds) { $sql .= " AND l.beds >= ?"; $params[] = (int)$beds; }
if ($baths) { $sql .= " AND l.baths >= ?"; $params[] = (float)$baths; }
if ($type) { $sql .= " AND l.property_type = ?"; $params[] = $type; }

$sql .= " ORDER BY l.featured DESC, l.created_at DESC";

$stmt = $db->prepare($sql);
$stmt->execute($params);
$listings = $stmt->fetchAll();

// Get unique cities for filter
$cities = $db->query("SELECT DISTINCT city FROM listings WHERE status = 'active' AND city IS NOT NULL AND city != '' ORDER BY city")->fetchAll(PDO::FETCH_COLUMN);
?>
<!-- Page Header -->
<section class="bg-navy py-12">
    <div class="container mx-auto px-4">
        <h1 class="text-3xl md:text-4xl font-bold text-white">Browse <span class="text-gold">Properties</span></h1>
        <p class="text-gray-300 mt-2">Find your perfect home in Central Mississippi</p>
    </div>
</section>

<!-- Search/Filter Bar -->
<section class="bg-white shadow-md sticky top-16 z-40">
    <div class="container mx-auto px-4 py-4">
        <form method="GET" class="grid md:grid-cols-6 gap-3 items-end">
            <div>
                <label class="block text-xs font-semibold text-gray-500 mb-1">City</label>
                <select name="city" class="form-input text-sm">
                    <option value="">All Cities</option>
                    <?php foreach ($cities as $c): ?>
                    <option value="<?= htmlspecialchars($c) ?>" <?= $city == $c ? 'selected' : '' ?>><?= htmlspecialchars($c) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-500 mb-1">Min Price</label>
                <select name="min_price" class="form-input text-sm">
                    <option value="">No Min</option>
                    <option value="100000" <?= $min_price == '100000' ? 'selected' : '' ?>>$100k</option>
                    <option value="200000" <?= $min_price == '200000' ? 'selected' : '' ?>>$200k</option>
                    <option value="300000" <?= $min_price == '300000' ? 'selected' : '' ?>>$300k</option>
                    <option value="500000" <?= $min_price == '500000' ? 'selected' : '' ?>>$500k</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-500 mb-1">Max Price</label>
                <select name="max_price" class="form-input text-sm">
                    <option value="">No Max</option>
                    <option value="200000" <?= $max_price == '200000' ? 'selected' : '' ?>>$200k</option>
                    <option value="300000" <?= $max_price == '300000' ? 'selected' : '' ?>>$300k</option>
                    <option value="500000" <?= $max_price == '500000' ? 'selected' : '' ?>>$500k</option>
                    <option value="1000000" <?= $max_price == '1000000' ? 'selected' : '' ?>>$1M</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-500 mb-1">Beds</label>
                <select name="beds" class="form-input text-sm">
                    <option value="">Any</option>
                    <option value="1" <?= $beds == '1' ? 'selected' : '' ?>>1+</option>
                    <option value="2" <?= $beds == '2' ? 'selected' : '' ?>>2+</option>
                    <option value="3" <?= $beds == '3' ? 'selected' : '' ?>>3+</option>
                    <option value="4" <?= $beds == '4' ? 'selected' : '' ?>>4+</option>
                    <option value="5" <?= $beds == '5' ? 'selected' : '' ?>>5+</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-500 mb-1">Baths</label>
                <select name="baths" class="form-input text-sm">
                    <option value="">Any</option>
                    <option value="1" <?= $baths == '1' ? 'selected' : '' ?>>1+</option>
                    <option value="2" <?= $baths == '2' ? 'selected' : '' ?>>2+</option>
                    <option value="3" <?= $baths == '3' ? 'selected' : '' ?>>3+</option>
                    <option value="4" <?= $baths == '4' ? 'selected' : '' ?>>4+</option>
                </select>
            </div>
            <div>
                <button type="submit" class="btn-gold w-full text-sm">Search</button>
            </div>
        </form>
    </div>
</section>

<!-- Listings Grid -->
<section class="py-12">
    <div class="container mx-auto px-4">
        <?php if (count($listings) > 0): ?>
        <p class="text-gray-500 mb-6">Showing <?= count($listings) ?> listing<?= count($listings) != 1 ? 's' : '' ?></p>
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php foreach ($listings as $listing): ?>
            <a href="/listing.php?id=<?= $listing['id'] ?>" class="listing-card bg-white rounded-xl overflow-hidden shadow-md block fade-in">
                <div class="img-container h-56">
                    <?php if ($listing['image_1']): ?>
                        <img src="<?= htmlspecialchars($listing['image_1']) ?>" alt="<?= htmlspecialchars($listing['title']) ?>" class="w-full h-full object-cover">
                    <?php else: ?>
                        <div class="w-full h-full flex items-center justify-center bg-gray-200 text-gray-400">
                            <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        </div>
                    <?php endif; ?>
                    <div class="absolute bottom-4 left-4 bg-gold text-navy font-bold px-3 py-1 rounded-lg">
                        $<?= number_format($listing['price']) ?>
                    </div>
                </div>
                <div class="p-5">
                    <h3 class="font-semibold text-lg text-navy"><?= htmlspecialchars($listing['title']) ?></h3>
                    <p class="text-gray-500 text-sm mt-1"><?= htmlspecialchars($listing['city']) ?>, <?= $listing['state'] ?> <?= htmlspecialchars($listing['zip'] ?? '') ?></p>
                    <div class="flex items-center gap-4 mt-3 text-sm text-gray-600">
                        <span><?= $listing['beds'] ?> Beds</span>
                        <span><?= $listing['baths'] ?> Baths</span>
                        <?php if ($listing['sqft']): ?><span><?= number_format($listing['sqft']) ?> sqft</span><?php endif; ?>
                    </div>
                    <?php if ($listing['agent_name']): ?>
                    <p class="text-xs text-gold mt-3 font-medium">Listed by <?= htmlspecialchars($listing['agent_name']) ?></p>
                    <?php endif; ?>
                </div>
            </a>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
        <div class="text-center py-20">
            <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
            <h3 class="text-xl font-semibold text-gray-400 mb-2">No Listings Found</h3>
            <p class="text-gray-400">Try adjusting your search filters or check back later.</p>
        </div>
        <?php endif; ?>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>