<?php
$page_title = 'Our Agents';
$page_desc = 'Meet the McKee Realty team of expert real estate agents serving Central Mississippi.';
require_once 'includes/header.php';

$agents = $db->query("SELECT * FROM agents ORDER BY CASE WHEN role LIKE '%Broker%' THEN 0 ELSE 1 END, name ASC")->fetchAll();
?>
<section class="bg-navy py-10">
    <div class="container mx-auto px-4">
        <h1 class="text-3xl font-bold text-white">Meet Our <span class="text-gold">Team</span></h1>
        <p class="text-gray-400 text-sm mt-1">Dedicated professionals serving Central Mississippi</p>
    </div>
</section>

<section class="py-12 md:py-16">
    <div class="container mx-auto px-4">
        <?php if (count($agents) > 0): ?>
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach ($agents as $agent): ?>
            <div class="agent-card bg-white p-6 fade-in">
                <div class="text-center">
                    <div class="w-20 h-20 bg-navy flex items-center justify-center text-gold font-bold text-2xl mx-auto mb-4">
                        <?= strtoupper(substr($agent['name'], 0, 1)) ?>
                    </div>
                    <h3 class="text-lg font-semibold text-navy"><?= htmlspecialchars($agent['name']) ?></h3>
                    <p class="text-gold text-xs font-medium uppercase tracking-wider mt-1"><?= htmlspecialchars($agent['role']) ?></p>
                </div>
                <hr class="my-4">
                <div class="space-y-2 text-sm">
                    <?php if ($agent['email']): ?>
                    <a href="mailto:<?= htmlspecialchars($agent['email']) ?>" class="flex items-center gap-2 text-gray-600 hover:text-navy transition">
                        <svg class="w-3.5 h-3.5 text-gold shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        <span class="truncate"><?= htmlspecialchars($agent['email']) ?></span>
                    </a>
                    <?php endif; ?>
                    <?php if ($agent['phone']): ?>
                    <a href="tel:<?= $agent['phone'] ?>" class="flex items-center gap-2 text-gray-600 hover:text-navy transition">
                        <svg class="w-3.5 h-3.5 text-gold shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                        <?= $agent['phone'] ?>
                    </a>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
        <div class="text-center py-12 border border-gray-200 bg-white">
            <p class="text-gray-400 text-sm">[Placeholder] Agent information coming soon.</p>
        </div>
        <?php endif; ?>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>