<?php
$page_title = 'Property Details';
require_once 'includes/header.php';

$id = $_GET['id'] ?? 0;
$stmt = $db->prepare("SELECT l.*, a.name as agent_name, a.email as agent_email, a.phone as agent_phone, a.phone2 as agent_phone2, a.role as agent_role FROM listings l LEFT JOIN agents a ON l.agent_id = a.id WHERE l.id = ?");
$stmt->execute([$id]);
$listing = $stmt->fetch();

if (!$listing) {
    echo '<div class="container mx-auto px-4 py-20 text-center"><h1 class="text-3xl font-bold text-gray-400">Listing Not Found</h1><a href="/listings.php" class="btn-primary inline-block mt-4">Back to Listings</a></div>';
    require_once 'includes/footer.php';
    exit;
}

$page_title = htmlspecialchars($listing['title']);

// Collect images
$images = [];
for ($i = 1; $i <= 5; $i++) {
    if ($listing["image_$i"]) {
        $images[] = $listing["image_$i"];
    }
}

// Handle contact form submission
$form_success = false;
$form_error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['interest_submit'])) {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $message = trim($_POST['message'] ?? '');
    
    if ($name && $email && $message) {
        $ins = $db->prepare("INSERT INTO contacts (listing_id, name, email, phone, message) VALUES (?, ?, ?, ?, ?)");
        $ins->execute([$id, $name, $email, $phone, $message]);
        
        // Send email to agent
        $to = $listing['agent_email'] ?? 'mckeerealty@att.net';
        $subject = "Interest in: {$listing['title']}";
        $body = "Name: $name\nEmail: $email\nPhone: $phone\n\nMessage:\n$message\n\nProperty: {$listing['title']} - https://www.mckeerealtyinc.com/listing.php?id=$id";
        mail($to, $subject, $body, "From: noreply@mckeerealtyinc.com");
        
        // Mark notified
        $upd = $db->prepare("UPDATE contacts SET agent_notified = 1 WHERE id = ?");
        $upd->execute([$db->lastInsertId()]);
        
        $form_success = true;
    } else {
        $form_error = 'Please fill in all required fields.';
    }
}
?>
<!-- Listing Detail -->
<section class="bg-gray-50 py-8">
    <div class="container mx-auto px-4">
        <a href="/listings.php" class="text-navy hover:text-gold transition font-medium mb-4 inline-block">&larr; Back to Listings</a>
        
        <div class="lg:grid lg:grid-cols-3 lg:gap-8">
            <!-- Main Content -->
            <div class="lg:col-span-2">
                <!-- Image Gallery -->
                <div class="bg-white rounded-xl overflow-hidden shadow-md mb-6">
                    <div class="img-container h-64 md:h-96">
                        <?php if (count($images) > 0): ?>
                            <img id="main-image" src="<?= htmlspecialchars($images[0]) ?>" alt="<?= htmlspecialchars($listing['title']) ?>" class="w-full h-full object-cover">
                        <?php else: ?>
                            <div class="w-full h-full flex items-center justify-center bg-gray-200 text-gray-400">
                                <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            </div>
                        <?php endif; ?>
                    </div>
                    <?php if (count($images) > 1): ?>
                    <div class="flex gap-2 p-3 overflow-x-auto">
                        <?php foreach ($images as $i => $img): ?>
                        <img src="<?= htmlspecialchars($img) ?>" data-full="<?= htmlspecialchars($img) ?>" class="gallery-thumb w-20 h-16 rounded-lg object-cover <?= $i === 0 ? 'active' : '' ?>">
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
                </div>
                
                <!-- Property Details -->
                <div class="bg-white rounded-xl shadow-md p-6 mb-6">
                    <div class="flex flex-wrap items-start justify-between gap-4 mb-4">
                        <div>
                            <h1 class="text-2xl md:text-3xl font-bold text-navy"><?= htmlspecialchars($listing['title']) ?></h1>
                            <p class="text-gray-500 mt-1"><?= htmlspecialchars($listing['address'] ?? '') ?>, <?= htmlspecialchars($listing['city']) ?>, <?= $listing['state'] ?> <?= htmlspecialchars($listing['zip'] ?? '') ?></p>
                        </div>
                        <div class="text-right">
                            <div class="text-3xl font-bold text-navy">$<?= number_format($listing['price']) ?></div>
                            <span class="badge-<?= $listing['status'] ?> text-white text-xs font-semibold px-3 py-1 rounded-full uppercase inline-block mt-1"><?= $listing['status'] ?></span>
                        </div>
                    </div>
                    
                    <div class="flex flex-wrap gap-6 py-4 border-y border-gray-100 mb-4">
                        <div class="text-center">
                            <div class="text-xl font-bold text-navy"><?= $listing['beds'] ?></div>
                            <div class="text-sm text-gray-500">Bedrooms</div>
                        </div>
                        <div class="text-center">
                            <div class="text-xl font-bold text-navy"><?= $listing['baths'] ?></div>
                            <div class="text-sm text-gray-500">Bathrooms</div>
                        </div>
                        <div class="text-center">
                            <div class="text-xl font-bold text-navy"><?= number_format($listing['sqft']) ?></div>
                            <div class="text-sm text-gray-500">Square Feet</div>
                        </div>
                        <div class="text-center">
                            <div class="text-xl font-bold text-navy"><?= htmlspecialchars($listing['property_type']) ?></div>
                            <div class="text-sm text-gray-500">Property Type</div>
                        </div>
                    </div>
                    
                    <?php if ($listing['description']): ?>
                    <div>
                        <h2 class="text-xl font-semibold text-navy mb-3">Description</h2>
                        <p class="text-gray-600 leading-relaxed"><?= nl2br(htmlspecialchars($listing['description'])) ?></p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Sidebar -->
            <div class="lg:col-span-1">
                <!-- Agent Card -->
                <div class="bg-white rounded-xl shadow-md p-6 mb-6 sticky top-24">
                    <h3 class="font-semibold text-navy text-lg mb-4">Listed By</h3>
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-14 h-14 bg-navy rounded-full flex items-center justify-center text-gold font-bold text-xl">
                            <?= strtoupper(substr($listing['agent_name'] ?? 'M', 0, 1)) ?>
                        </div>
                        <div>
                            <p class="font-semibold text-navy"><?= htmlspecialchars($listing['agent_name'] ?? 'McKee Realty') ?></p>
                            <p class="text-sm text-gray-500"><?= htmlspecialchars($listing['agent_role'] ?? '') ?></p>
                        </div>
                    </div>
                    
                    <?php if ($listing['agent_phone']): ?>
                    <a href="tel:<?= $listing['agent_phone'] ?>" class="flex items-center gap-2 text-gray-600 hover:text-navy transition mb-2">
                        <svg class="w-4 h-4 text-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                        <?= $listing['agent_phone'] ?>
                    </a>
                    <?php endif; ?>
                    
                    <hr class="my-4">
                    
                    <!-- Interest Form -->
                    <h4 class="font-semibold text-navy mb-3">Interested in this property?</h4>
                    
                    <?php if ($form_success): ?>
                    <div class="bg-green-50 text-green-700 p-4 rounded-lg text-sm">
                        Thanks! Your message has been sent to <?= htmlspecialchars($listing['agent_name'] ?? 'the listing agent') ?>. They'll be in touch soon.
                    </div>
                    <?php else: ?>
                    <form method="POST" class="space-y-3">
                        <?php if ($form_error): ?>
                        <p class="text-red-500 text-sm"><?= $form_error ?></p>
                        <?php endif; ?>
                        <input type="text" name="name" placeholder="Your Name" required class="form-input text-sm">
                        <input type="email" name="email" placeholder="Your Email" required class="form-input text-sm">
                        <input type="tel" name="phone" placeholder="Your Phone" class="form-input text-sm">
                        <textarea name="message" rows="3" placeholder="I'm interested in this property..." required class="form-input text-sm"></textarea>
                        <button type="submit" name="interest_submit" class="btn-gold w-full">Send Inquiry</button>
                    </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>