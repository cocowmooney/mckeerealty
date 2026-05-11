<?php
$page_title = 'Listings';
$page_desc = 'Browse available properties in Flowood, Brandon, Madison, and Central Mississippi.';
require_once 'includes/header.php';

$city = $_GET['city'] ?? '';
$min_price = $_GET['min_price'] ?? '';
$max_price = $_GET['max_price'] ?? '';
$beds = $_GET['beds'] ?? '';
$baths = $_GET['baths'] ?? '';

$sql = "SELECT l.*, a.name as agent_name FROM listings l LEFT JOIN agents a ON l.agent_id = a.id WHERE l.status = 'active'";
$params = [];

if ($city) { $sql .= " AND l.city LIKE ?"; $params[] = "%$city%"; }
if ($min_price) { $sql .= " AND l.price >= ?"; $params[] = (float)$min_price; }
if ($max_price) { $sql .= " AND l.price <= ?"; $params[] = (float)$max_price; }
if ($beds) { $sql .= " AND l.beds >= ?"; $params[] = (int)$beds; }
if ($baths) { $sql .= " AND l.baths >= ?"; $params[] = (float)$baths; }

$sql .= " ORDER BY l.featured DESC, l.created_at DESC";
$stmt = $db->prepare($sql);
$stmt->execute($params);
$listings = $stmt->fetchAll();

$cities = $db->query("SELECT DISTINCT city FROM listings WHERE status = 'active' AND city IS NOT NULL AND city != '' ORDER BY city")->fetchAll(PDO::FETCH_COLUMN);
?>
<section class="bg-navy py-10">
    <div class="container mx-auto px-4">
        <h1 class="text-3xl font-bold text-white">Browse <span class="text-gold">Properties</span></h1>
        <p class="text-gray-400 text-sm mt-1">Find your perfect home in Central Mississippi</p>
    </div>
</section>

<!-- Filter Bar -->
<section class="bg-white border-b border-gray-200 sticky top-16 z-40">
    <div class="container mx-auto px-4 py-3">
        <form method="GET" class="flex flex-wrap gap-2 items-end">
            <select name="city" class="border border-gray-300 px-3 py-2 text-sm bg-white">
                <option value="">All Cities</option>
                <?php foreach ($cities as $c): ?>
                <option value="<?= htmlspecialchars($c) ?>" <?= $city == $c ? 'selected' : '' ?>><?= htmlspecialchars($c) ?></option>
                <?php endforeach; ?>
            </select>
            <select name="min_price" class="border border-gray-300 px-3 py-2 text-sm bg-white">
                <option value="">Min Price</option>
                <option value="100000" <?= $min_price == '100000' ? 'selected' : '' ?>>$100k</option>
                <option value="200000" <?= $min_price == '200000' ? 'selected' : '' ?>>$200k</option>
                <option value="300000" <?= $min_price == '300000' ? 'selected' : '' ?>>$300k</option>
                <option value="500000" <?= $min_price == '500000' ? 'selected' : '' ?>>$500k</option>
            </select>
            <select name="max_price" class="border border-gray-300 px-3 py-2 text-sm bg-white">
                <option value="">Max Price</option>
                <option value="200000" <?= $max_price == '200000' ? 'selected' : '' ?>>$200k</option>
                <option value="300000" <?= $max_price == '300000' ? 'selected' : '' ?>>$300k</option>
                <option value="500000" <?= $max_price == '500000' ? 'selected' : '' ?>>$500k</option>
                <option value="1000000" <?= $max_price == '1000000' ? 'selected' : '' ?>>$1M</option>
            </select>
            <select name="beds" class="border border-gray-300 px-3 py-2 text-sm bg-white">
                <option value="">Beds</option>
                <option value="1" <?= $beds == '1' ? 'selected' : '' ?>>1+</option>
                <option value="2" <?= $beds == '2' ? 'selected' : '' ?>>2+</option>
                <option value="3" <?= $beds == '3' ? 'selected' : '' ?>>3+</option>
                <option value="4" <?= $beds == '4' ? 'selected' : '' ?>>4+</option>
            </select>
            <select name="baths" class="border border-gray-300 px-3 py-2 text-sm bg-white">
                <option value="">Baths</option>
                <option value="1" <?= $baths == '1' ? 'selected' : '' ?>>1+</option>
                <option value="2" <?= $baths == '2' ? 'selected' : '' ?>>2+</option>
                <option value="3" <?= $baths == '3' ? 'selected' : '' ?>>3+</option>
            </select>
            <button type="submit" class="btn-gold text-sm px-4 py-2">Search</button>
        </form>
    </div>
</section>

<section class="py-10">
    <div class="container mx-auto px-4">
        <?php if (count($listings) > 0): ?>
        <p class="text-gray-500 text-sm mb-5"><?= count($listings) ?> listing<?= count($listings) != 1 ? 's' : '' ?> found</p>
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach ($listings as $listing): ?>
            <a href="/listing.php?id=<?= $listing['id'] ?>" class="listing-card bg-white border border-gray-200 overflow-hidden block fade-in">
                <div class="img-container h-52">
                    <?php if ($listing['image_1']): ?>
                        <img src="<?= htmlspecialchars($listing['image_1']) ?>" alt="<?= htmlspecialchars($listing['title']) ?>" class="w-full h-full object-cover">
                    <?php else: ?>
                        <div class="w-full h-full flex items-center justify-center bg-gray-100 text-gray-300">
                            <span class="text-xs uppercase tracking-wider">[Placeholder Image]</span>
                        </div>
                    <?php endif; ?>
                    <div class="absolute bottom-3 left-3 bg-gold text-navy font-bold px-2.5 py-1 text-sm">
                        $<?= number_format($listing['price']) ?>
                    </div>
                </div>
                <div class="p-5">
                    <h3 class="font-semibold text-navy"><?= htmlspecialchars($listing['title']) ?></h3>
                    <p class="text-gray-500 text-sm mt-1"><?= htmlspecialchars($listing['city']) ?>, MS</p>
                    <div class="flex items-center gap-4 mt-3 text-xs text-gray-600">
                        <span><?= $listing['beds'] ?> Bed</span><span class="text-gray-300">|</span>
                        <span><?= $listing['baths'] ?> Bath</span>
                        <?php if ($listing['sqft']): ?><span class="text-gray-300">|</span><span><?= number_format($listing['sqft']) ?> SF</span><?php endif; ?>
                    </div>
                    <?php if ($listing['agent_name']): ?>
                    <p class="text-[11px] text-gold mt-3 font-medium uppercase tracking-wider"><?= htmlspecialchars($listing['agent_name']) ?></p>
                    <?php endif; ?>
                </div>
            </a>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
        <div class="text-center py-16 border border-gray-200 bg-white">
            <p class="text-gray-400 text-sm">No listings match your criteria. Try adjusting your filters.</p>
        </div>
        <?php endif; ?>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>