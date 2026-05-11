<?php
$page_title = 'Home';
require_once 'includes/header.php';

$featured = $db->prepare("SELECT l.*, a.name as agent_name FROM listings l LEFT JOIN agents a ON l.agent_id = a.id WHERE l.status = 'active' AND l.featured = 1 ORDER BY l.created_at DESC LIMIT 6");
$featured->execute();
$featured_listings = $featured->fetchAll();

$total_active = $db->query("SELECT COUNT(*) as c FROM listings WHERE status = 'active'")->fetch()['c'];
$total_agents = $db->query("SELECT COUNT(*) as c FROM agents")->fetch()['c'];
?>
<!-- Hero -->
<section class="relative h-[65vh] md:h-[75vh] bg-navy overflow-hidden">
    <div class="absolute inset-0 bg-gradient-to-br from-navy via-navy-light to-navy"></div>
    <div class="hero-overlay absolute inset-0"></div>
    <div class="relative z-10 container mx-auto px-4 h-full flex items-center">
        <div class="max-w-2xl">
            <span class="text-gold font-medium tracking-[0.2em] uppercase text-xs">Flowood, Mississippi</span>
            <h1 class="text-4xl md:text-6xl font-bold text-white mt-4 leading-tight">
                Your Home<br>
                <span class="text-gold">Starts Here</span>
            </h1>
            <p class="text-gray-300 text-lg mt-4 max-w-xl leading-relaxed">
                Trusted real estate expertise in Central Mississippi. Whether buying or selling, we're here for you.
            </p>
            <div class="flex flex-wrap gap-3 mt-8">
                <a href="/listings.php" class="btn-gold px-6 py-2.5 text-sm">Browse Listings</a>
                <a href="/contact.php" class="inline-block border border-white/30 text-white px-6 py-2.5 text-sm font-medium hover:bg-white/10 transition">Contact Us</a>
            </div>
        </div>
    </div>
</section>

<!-- Stats -->
<section class="bg-navy border-t border-gold/20">
    <div class="container mx-auto px-4 py-10">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
            <div class="fade-in">
                <div class="text-3xl font-bold text-gold"><?= $total_agents ?></div>
                <div class="text-gray-400 text-xs uppercase tracking-wider mt-1">Expert Agents</div>
            </div>
            <div class="fade-in">
                <div class="text-3xl font-bold text-gold"><?= max($total_active, 100) ?>+</div>
                <div class="text-gray-400 text-xs uppercase tracking-wider mt-1">Properties Sold</div>
            </div>
            <div class="fade-in">
                <div class="text-3xl font-bold text-gold">15+</div>
                <div class="text-gray-400 text-xs uppercase tracking-wider mt-1">Years Serving MS</div>
            </div>
            <div class="fade-in">
                <div class="text-3xl font-bold text-gold">100%</div>
                <div class="text-gray-400 text-xs uppercase tracking-wider mt-1">Client Satisfaction</div>
            </div>
        </div>
    </div>
</section>

<!-- Featured Listings -->
<section class="py-16 md:py-20">
    <div class="container mx-auto px-4">
        <div class="text-center mb-10">
            <span class="text-gold font-medium tracking-[0.2em] uppercase text-xs">Featured Properties</span>
            <h2 class="text-3xl font-bold text-navy mt-2">Featured <span class="gold-underline">Listings</span></h2>
        </div>
        
        <?php if (count($featured_listings) > 0): ?>
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach ($featured_listings as $listing): ?>
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
                        <span><?= $listing['beds'] ?> Bed</span>
                        <span class="text-gray-300">|</span>
                        <span><?= $listing['baths'] ?> Bath</span>
                        <?php if ($listing['sqft']): ?>
                        <span class="text-gray-300">|</span>
                        <span><?= number_format($listing['sqft']) ?> SF</span>
                        <?php endif; ?>
                    </div>
                    <?php if ($listing['agent_name']): ?>
                    <p class="text-[11px] text-gold mt-3 font-medium uppercase tracking-wider"><?= htmlspecialchars($listing['agent_name']) ?></p>
                    <?php endif; ?>
                </div>
            </a>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
        <div class="text-center py-12 border border-gray-200 bg-white">
            <p class="text-gray-400 text-sm">No featured listings yet. Check back soon.</p>
        </div>
        <?php endif; ?>
        
        <div class="text-center mt-8">
            <a href="/listings.php" class="btn-primary text-sm">View All Listings</a>
        </div>
    </div>
</section>

<!-- Why Choose Us -->
<section class="py-16 md:py-20 bg-white border-t border-gray-200">
    <div class="container mx-auto px-4">
        <div class="text-center mb-10">
            <span class="text-gold font-medium tracking-[0.2em] uppercase text-xs">Why McKee Realty</span>
            <h2 class="text-3xl font-bold text-navy mt-2">Built on <span class="gold-underline">Trust</span></h2>
        </div>
        <div class="grid md:grid-cols-3 gap-8">
            <div class="text-center p-6 fade-in">
                <div class="w-12 h-12 bg-navy flex items-center justify-center mx-auto mb-4">
                    <svg class="w-6 h-6 text-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                </div>
                <h3 class="text-base font-semibold text-navy mb-2">Local Expertise</h3>
                <p class="text-gray-500 text-sm leading-relaxed">Deep knowledge of Central Mississippi markets — Flowood, Brandon, Madison, Ridgeland, and beyond.</p>
            </div>
            <div class="text-center p-6 fade-in">
                <div class="w-12 h-12 bg-navy flex items-center justify-center mx-auto mb-4">
                    <svg class="w-6 h-6 text-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </div>
                <h3 class="text-base font-semibold text-navy mb-2">15 Expert Agents</h3>
                <p class="text-gray-500 text-sm leading-relaxed">A dedicated team ready to help you find the perfect property or sell your home at the best price.</p>
            </div>
            <div class="text-center p-6 fade-in">
                <div class="w-12 h-12 bg-navy flex items-center justify-center mx-auto mb-4">
                    <svg class="w-6 h-6 text-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                </div>
                <h3 class="text-base font-semibold text-navy mb-2">Modern Marketing</h3>
                <p class="text-gray-500 text-sm leading-relaxed">Aggressive online campaigns reaching buyers where they search — because 98% start online.</p>
            </div>
        </div>
    </div>
</section>

<!-- CTA -->
<section class="py-14 bg-navy">
    <div class="container mx-auto px-4 text-center">
        <h2 class="text-2xl md:text-3xl font-bold text-white mb-3">Ready to Find Your Dream Home?</h2>
        <p class="text-gray-400 text-sm max-w-xl mx-auto mb-6 leading-relaxed">Our team is ready to help you navigate the Central Mississippi real estate market.</p>
        <div class="flex flex-wrap justify-center gap-3">
            <a href="/listings.php" class="btn-gold text-sm px-6 py-2.5">Browse Properties</a>
            <a href="/contact.php" class="inline-block border border-gold text-gold px-6 py-2.5 text-sm font-medium hover:bg-gold hover:text-navy transition">Get In Touch</a>
        </div>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>