<?php
$page_title = 'Home';
require_once 'includes/header.php';

// Get featured listings
$featured = $db->prepare("SELECT l.*, a.name as agent_name FROM listings l LEFT JOIN agents a ON l.agent_id = a.id WHERE l.status = 'active' AND l.featured = 1 ORDER BY l.created_at DESC LIMIT 6");
$featured->execute();
$featured_listings = $featured->fetchAll();

// Get total listings count
$total_active = $db->query("SELECT COUNT(*) as c FROM listings WHERE status = 'active'")->fetch()['c'];
$total_agents = $db->query("SELECT COUNT(*) as c FROM agents")->fetch()['c'];
?>
<!-- Hero Section -->
<section class="relative h-[70vh] md:h-[80vh] bg-navy overflow-hidden">
    <div class="absolute inset-0 bg-gradient-to-br from-navy via-navy-light to-navy"></div>
    <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(circle at 25% 25%, #c9a84c 1px, transparent 1px); background-size: 50px 50px;"></div>
    <div class="hero-overlay absolute inset-0"></div>
    <div class="relative z-10 container mx-auto px-4 h-full flex items-center">
        <div class="max-w-3xl">
            <span class="text-gold font-semibold tracking-widest uppercase text-sm">Flowood, Mississippi</span>
            <h1 class="text-4xl md:text-6xl lg:text-7xl font-bold text-white mt-4 leading-tight">
                Your Home<br>
                <span class="text-gold">Starts Here</span>
            </h1>
            <p class="text-gray-200 text-lg md:text-xl mt-6 max-w-xl">
                Trusted real estate expertise in Central Mississippi. Whether buying or selling, we're here for you every step of the way.
            </p>
            <div class="flex flex-wrap gap-4 mt-8">
                <a href="/listings.php" class="btn-gold text-lg px-8 py-3 inline-block">Browse Listings</a>
                <a href="/contact.php" class="bg-white/10 text-white border-2 border-white/30 px-8 py-3 rounded-lg font-semibold hover:bg-white/20 transition inline-block">Contact Us</a>
            </div>
        </div>
    </div>
</section>

<!-- Stats Bar -->
<section class="bg-navy border-t border-gold/20">
    <div class="container mx-auto px-4 py-8">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6 text-center">
            <div class="fade-in">
                <div class="text-3xl md:text-4xl font-bold text-gold"><?= $total_agents ?></div>
                <div class="text-gray-300 text-sm mt-1">Expert Agents</div>
            </div>
            <div class="fade-in">
                <div class="text-3xl md:text-4xl font-bold text-gold"><?= $total_active ?>+</div>
                <div class="text-gray-300 text-sm mt-1">Active Listings</div>
            </div>
            <div class="fade-in">
                <div class="text-3xl md:text-4xl font-bold text-gold">15+</div>
                <div class="text-gray-300 text-sm mt-1">Years Serving MS</div>
            </div>
            <div class="fade-in">
                <div class="text-3xl md:text-4xl font-bold text-gold">100%</div>
                <div class="text-gray-300 text-sm mt-1">Client Satisfaction</div>
            </div>
        </div>
    </div>
</section>

<!-- Featured Listings -->
<section class="py-16 md:py-24">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12">
            <span class="text-gold font-semibold tracking-widest uppercase text-sm">Featured Properties</span>
            <h2 class="text-3xl md:text-4xl font-bold text-navy mt-2">Featured <span class="gold-underline">Listings</span></h2>
        </div>
        
        <?php if (count($featured_listings) > 0): ?>
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php foreach ($featured_listings as $listing): ?>
            <a href="/listing.php?id=<?= $listing['id'] ?>" class="listing-card bg-white rounded-xl overflow-hidden shadow-md block fade-in">
                <div class="img-container h-56">
                    <?php if ($listing['image_1']): ?>
                        <img src="<?= htmlspecialchars($listing['image_1']) ?>" alt="<?= htmlspecialchars($listing['title']) ?>" class="w-full h-full">
                    <?php else: ?>
                        <div class="w-full h-full flex items-center justify-center bg-gray-200 text-gray-400">
                            <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        </div>
                    <?php endif; ?>
                    <div class="absolute top-4 left-4">
                        <span class="badge-<?= $listing['status'] ?> text-white text-xs font-semibold px-3 py-1 rounded-full uppercase"><?= $listing['status'] ?></span>
                    </div>
                    <div class="absolute bottom-4 left-4 bg-gold text-navy font-bold px-3 py-1 rounded-lg">
                        $<?= number_format($listing['price']) ?>
                    </div>
                </div>
                <div class="p-5">
                    <h3 class="font-semibold text-lg text-navy"><?= htmlspecialchars($listing['title']) ?></h3>
                    <p class="text-gray-500 text-sm mt-1"><?= htmlspecialchars($listing['city']) ?>, <?= $listing['state'] ?> <?= htmlspecialchars($listing['zip'] ?? '') ?></p>
                    <div class="flex items-center gap-4 mt-3 text-sm text-gray-600">
                        <span class="flex items-center gap-1">
                            <svg class="w-4 h-4 text-gold" fill="currentColor" viewBox="0 0 20 20"><path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/></svg>
                            <?= $listing['beds'] ?> Beds
                        </span>
                        <span class="flex items-center gap-1">
                            <svg class="w-4 h-4 text-gold" fill="currentColor" viewBox="0 0 20 20"><path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z"/><path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z"/></svg>
                            <?= $listing['baths'] ?> Baths
                        </span>
                        <?php if ($listing['sqft']): ?>
                        <span class="flex items-center gap-1">
                            <svg class="w-4 h-4 text-gold" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5 3a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2V5a2 2 0 00-2-2H5zm0 2h10v10H5V5z"/></svg>
                            <?= number_format($listing['sqft']) ?> sqft
                        </span>
                        <?php endif; ?>
                    </div>
                </div>
            </a>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
        <div class="text-center py-12">
            <p class="text-gray-400 text-lg">No featured listings yet. Check back soon!</p>
        </div>
        <?php endif; ?>
        
        <div class="text-center mt-10">
            <a href="/listings.php" class="btn-primary inline-block">View All Listings</a>
        </div>
    </div>
</section>

<!-- Why Choose Us -->
<section class="py-16 md:py-24 bg-white">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12">
            <span class="text-gold font-semibold tracking-widest uppercase text-sm">Why McKee Realty</span>
            <h2 class="text-3xl md:text-4xl font-bold text-navy mt-2">Built on <span class="gold-underline">Trust</span></h2>
        </div>
        <div class="grid md:grid-cols-3 gap-8">
            <div class="text-center p-8 fade-in">
                <div class="w-16 h-16 bg-navy rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                </div>
                <h3 class="text-xl font-semibold text-navy mb-2">Local Expertise</h3>
                <p class="text-gray-500">Deep knowledge of Central Mississippi markets — Flowood, Brandon, Madison, Ridgeland, and beyond.</p>
            </div>
            <div class="text-center p-8 fade-in">
                <div class="w-16 h-16 bg-navy rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </div>
                <h3 class="text-xl font-semibold text-navy mb-2">15 Expert Agents</h3>
                <p class="text-gray-500">A dedicated team of professionals ready to help you find the perfect property or sell your home.</p>
            </div>
            <div class="text-center p-8 fade-in">
                <div class="w-16 h-16 bg-navy rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                </div>
                <h3 class="text-xl font-semibold text-navy mb-2">Modern Marketing</h3>
                <p class="text-gray-500">Aggressive online marketing campaigns reaching buyers where they search — 98% start online.</p>
            </div>
        </div>
    </div>
</section>

<!-- CTA -->
<section class="py-16 bg-navy relative overflow-hidden">
    <div class="absolute inset-0 opacity-5" style="background-image: radial-gradient(circle at 50% 50%, #c9a84c 1px, transparent 1px); background-size: 30px 30px;"></div>
    <div class="container mx-auto px-4 text-center relative z-10">
        <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">Ready to Find Your Dream Home?</h2>
        <p class="text-gray-300 text-lg max-w-2xl mx-auto mb-8">Our team is ready to help you navigate the Central Mississippi real estate market.</p>
        <div class="flex flex-wrap justify-center gap-4">
            <a href="/listings.php" class="btn-gold text-lg px-8 py-3 inline-block">Browse Properties</a>
            <a href="/contact.php" class="border-2 border-gold text-gold px-8 py-3 rounded-lg font-semibold hover:bg-gold hover:text-navy transition inline-block">Get In Touch</a>
        </div>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>