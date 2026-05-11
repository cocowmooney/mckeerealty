<?php
$page_title = 'Contact Us';
$page_desc = 'Get in touch with McKee Realty in Flowood, MS. Call, email, or visit our office.';
require_once 'includes/header.php';

$form_sent = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['contact_submit'])) {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $message = trim($_POST['message'] ?? '');
    
    if ($name && $email && $message) {
        $ins = $db->prepare("INSERT INTO contacts (name, email, phone, message) VALUES (?, ?, ?, ?)");
        $ins->execute([$name, $email, $phone, $message]);
        mail('mckeerealty@att.net', 'McKee Realty - New Contact Message', "Name: $name\nEmail: $email\nPhone: $phone\n\nMessage:\n$message", "From: noreply@mckeerealtyinc.com");
        $form_sent = true;
    }
}
?>
<section class="bg-navy py-10">
    <div class="container mx-auto px-4">
        <h1 class="text-3xl font-bold text-white">Get In <span class="text-gold">Touch</span></h1>
        <p class="text-gray-400 text-sm mt-1">We're here to help with all your real estate needs</p>
    </div>
</section>

<section class="py-12 md:py-16">
    <div class="container mx-auto px-4">
        <div class="grid md:grid-cols-2 gap-10">
            <div>
                <h2 class="text-xl font-bold text-navy mb-5">Send Us a Message</h2>
                
                <?php if ($form_sent): ?>
                <div class="bg-green-50 border border-green-200 text-green-700 p-5 text-sm">Thanks for reaching out. One of our agents will get back to you shortly.</div>
                <?php else: ?>
                <form method="POST" class="space-y-4">
                    <div><input type="text" name="name" id="cf_name" placeholder="Your Name *" required class="border border-gray-300 w-full px-3 py-2.5 text-sm"></div>
                    <div><input type="email" name="email" id="cf_email" placeholder="Your Email *" required class="border border-gray-300 w-full px-3 py-2.5 text-sm"></div>
                    <div><input type="tel" name="phone" placeholder="Your Phone" class="border border-gray-300 w-full px-3 py-2.5 text-sm"></div>
                    <div><textarea name="message" id="cf_message" rows="4" placeholder="Your Message *" required class="border border-gray-300 w-full px-3 py-2.5 text-sm"></textarea></div>
                    <button type="submit" name="contact_submit" class="btn-primary text-sm">Send Message</button>
                </form>
                <?php endif; ?>
            </div>
            
            <div>
                <h2 class="text-xl font-bold text-navy mb-5">Our Office</h2>
                <div class="bg-white border border-gray-200 p-6 space-y-5">
                    <div class="flex items-start gap-3">
                        <div class="w-10 h-10 bg-navy flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5 text-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        </div>
                        <div><h3 class="font-semibold text-navy text-sm">Address</h3><p class="text-gray-600 text-sm mt-1">300 Belle Meade Pt. Ste. B<br>Flowood, MS 39232</p></div>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="w-10 h-10 bg-navy flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5 text-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                        </div>
                        <div><h3 class="font-semibold text-navy text-sm">Phone</h3><a href="tel:6019928141" class="text-gray-600 hover:text-navy text-sm mt-1 block">(601) 992-8141</a></div>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="w-10 h-10 bg-navy flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5 text-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        </div>
                        <div><h3 class="font-semibold text-navy text-sm">Email</h3><a href="mailto:mckeerealty@att.net" class="text-gray-600 hover:text-navy text-sm mt-1 block">mckeerealty@att.net</a></div>
                    </div>
                </div>
                <div class="mt-4 bg-gray-200 h-48 flex items-center justify-center text-gray-400 text-xs uppercase tracking-wider">
                    [Placeholder Map]
                </div>
            </div>
        </div>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>